#!/usr/bin/env python3
"""
Petsfolio Call Recording AI Analysis CLI Tool
Invoked by CodeIgniter 4 CLI worker or manually.
Supports automatic translation of regional languages (Telugu, Hindi, Tamil, etc.) to English.
Features Contextual NLP & LLM Evaluation for accurate scoring, QA metrics, and summaries.
Includes Abuse / Scolding / Negative Speech Detection & Strict Evaluation Rubric.
"""

import sys
import os
import json
import re
import urllib.request
import urllib.error
import tempfile
from collections import Counter

# Load .env variables into os.environ
def load_environment():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    candidates = [
        os.path.join(base_dir, '../../.env'),
        os.path.join(base_dir, '../../../.env'),
        os.path.join(os.getcwd(), '.env'),
        '/var/www/petsfolio/beta/admin/.env'
    ]
    for env_path in candidates:
        if os.path.exists(env_path):
            try:
                with open(env_path, 'r', encoding='utf-8', errors='ignore') as f:
                    for line in f:
                        line = line.strip()
                        if line and not line.startswith('#') and '=' in line:
                            k, v = line.split('=', 1)
                            k = k.strip()
                            v = v.strip().strip("'").strip('"')
                            if k and k not in os.environ:
                                os.environ[k] = v
            except Exception:
                pass
            break

load_environment()

# ─── Abuse, Scolding, Negative Speech & Unrelated Detection Dictionaries ────

ABUSE_PHRASES = [
    # Profanity & slurs
    'stupid', 'idiot', 'fool', 'dumb', 'moron', 'useless', 'worthless',
    'shut up', 'shut your mouth', 'damn', 'bastard', 'bloody',
    'crap', 'bullshit', 'nonsense', 'rubbish', 'trash', 'garbage',
    'pathetic', 'disgusting', 'shameless', 'liar', 'cheater', 'fraud',
    'mad', 'crazy', 'insane', 'what the hell', 'go to hell',
    # Transliterated Indian abuse
    'pagal', 'bewakoof', 'gadha', 'kamina', 'haramkhor', 'bakwas',
    'chutiya', 'saala', 'gadhe', 'ullu', 'nalayak',
]

SCOLDING_PHRASES = [
    'how dare you', 'you should be ashamed', 'shame on you',
    'incompetent', 'you people never', 'you always', 'every time you',
    'waste of time', 'waste of money', 'i will report you',
    'i will complain', 'you are lying', 'you lied', 'stop lying',
    'don\'t you dare', 'listen to me carefully', 'are you even listening',
    'do you even know', 'is this how you work', 'what kind of service',
    'worst service ever', 'never seen such', 'absolutely terrible',
    'totally unacceptable', 'completely useless', 'you call this service',
    'i demand', 'i insist', 'this is outrageous', 'ridiculous',
]

THREATENING_PHRASES = [
    'legal action', 'lawyer', 'court', 'consumer court',
    'call police', 'call the police', 'file a police complaint', 'police case', 'police complaint', 'inform the police', 'report to police',
    'sue you', 'file a case', 'consumer forum', 'media', 'social media',
    'i will expose', 'viral', 'consequences', 'you will pay',
    'i will make sure you pay', 'destroy your', 'ruin your', 'shut down',
]

NEGATIVE_SPEECH_INDICATORS = [
    'rude', 'arrogant', 'disrespectful', 'condescending', 'aggressive',
    'hostile', 'unprofessional', 'careless', 'negligent', 'irresponsible',
    'not helpful', 'unhelpful', 'uncooperative', 'dismissive',
    'hung up on me', 'cut the call', 'shouting', 'yelling', 'screaming',
    'raised voice', 'tone', 'attitude', 'no manners', 'badly behaved',
]

UNRELATED_PHRASES = [
    'cricket', 'ipl', 'match', 'score', 'stadium', 'football', 'sports',
    'movie', 'film', 'cinema', 'actor', 'actress', 'song', 'singing', 'music',
    'politics', 'election', 'minister', 'party', 'vote', 'government',
    'dating', 'relationship', 'girlfriend', 'boyfriend', 'marriage', 'wedding'
]

# ─── Verification Questions Knowledge Base ─────────────────────────────────
VERIFICATION_QUESTIONS_KB = [
    {
        "id": "q_why_work_pets",
        "category": "Behaviour & Pet Handling",
        "question": "Why did you choose to work with pets?",
        "q_triggers": ["why did you choose", "why work with pets", "why do you want", "interest in pets", "why choose"],
        "expected_ans_kw": ["love", "animals", "dogs", "cats", "enjoy", "comfortable", "passion", "caring", "pets", "pet"],
        "critical": True
    },
    {
        "id": "q_handle_bite",
        "category": "Behaviour & Pet Handling",
        "question": "If a dog tries to bite or misbehaves, what will you do?",
        "q_triggers": ["tries to bite", "bite", "misbehave", "aggressive", "dog attacks", "bark", "bites"],
        "expected_ans_kw": ["calm", "space", "shout", "hit", "gently", "control", "distance", "avoid", "careful", "stop"],
        "critical": True
    },
    {
        "id": "q_handle_scared",
        "category": "Behaviour & Pet Handling",
        "question": "How do you handle a scared or nervous dog?",
        "q_triggers": ["scared dog", "nervous dog", "afraid", "shy dog", "scared"],
        "expected_ans_kw": ["slowly", "gently", "softly", "space", "time", "trust", "comfort", "calm"],
        "critical": False
    },
    {
        "id": "q_experience",
        "category": "Prior Experience",
        "question": "Do you have prior practical experience handling pets?",
        "q_triggers": ["years of experience", "prior experience", "handled dogs", "experience with", "how many years", "how long"],
        "expected_ans_kw": ["years", "months", "handled", "worked", "experience", "pet", "dog", "walked", "trained", "groomed", "four", "one", "two", "three", "five"],
        "critical": True
    },
    {
        "id": "q_rules_offline",
        "category": "Ethics & Platform Rules",
        "question": "Do you agree to follow Petsfolio rules and not take clients offline?",
        "q_triggers": ["outside the app", "offline", "take booking outside", "direct payment", "petsfolio rules", "app policy", "ethical service"],
        "expected_ans_kw": ["agree", "yes", "follow", "rules", "insurance", "app", "policy", "only app", "no direct", "outside"],
        "critical": True
    },
    {
        "id": "q_run_away_injury",
        "category": "Situational Safety",
        "question": "What will you do if the dog runs away, gets injured, or sick during service?",
        "q_triggers": ["runs away", "injured", "accident", "gets sick", "emergency", "lost"],
        "expected_ans_kw": ["inform", "owner", "parent", "petsfolio", "doctor", "vet", "help", "calm", "stop", "immediate"],
        "critical": True
    },
    {
        "id": "q_punctuality_travel",
        "category": "Punctuality & Travel",
        "question": "Will you be punctual and how far can you travel for services?",
        "q_triggers": ["punctual", "late", "travel", "distance", "transportation", "vehicle", "how far", "hour"],
        "expected_ans_kw": ["yes", "time", "inform", "bike", "vehicle", "km", "distance", "punctual", "hour", "hours"],
        "critical": False
    },
    {
        "id": "q_verification_trust",
        "category": "Trustworthiness & Access",
        "question": "Are you comfortable with background/police verification and home access?",
        "q_triggers": ["verification", "police verification", "background check", "trust with home", "access", "responsibilities"],
        "expected_ans_kw": ["yes", "comfortable", "agree", "passport", "aadhaar", "id", "trust", "responsible", "access"],
        "critical": True
    }
]


def evaluate_verification_qa(transcript):
    """
    Evaluates verification question asking accuracy, answer relevance matching,
    and flags out-of-box / off-topic segments.
    """
    lines = [l.strip() for l in transcript.split('\n') if l.strip()]
    
    qa_checklist = []
    out_of_box_segments = []
    mismatched_answers = []
    
    # 1. Out-of-box / off-topic phrases to flag
    out_of_box_triggers = [
        'college student', 'important college', 'fighting system', 'doubts about your life',
        'doing drugs', 'drugs for', 'police officer', 'police will come', 'cricket', 'movie',
        'girlfriend', 'marriage', 'politics', 'blind contact'
    ]
    
    # Scan transcript lines for out-of-box topics
    for line in lines:
        match = re.match(r'(\[\d+\.\d+s\s*-\s*\d+\.\d+s\])?\s*(.*)', line)
        if match:
            ts = match.group(1) or "[00:00]"
            text = match.group(2).strip()
            text_lower = text.lower()
            
            for oob in out_of_box_triggers:
                if oob in text_lower:
                    out_of_box_segments.append({
                        "timestamp": ts,
                        "phrase": text,
                        "topic": oob.title(),
                        "note": f"Out-of-box segment flagged: '{text}' (Irrelevant to Petsfolio Verification)"
                    })
                    break

    # 2. Check each standard verification question in KB
    transcript_full_lower = transcript.lower()
    
    matched_q_count = 0
    total_q_count = len(VERIFICATION_QUESTIONS_KB)
    
    for item in VERIFICATION_QUESTIONS_KB:
        q_asked = False
        ans_relevant = False
        detected_q_line = ""
        detected_a_line = ""
        
        # Check if question was asked
        for idx, line in enumerate(lines):
            line_lower = line.lower()
            if any(trig in line_lower for trig in item['q_triggers']):
                q_asked = True
                detected_q_line = line
                
                # Check following 3 lines for response relevance
                ans_window = lines[idx+1 : idx+4]
                ans_text = " ".join(ans_window).lower()
                detected_a_line = " ".join([l for l in ans_window if not any(t in l.lower() for t in item['q_triggers'])])
                
                if any(kw in ans_text for kw in item['expected_ans_kw']):
                    ans_relevant = True
                break
        
        if q_asked:
            matched_q_count += 1
            if ans_relevant:
                status = "Matched"
                status_color = "success"
                note = "Question asked correctly and answer was relevant to pet service verification."
            else:
                status = "Mismatched Answer"
                status_color = "danger"
                note = "Question was asked, but service provider's answer was irrelevant, dodge, or out-of-box."
                mismatched_answers.append({
                    "question": item['question'],
                    "category": item['category'],
                    "detected_q": detected_q_line,
                    "detected_a": detected_a_line,
                    "note": note
                })
        else:
            status = "Not Asked"
            status_color = "warning"
            note = f"Verification question on '{item['category']}' was skipped by executive."
            
        qa_checklist.append({
            "id": item['id'],
            "category": item['category'],
            "question": item['question'],
            "status": status,
            "status_color": status_color,
            "critical": item['critical'],
            "detected_q": detected_q_line,
            "detected_a": detected_a_line,
            "note": note
        })

    question_coverage_pct = round((matched_q_count / max(1, total_q_count)) * 100.0, 1)

    return {
        "qa_checklist": qa_checklist,
        "question_coverage_pct": question_coverage_pct,
        "questions_asked_count": matched_q_count,
        "total_questions_count": total_q_count,
        "mismatched_answers": mismatched_answers,
        "out_of_box_segments": out_of_box_segments,
        "has_mismatched_answers": len(mismatched_answers) > 0,
        "has_out_of_box_segments": len(out_of_box_segments) > 0
    }


# Safely import Qdrant RAG engine module
try:
    import qdrant_rag
except ImportError:
    qdrant_rag = None


def ensure_local_file(input_path):
    if input_path.startswith("http://") or input_path.startswith("https://"):
        try:
            suffix = os.path.splitext(input_path)[1]
            if not suffix or len(suffix) > 5:
                suffix = ".mp3"
            
            temp_file = tempfile.NamedTemporaryFile(delete=False, suffix=suffix)
            req = urllib.request.Request(
                input_path, 
                headers={'User-Agent': 'Petsfolio-AIAnalyst/1.0'}
            )
            with urllib.request.urlopen(req, timeout=30) as response, open(temp_file.name, 'wb') as out_file:
                out_file.write(response.read())
            return temp_file.name, True
        except Exception as e:
            sys.stderr.write(f"Error downloading audio URL ({input_path}): {str(e)}\n")
            return None, False

    abs_path = os.path.abspath(input_path)
    return abs_path, False


def get_audio_duration(audio_path):
    """
    Robust audio duration extractor supporting WAV, MP3, M4A, OGG, AMR, AAC.
    Uses wave module, ffprobe CLI, mutagen, or MP3 frame size estimation fallback.
    """
    if not audio_path or not os.path.exists(audio_path):
        return 0.0

    # 1. Try wave module for WAV files
    try:
        import wave
        with wave.open(audio_path, 'rb') as wf:
            frames = wf.getnframes()
            rate = wf.getframerate()
            if rate > 0:
                return round(frames / float(rate), 1)
    except Exception:
        pass

    # 2. Try ffprobe CLI tool (fast & accurate for MP3, M4A, AAC, OGG)
    try:
        import subprocess
        res = subprocess.run(
            ["ffprobe", "-v", "error", "-show_entries", "format=duration", "-of", "default=noprintwrappers=1:nokey=1", audio_path],
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True,
            timeout=5
        )
        if res.returncode == 0 and res.stdout.strip():
            val = float(res.stdout.strip())
            if val > 0:
                return round(val, 1)
    except Exception:
        pass

    # 3. Try mutagen library if available
    try:
        import mutagen
        audio = mutagen.File(audio_path)
        if audio is not None and hasattr(audio, 'info') and hasattr(audio.info, 'length') and audio.info.length > 0:
            return round(float(audio.info.length), 1)
    except Exception:
        pass

    # 4. Fallback: Estimate duration based on file size (~128 kbps)
    try:
        file_size = os.path.getsize(audio_path)
        if file_size > 0:
            estimated_sec = round(file_size / 16000.0, 1)
            return max(1.0, estimated_sec)
    except Exception:
        pass

    return 0.0


def format_duration(seconds):
    """Formats numeric seconds into MM:SS string format e.g. 522.0 -> '08:42'."""
    sec = int(round(seconds or 0))
    mins = sec // 60
    rem_sec = sec % 60
    return f"{mins:02d}:{rem_sec:02d}"


def deduplicate_repetitive_hallucinations(text):
    """
    Post-processing cleaner for ASR output. Handles:
    1. Known hallucination phrase removal (YouTube training artifacts)
    2. Inline within-line phrase repetition collapse
    3. Consecutive identical line collapse (A, A, A -> A)
    4. Alternating pair loop collapse (A, B, A, B, A, B -> A, B)
    """
    if not text:
        return text

    lines = text.split('\n')
    ts_pattern = re.compile(r'^(\[\d+\.\d+s\s*-\s*\d+\.\d+s\])\s*(.*)')

    # ── Step 1: Known hallucination phrase blacklist ──
    # These are phrases Whisper hallucinates from its YouTube training data.
    # We strip them from line content (not remove entire lines).
    blacklist = [
        'in the comments below', 'let me know in the comments',
        'like and subscribe', 'subscribe to my channel',
        'thanks for watching', 'thank you for watching',
        'see you in the next video', 'welcome back to my channel',
        'control the fighting system', 'doubts about your life',
        'what is your life', 'you are a police officer',
        'you are going to get scared', 'blind contact with you',
        'fighting for a friend', 'fighting for dog walking',
        'enjoy the food', 'area where you can enjoy',
    ]
    blacklist_re = re.compile(
        '|'.join(re.escape(p) for p in blacklist),
        re.IGNORECASE
    )

    # ── Step 2: Inline within-line repetition collapse ──
    # e.g. "I don't have money, I don't have money" -> "I don't have money"
    inline_rep = re.compile(r'(\b.{3,80}?\b)(?:\s*[,.]?\s*\1){1,}', re.IGNORECASE)

    def clean_line_content(content):
        # Remove blacklisted hallucination phrases
        content = blacklist_re.sub('', content)
        # Collapse inline repetitions (up to 5 passes)
        for _ in range(5):
            new_c = inline_rep.sub(r'\1', content)
            if new_c == content:
                break
            content = new_c
        # Clean up leftover punctuation/whitespace
        content = re.sub(r'\s{2,}', ' ', content).strip()
        content = re.sub(r'^[\s,.\-]+|[\s,.\-]+$', '', content).strip()
        return content

    # ── Parse and clean each line ──
    parsed = []  # list of (timestamp_str, normalized_text, full_cleaned_line)
    for line in lines:
        line = line.strip()
        if not line:
            continue
        m = ts_pattern.match(line)
        if m:
            ts, content = m.group(1), m.group(2).strip()
        else:
            ts, content = '', line

        content = clean_line_content(content)
        if not content:
            continue

        norm = re.sub(r'[^\w\s]', '', content.lower()).strip()
        if not norm:
            continue

        full = f"{ts} {content}".strip() if ts else content
        parsed.append((ts, norm, full))

    if not parsed:
        return ''

    # ── Step 3: Collapse consecutive identical lines AND alternating pair loops ──
    result = []
    i = 0
    n = len(parsed)

    while i < n:
        _, norm_i, line_i = parsed[i]

        # --- Check for alternating A-B pair loop ---
        if i + 3 < n:  # Need at least A, B, A, B (4 lines) to detect a pair loop
            _, norm_j, line_j = parsed[i + 1]
            if norm_i != norm_j:
                # Count how many times the (A, B) pair repeats consecutively
                pair_repeats = 1
                k = i + 2
                while k + 1 < n:
                    _, norm_k, _ = parsed[k]
                    _, norm_k1, _ = parsed[k + 1]
                    if norm_k == norm_i and norm_k1 == norm_j:
                        pair_repeats += 1
                        k += 2
                    else:
                        break
                if pair_repeats >= 2:
                    # Alternating loop detected — keep only 1 pair
                    result.append(line_i)
                    result.append(line_j)
                    i = k
                    continue

        # --- Check for consecutive identical lines ---
        run_end = i + 1
        while run_end < n and parsed[run_end][1] == norm_i:
            run_end += 1

        # Keep only the first occurrence
        result.append(line_i)
        i = run_end  # skip all duplicates

    return '\n'.join(result)


def transcribe_audio(audio_path):
    """Transcribe audio with Faster-Whisper. Returns (transcript, duration_seconds)."""
    audio_duration = get_audio_duration(audio_path)
    try:
        from faster_whisper import WhisperModel
        model_size = os.getenv("WHISPER_MODEL", "small")
        model = WhisperModel(model_size, device="cpu", compute_type="int8")

        DOMAIN_PROMPT = (
            "Petsfolio verification call. Executive verifies service provider details: "
            "pet experience, dog walking, location, availability, ethical policy agreement."
        )

        # Three-tier transcription fallback for maximum compatibility
        segments = None
        info = None

        # Tier 1: Full anti-hallucination params including repetition_penalty + VAD
        try:
            segments, info = model.transcribe(
                audio_path,
                beam_size=5,
                task="translate",
                initial_prompt=DOMAIN_PROMPT,
                temperature=0.0,
                repetition_penalty=1.2,
                condition_on_previous_text=False,
                no_speech_threshold=0.6,
                compression_ratio_threshold=1.8,
                log_prob_threshold=-0.8,
                hallucination_silence_threshold=1.5,
                vad_filter=True,
                vad_parameters=dict(min_silence_duration_ms=400, speech_pad_ms=200)
            )
        except TypeError:
            # Tier 2: Without repetition_penalty (older faster-whisper versions)
            try:
                segments, info = model.transcribe(
                    audio_path,
                    beam_size=5,
                    task="translate",
                    initial_prompt=DOMAIN_PROMPT,
                    temperature=0.0,
                    condition_on_previous_text=False,
                    no_speech_threshold=0.6,
                    compression_ratio_threshold=1.8,
                    log_prob_threshold=-0.8,
                    hallucination_silence_threshold=1.5,
                    vad_filter=True,
                    vad_parameters=dict(min_silence_duration_ms=400, speech_pad_ms=200)
                )
            except TypeError:
                # Tier 3: Bare minimum params (maximum compatibility)
                segments, info = model.transcribe(
                    audio_path,
                    beam_size=5,
                    task="translate",
                    temperature=0.0,
                    condition_on_previous_text=False,
                )

        transcript_parts = []
        last_end = 0.0
        for segment in segments:
            text = segment.text.strip()
            if text:
                transcript_parts.append(f"[{segment.start:.1f}s - {segment.end:.1f}s] {text}")
                last_end = segment.end

        full_transcript = deduplicate_repetitive_hallucinations("\n".join(transcript_parts))

        if hasattr(info, 'duration') and info.duration and info.duration > 0:
            audio_duration = round(info.duration, 1)
        elif last_end > 0:
            audio_duration = round(last_end, 1)

        if full_transcript.strip():
            return full_transcript, audio_duration
    except Exception as e:
        sys.stderr.write(f"[WARN] Faster-Whisper transcription exception: {e}\n")
        import traceback
        traceback.print_exc(file=sys.stderr)

    filename = os.path.basename(audio_path)
    # Return sentinel prefix so downstream analysis can detect failed transcription
    return f"[NO_SPEECH_DETECTED] Audio recording ({filename}) loaded. No speech or verification content detected.", audio_duration


def clean_transcript_text(transcript):
    """
    Strips ASR timestamps like [0.0s - 2.0s] and corrects common phonetic mishearings.
    """
    clean = re.sub(r'\[\d+\.\d+s\s*-\s*\d+\.\d+s\]', '', transcript)
    clean = deduplicate_repetitive_hallucinations(clean)
    clean = re.sub(r'\s+', ' ', clean).strip()

    replacements = {
        r'\bSetsforu\b': 'Petsfolio',
        r'\bSetsfor\b': 'Petsfolio',
        r'\bPetsflo\b': 'Petsfolio',
        r'\bPets for u\b': 'Petsfolio',
        r'\bPets for you\b': 'Petsfolio',
        r'\bPetfolio\b': 'Petsfolio',
        r'\bPetrol\b': 'Petsfolio',
        r'\bPet roll\b': 'Petsfolio',
        r'\bPets file\b': 'Petsfolio',
        r'\bPets flow\b': 'Petsfolio',
        r'\bDog fighting\b': 'Dog walking',
        r'\bJagger\b': 'Walker',
        r'\bKata block\b': 'Code of conduct',
    }
    for pattern, repl in replacements.items():
        clean = re.sub(pattern, repl, clean, flags=re.IGNORECASE)

    return clean


def extract_dynamic_keywords(text):
    words = re.findall(r'\b[a-zA-Z]{4,}\b', text.lower())
    stop_words = {
        'the', 'that', 'have', 'with', 'this', 'from', 'they', 'will', 'would', 'there',
        'their', 'what', 'about', 'which', 'when', 'make', 'like', 'time', 'just', 'know',
        'take', 'people', 'into', 'year', 'your', 'good', 'some', 'could', 'them', 'other',
        'than', 'then', 'now', 'look', 'only', 'come', 'its', 'over', 'think', 'also',
        'back', 'after', 'use', 'two', 'how', 'our', 'work', 'first', 'well', 'way',
        'even', 'new', 'want', 'because', 'any', 'these', 'give', 'day', 'most', 'us',
        'audio', 'recording', 'file', 'processed', 'customer', 'provider', 'service',
        'calling', 'called', 'talking', 'hello', 'thank', 'thanks', 'name', 'petsfolio',
        'okay', 'knowledge', 'doing', 'been', 'working', 'choose', 'please', 'test', 'going',
        'having', 'saying', 'getting', 'sitting', 'there', 'here', 'sir', 'yes', 'right',
        'pass', 'passed', 'hour', 'hours', 'mins', 'minutes', 'number', 'same', 'much',
        'many', 'doing', 'does', 'did', 'done', 'said', 'says', 'tell', 'told', 'call',
        'calls', 'feel', 'feels', 'start', 'started', 'place', 'places', 'side', 'group',
        'home', 'house', 'world', 'future', 'friend', 'friends', 'search', 'searching',
        'asking', 'asked', 'question', 'answer', 'letter', 'block', 'expert', 'experts'
    }
    filtered = [w for w in words if w not in stop_words]
    counts = Counter(filtered).most_common(8)

    res = {word: count for word, count in counts}
    if not res:
        res = {"verification": 3, "experience": 2, "insurance": 2, "policy": 2}
    return res


def detect_abuse_and_negativity(text_lower):
    """
    Comprehensive detection of abusive language, scolding, threats, negative speech, and unrelated topics.
    Uses regex word boundaries to avoid false positives (e.g., 'hello' matching 'hell', or 'dog' in pet care).
    """
    abuse_found = []
    scolding_found = []
    threats_found = []
    negative_flags = []
    unrelated_found = []

    def phrase_matches(phrase, text):
        pattern = r'\b' + re.escape(phrase) + r'\b'
        return re.search(pattern, text) is not None

    for phrase in ABUSE_PHRASES:
        if phrase_matches(phrase, text_lower):
            abuse_found.append(phrase)

    for phrase in SCOLDING_PHRASES:
        if phrase_matches(phrase, text_lower):
            scolding_found.append(phrase)

    for phrase in THREATENING_PHRASES:
        if phrase_matches(phrase, text_lower):
            threats_found.append(phrase)

    for phrase in NEGATIVE_SPEECH_INDICATORS:
        if phrase_matches(phrase, text_lower):
            negative_flags.append(phrase)

    for phrase in UNRELATED_PHRASES:
        if phrase_matches(phrase, text_lower):
            unrelated_found.append(phrase)

    has_abuse = len(abuse_found) > 0 or len(scolding_found) > 0 or len(threats_found) > 0
    
    # Severity: 0=clean, 1=mild negative, 2=scolding, 3=abusive, 4=threatening/hostile
    severity = 0
    if negative_flags or unrelated_found:
        severity = 1
    if scolding_found:
        severity = 2
    if abuse_found:
        severity = 3
    if threats_found:
        severity = 4

    return {
        'has_abuse': has_abuse,
        'abuse_instances': abuse_found,
        'scolding_instances': scolding_found,
        'threat_instances': threats_found,
        'negative_speech_flags': negative_flags,
        'unrelated_topics': unrelated_found,
        'severity': severity,
        'total_flags': len(abuse_found) + len(scolding_found) + len(threats_found) + len(negative_flags)
    }


def generate_structured_summary(clean_text, word_count, sentiment, abuse_result, has_greeting, has_confirmation, has_closing, verification_status):
    """
    Generate an intelligent, transcript-aware executive summary covering verified facts, policies, and findings.
    """
    text_lower = clean_text.lower()
    summary_points = []
    
    # Check verification terms presence
    verification_terms = [
        'verification', 'verify', 'confirm', 'booking', 'pet', 'dogs', 'dog', 'cats', 'cat',
        'address', 'timing', 'schedule', 'experience', 'policy', 'guidelines', 'provider',
        'walker', 'sitter', 'groomer', 'identity', 'profile', 'petsfolio', 'ethical'
    ]
    has_verification_content = any(re.search(r'\b' + re.escape(term) + r'\b', text_lower) for term in verification_terms)

    # Non-verification audio or hostile/threat call handling
    if not has_verification_content or abuse_result['severity'] >= 4:
        summary_points.append("🔴 CRITICAL ALERT: Non-Verification / Hostile Audio Recorded.")
        summary_points.append("The uploaded recording does NOT contain a valid Petsfolio verification conversation.")
        if abuse_result['threat_instances']:
            summary_points.append(f"Threatening / police intervention language detected: '{', '.join(abuse_result['threat_instances'][:3])}'.")
        elif abuse_result['abuse_instances']:
            summary_points.append(f"Inappropriate language detected: '{', '.join(abuse_result['abuse_instances'][:3])}'.")
        else:
            summary_points.append("No verification-related topics were discussed during the call.")
        summary_points.append(f"Status: {verification_status.replace('_', ' ').title()}.")
        return " ".join(summary_points)

    # Main call purpose
    summary_points.append(f"Verification Executive conducted a formal verification call with the Service Provider ({word_count} words).")
    
    # Extract specific verified dialogue details
    details_verified = []
    
    # Prior experience
    exp_match = re.search(r'(\d+|\b(one|two|three|four|five|six|seven|eight|nine|ten))\s+years?', text_lower)
    if exp_match:
        details_verified.append(f"SP confirmed {exp_match.group(0)} of pet care / dog walking experience")
    elif 'dog walking' in text_lower or 'experience' in text_lower:
        details_verified.append("SP confirmed prior experience in dog walking and pet care")
        
    # Ethical policies & Off-app booking rule
    if any(phrase in text_lower for phrase in ['outside the app', 'ethical service', 'insurance policy', 'liability']):
        if 'outside' in text_lower and any(kw in text_lower for kw in ['insurance', 'liability', 'agree', "don't agree"]):
            details_verified.append("SP confirmed strict adherence to ethical service policies and agreed NOT to accept bookings outside the app due to insurance policy coverage")
        else:
            details_verified.append("SP agreed to follow ethical service policies")

    # Session Duration & Availability
    dur_match = re.search(r'(\d+(?:\.\d+)?)\s*(?:hour|hrs)', text_lower)
    if dur_match:
        details_verified.append(f"SP confirmed availability for {dur_match.group(0)} service sessions")
        
    if details_verified:
        summary_points.append("Key details verified: " + "; ".join(details_verified) + ".")
    else:
        summary_points.append("Verification Executive verified basic details and explained platform guidelines.")

    # Highlighting violations / issues per guidelines
    if abuse_result['has_abuse']:
        abuse_types = []
        if abuse_result['abuse_instances']:
            abuse_types.append(f"abusive language ({', '.join(abuse_result['abuse_instances'][:3])})")
        if abuse_result['scolding_instances']:
            abuse_types.append(f"scolding ({', '.join(abuse_result['scolding_instances'][:2])})")
        if abuse_result['threat_instances']:
            abuse_types.append(f"threats ({', '.join(abuse_result['threat_instances'][:2])})")
        summary_points.append(f"⚠️ SERIOUS VIOLATION DETECTED: {'; '.join(abuse_types)}.")
    elif abuse_result['unrelated_topics']:
        summary_points.append(f"⚠️ Unrelated conversation detected regarding: {', '.join(abuse_result['unrelated_topics'][:3])}.")

    # Status verdict
    summary_points.append(f"Call completed. Status: {verification_status.replace('_', ' ').title()}.")

    return " ".join(summary_points)


def analyze_with_llm(clean_text):
    """
    Performs RAG-augmented call analysis using Google Gemini Flash (Gemini 2.5 Flash / Flash Lite),
    OpenAI, or Ollama.
    """
    gemini_key = os.getenv("x-goog-api-key") or os.getenv("GEMINI_API_KEY") or os.getenv("GOOGLE_API_KEY")
    gemini_model = os.getenv("GEMINI_MODEL", "gemini-2.5-flash")
    openai_api_key = os.getenv("OPENAI_API_KEY")
    ollama_enabled = os.getenv("ENABLE_OLLAMA") or os.getenv("OLLAMA_HOST")

    if not gemini_key and not openai_api_key and not ollama_enabled:
        return None

    # 1. Retrieve RAG vector context from Qdrant if available
    rag_context_str = ""
    if qdrant_rag:
        try:
            matches = qdrant_rag.query_qdrant_guidelines(clean_text[:1000], top_k=4)
            if matches:
                rules = [f"[{m['title']}]\n{m['content']}" for m in matches]
                rag_context_str = "\n\nOFFICIAL CALL ANALYSIS BUSINESS RULES (Retrieved via Qdrant RAG Vector Search):\n" + "\n---\n".join(rules)
        except Exception:
            pass

    prompt = f"""You are an AI-based Verification Call Quality Auditor for Petsfolio pet care platform.
Analyze the following recorded verification call transcript strictly following the official Call Analysis Guidelines.

{rag_context_str}

CRITICAL INTENT & CONTEXT EVALUATION RULES:
- Analyze SEMANTIC INTENT and TONE rather than doing naive literal word matching.
- DO NOT flag domain terms or standard conversation out of context:
  * References to pets, dogs, cats, animals, or pet care routines in a pet platform are domain-relevant—NOT abuse or unrelated talk, UNLESS explicitly used with hostile intent to insult a human being (e.g. calling a person a name).
  * Standard greetings (e.g., "hello", "namaste") are polite call etiquette—NOT abuse.
  * Customer service commitments (e.g., "I will make sure to process your document", "I will confirm your timing") express helpful service—NOT threats.
- ONLY flag abuse (`has_abuse = 1`), scolding, or threats when the speaker's true INTENT is to insult, berate, demean, intimidate, or threaten another person.
- ONLY flag unrelated conversations when the speaker's intent is to engage in non-verification topics (e.g., cricket matches, movies, politics, personal relationship gossip).

MANDATORY BUSINESS CALCULATIONS:
1. Call Summary: Summarize the purpose of the call, verification details discussed, executive questions, SP responses, and explicitly mention any genuine issues/unrelated conversation.
2. Unrelated Conversation Detection: Identify sentences where intent is unrelated to verification (cricket, movies, songs, politics, sports, personal relationships).
3. Bad / Abusive Language Detection: Detect any genuine profanity, insults, scolding, or threats directed at a person.
4. Verification Relevance: Assess if call is relevant to verification or contains music/song/blank audio.
5. Weighted Call Rating (0.0 to 10.0):
   - Verification Relevance: 30%
   - Communication Quality: 25%
   - Professional Behaviour: 20%
   - Verification Completion: 15%
   - Unrelated Conversation: 5%
   - Appropriate Language: 5%
6. Rating Visual Indicator: rating_color MUST be "red" if serious issues exist (genuine abusive language, song/music uploaded, major irrelevance, failure to verify), else "green".
7. Alerts: Generate Critical (🔴), Warning (🟠), or Good (🟢) alerts based on intent evaluation.
8. Highlighted Conversations: Include array of flagged sentence items with speaker, timestamp, category, sentence, severity.

Transcript:
\"\"\"
{clean_text[:4000]}
\"\"\"

Return a valid JSON object ONLY with the following exact keys:
{{
  "summary": "<Structured summary covering call purpose, details verified, and explicitly mentioning any detected issues>",
  "verification_relevance": "<High|Medium|Low>",
  "unrelated_conversation_detected": <0 or 1>,
  "bad_language_detected": <0 or 1>,
  "audio_alert_detected": <0 or 1>,
  "call_quality": "<Good|Fair|Poor>",
  "communication_quality": "<Professional|Moderate|Unprofessional>",
  "call_rating": <float 0.0-10.0 weighted rating>,
  "rating_color": "<green|red>",
  "communication": <integer 1-10>,
  "professionalism": <integer 1-10>,
  "empathy": <integer 1-10>,
  "compliance": <integer 1-10>,
  "overall_rating": <float 0.0-10.0>,
  "customer_sentiment": "<Positive|Neutral|Negative>",
  "provider_sentiment": "<Positive|Neutral|Negative>",
  "verification_status": "<approved|re_verification|escalated|rejected>",
  "qa_score": <float 0.0-10.0>,
  "is_escalated": <0 or 1>,
  "has_complaint": <0 or 1>,
  "has_abuse": <0 or 1>,
  "abuse_instances": ["<abusive/scolding phrases>"],
  "negative_speech_flags": ["<negative tone indicators>"],
  "risk_score": <integer 0-10>,
  "alerts": [
    {{
      "type": "<Critical|Warning|Good>",
      "category": "<Category Name>",
      "message": "<Alert message>",
      "icon": "<🔴|🟠|🟢>"
    }}
  ],
  "highlighted_conversations": [
    {{
      "speaker": "<Verification Executive|Service Provider>",
      "timestamp": "<MM:SS>",
      "category": "<Unrelated Conversation|Abusive Language|Scolding|Threat>",
      "sentence": "<exact sentence>",
      "severity": "<Critical|Warning>"
    }}
  ],
  "ai_recommendation": "<Actionable recommendation for admin and executive>",
  "strengths": ["<strength 1>", "<strength 2>"],
  "weaknesses": ["<weakness 1>", "<weakness 2>"],
  "recommendations": ["<recommendation 1>", "<recommendation 2>"]
}}
"""

    # 1. Primary: Google Gemini Flash (Gemini 2.5 Flash / Flash Lite)
    if gemini_key:
        models_to_try = [gemini_model, "gemini-2.5-flash", "gemini-2.0-flash", "gemini-1.5-flash"]
        seen = set()
        models = [m for m in models_to_try if not (m in seen or seen.add(m))]

        for mod in models:
            try:
                url = f"https://generativelanguage.googleapis.com/v1beta/models/{mod}:generateContent?key={gemini_key}"
                payload = json.dumps({
                    "contents": [{"parts": [{"text": prompt}]}],
                    "generationConfig": {
                        "responseMimeType": "application/json",
                        "temperature": 0.1
                    }
                }).encode('utf-8')
                req = urllib.request.Request(url, data=payload, headers={"Content-Type": "application/json"})
                with urllib.request.urlopen(req, timeout=4.0) as resp:
                    data = json.loads(resp.read().decode('utf-8'))
                    text_out = data['candidates'][0]['content']['parts'][0]['text']
                    json_match = re.search(r'\{.*\}', text_out, re.DOTALL)
                    if json_match:
                        parsed = json.loads(json_match.group(0))
                        if isinstance(parsed, dict) and ('overall_rating' in parsed or 'call_rating' in parsed):
                            return parsed
            except Exception:
                continue

    # 2. Fallback: OpenAI if API key exists
    if openai_api_key:
        try:
            req_data = json.dumps({
                "model": os.getenv("OPENAI_MODEL", "gpt-3.5-turbo"),
                "messages": [{"role": "user", "content": prompt}],
                "temperature": 0.2
            }).encode('utf-8')
            req = urllib.request.Request(
                "https://api.openai.com/v1/chat/completions",
                data=req_data,
                headers={
                    "Content-Type": "application/json",
                    "Authorization": f"Bearer {openai_api_key}"
                }
            )
            with urllib.request.urlopen(req, timeout=15) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                content = data['choices'][0]['message']['content']
                json_match = re.search(r'\{.*\}', content, re.DOTALL)
                if json_match:
                    return json.loads(json_match.group(0))
        except Exception:
            pass

    # 3. Fallback: Ollama local server (qwen2.5:7b)
    if ollama_enabled:
        ollama_host = os.getenv("OLLAMA_HOST", "http://localhost:11434")
        ollama_model = os.getenv("OLLAMA_MODEL", "qwen2.5:7b")
        try:
            req_data = json.dumps({
                "model": ollama_model,
                "prompt": prompt,
                "stream": False,
                "format": "json"
            }).encode('utf-8')
            req = urllib.request.Request(
                f"{ollama_host}/api/generate",
                data=req_data,
                headers={"Content-Type": "application/json"}
            )
            with urllib.request.urlopen(req, timeout=15) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                content = data.get('response', '')
                json_match = re.search(r'\{.*\}', content, re.DOTALL)
                if json_match:
                    return json.loads(json_match.group(0))
        except Exception:
            pass

    return None


def dynamic_analyze_transcript(transcript):
    # ─── EARLY EXIT: No speech / failed transcription ─────────────
    # If the transcript starts with our sentinel, the audio had no real speech
    # (e.g. ringtone, music, silence, blank recording). Return zero-score rejection.
    if transcript.startswith('[NO_SPEECH_DETECTED]') or not transcript.strip():
        return {
            "transcript": transcript,
            "summary": "🔴 REJECTED: The uploaded audio does NOT contain any valid speech or verification conversation. "
                       "This may be a ringtone, music file, silence, or corrupted recording. "
                       "No verification was conducted. Status: Rejected.",
            "call_rating": 0.0,
            "rating_color": "red",
            "overall_rating": 0.0,
            "call_quality": "Poor",
            "communication_quality": "Unprofessional",
            "verification_relevance": "Low",
            "unrelated_conversation_detected": 1,
            "bad_language_detected": 0,
            "audio_alert_detected": 1,
            "communication": 0,
            "professionalism": 0,
            "empathy": 0,
            "compliance": 0,
            "customer_sentiment": "Negative",
            "provider_sentiment": "Negative",
            "resolution": "Follow-up Required",
            "verification_status": "rejected",
            "qa_score": 0.0,
            "is_escalated": 1,
            "has_complaint": 1,
            "has_abuse": 0,
            "abuse_instances": [],
            "negative_speech_flags": [],
            "risk_score": 10,
            "alerts": [{
                "type": "Critical Alert",
                "category": "Invalid Audio",
                "message": "No speech detected. Uploaded file is not a verification call recording (may be ringtone, music, silence, or corrupted file).",
                "icon": "🔴"
            }],
            "highlighted_conversations": [],
            "ai_recommendation": "REJECTED: The uploaded audio file does not contain any verification call. "
                                 "Please upload a valid verification call recording.",
            "strengths": [],
            "weaknesses": ["No speech or verification content detected in the uploaded audio"],
            "recommendations": ["Upload a valid verification call recording with actual spoken conversation"],
            "tags": ["Call Analysis", "Negative", "Automated QA", "rejected", "Invalid Audio"],
            "keywords": {}
        }

    clean_text = clean_transcript_text(transcript)
    text_lower = clean_text.lower()
    word_count = len(clean_text.split())

    # Try LLM analysis first if available
    llm_result = analyze_with_llm(clean_text)
    if llm_result and isinstance(llm_result, dict) and "overall_rating" in llm_result:
        llm_result["transcript"] = transcript
        llm_result["keywords"] = extract_dynamic_keywords(clean_text)
        # Ensure abuse fields exist even from LLM
        if 'has_abuse' not in llm_result:
            abuse_result = detect_abuse_and_negativity(text_lower)
            llm_result['has_abuse'] = 1 if abuse_result['has_abuse'] else 0
            llm_result['abuse_instances'] = abuse_result['abuse_instances'] + abuse_result['scolding_instances']
            llm_result['negative_speech_flags'] = abuse_result['negative_speech_flags'] + abuse_result['threat_instances']
        return llm_result

    # ─────────────────────────────────────────────────────────────────
    # STRICT NLP FALLBACK ENGINE WITH ABUSE DETECTION
    # ─────────────────────────────────────────────────────────────────

    # Detect abuse, scolding, threats, negative speech
    abuse_result = detect_abuse_and_negativity(text_lower)

    # Contextual positive phrases
    pos_phrases = [
        'thank you', 'thanks', 'great service', 'good service', 'perfect',
        'excellent', 'very helpful', 'confirm', 'happy with', 'looking forward',
        'approved', 'verified', 'everything clear', 'no issue', 'no problem',
        'no worry', 'sounds good', 'sure', 'definitely', 'welcome'
    ]

    # Contextual negative/complaint phrases (avoid single words like 'no' or 'sorry')
    neg_phrases = [
        'bad service', 'horrible', 'terrible', 'worst', 'unhappy',
        'disappointed', 'complaint', 'file a complaint', 'cancel my booking',
        'want refund', 'refund my', 'not satisfied', 'rude', 'cheat',
        'scam', 'waste of money', 'unacceptable', 'delay', 'issue with',
        'problem with', 'dispute'
    ]

    # Escalation indicators
    escalate_phrases = [
        'speak to manager', 'speak to supervisor', 'talk to manager',
        'talk to supervisor', 'escalate this', 'senior authority',
        'legal action', 'lawyer', 'consumer court', 'higher management'
    ]

    pos_score = sum(1 for p in pos_phrases if p in text_lower)
    neg_score = sum(1 for p in neg_phrases if p in text_lower)
    escalate_score = sum(1 for p in escalate_phrases if p in text_lower)

    # Sentiment determination (abuse overrides to Negative)
    if abuse_result['has_abuse'] or (neg_score > pos_score and neg_score >= 1):
        sentiment = "Negative"
        risk_score = min(10, 5 + neg_score * 2 + abuse_result['severity'] * 2)
    elif pos_score > neg_score:
        sentiment = "Positive"
        risk_score = min(2, max(0, neg_score))
    else:
        sentiment = "Neutral"
        risk_score = 2

    # Check greeting and professional structure
    has_greeting = any(g in text_lower for g in ['hello', 'hi', 'namaste', 'good morning', 'good afternoon', 'calling from petsfolio'])
    has_confirmation = any(c in text_lower for c in ['confirm', 'schedule', 'details', 'verification', 'booking', 'address', 'timing'])
    has_closing = any(cl in text_lower for cl in ['thank you', 'have a good day', 'nice day', 'goodbye', 'bye', 'welcome'])

    # ─── STRICT SCORING RUBRIC (Start at 5, earn your points) ──────

    # Communication (Clarity, active listening, articulation) — Base: 5
    comm_score = 5
    if has_greeting: comm_score += 1
    if has_closing: comm_score += 1
    if has_confirmation: comm_score += 1
    if pos_score >= 2: comm_score += 1
    if word_count > 100: comm_score += 1  # Adequate conversation length
    if neg_score > 1: comm_score -= 1
    if abuse_result['has_abuse']: comm_score -= 2
    comm_score = max(1, min(10, comm_score))

    # Professionalism (Greeting, adherence, tone) — Base: 5
    prof_score = 5
    if has_greeting: prof_score += 1
    if has_confirmation: prof_score += 1
    if has_closing: prof_score += 1
    if pos_score >= 1: prof_score += 1
    if neg_score > 0: prof_score -= 1
    # SEVERE penalty for abuse/scolding
    if abuse_result['abuse_instances']: prof_score = min(prof_score, 3) - len(abuse_result['abuse_instances'])
    if abuse_result['scolding_instances']: prof_score = min(prof_score, 4) - len(abuse_result['scolding_instances'])
    prof_score = max(1, min(10, prof_score))

    # Empathy (Customer rapport & understanding) — Base: 5
    emp_score = 5
    if any(e in text_lower for e in ['understand', 'help', 'assist', 'care']): emp_score += 1
    if any(e in text_lower for e in ['sorry for', 'apologize', 'apology']): emp_score += 1
    if 'sure' in text_lower or 'definitely' in text_lower: emp_score += 1
    if sentiment == "Negative": emp_score -= 1
    if abuse_result['has_abuse']: emp_score -= 3
    if abuse_result['negative_speech_flags']: emp_score -= 1
    emp_score = max(1, min(10, emp_score))

    # Compliance (Verification checklist adherence) — Base: 6
    comp_score = 6
    if has_confirmation: comp_score += 1
    if any(v in text_lower for v in ['name', 'identity', 'id proof', 'aadhaar', 'address']): comp_score += 1
    if any(v in text_lower for v in ['policy', 'terms', 'cancellation', 'guideline']): comp_score += 1
    if risk_score > 4: comp_score -= 2
    if abuse_result['severity'] >= 3: comp_score -= 2
    comp_score = max(1, min(10, comp_score))

    # Verification content check
    verification_terms = [
        'verification', 'verify', 'confirm', 'booking', 'pet', 'dogs', 'dog', 'cats', 'cat',
        'address', 'timing', 'schedule', 'experience', 'policy', 'guidelines', 'provider',
        'walker', 'sitter', 'groomer', 'identity', 'profile', 'petsfolio', 'ethical'
    ]
    has_verification_content = any(re.search(r'\b' + re.escape(term) + r'\b', text_lower) for term in verification_terms)

    if not has_verification_content or abuse_result['severity'] >= 4:
        comm_score = 1
        prof_score = 1
        emp_score = 1
        comp_score = 1
        overall = 1.5
        verification_status = "rejected"
        risk_score = 10
        sentiment = "Negative"
        is_escalated = 1
        has_complaint = 1
        qa_score = 0.0
    else:
        overall = round((comm_score + prof_score + emp_score + comp_score) / 4.0, 1)
        if abuse_result['has_abuse']:
            overall = min(overall, 4.0)

        # Verifier Quality & Reviewer metrics
        is_escalated = 1 if (escalate_score > 0 or risk_score >= 7 or abuse_result['severity'] >= 3) else 0
        has_complaint = 1 if (sentiment == "Negative" and (neg_score >= 2 or abuse_result['has_abuse'])) else 0

        if abuse_result['has_abuse'] or abuse_result['severity'] >= 3:
            verification_status = "escalated"
        elif risk_score <= 3 and overall >= 7.0:
            verification_status = "approved"
        elif is_escalated == 1 or risk_score >= 7:
            verification_status = "escalated"
        elif risk_score >= 4 or neg_score >= 1:
            verification_status = "re_verification"
        else:
            verification_status = "approved"

        qa_score = round(max(0.0, min(10.0, overall - (2.0 if is_escalated else 0) - (1.5 if has_complaint else 0) - (2.0 if abuse_result['has_abuse'] else 0))), 1)

    # ─── STRUCTURED SUMMARY GENERATION ─────────────────────────────
    summary = generate_structured_summary(
        clean_text, word_count, sentiment, abuse_result,
        has_greeting, has_confirmation, has_closing, verification_status
    )

    # ─── VERIFICATION QUESTION & OUT-OF-BOX EVALUATION ───────────────
    qa_eval = evaluate_verification_qa(transcript)

    # ─── SERVICE PROVIDER VERIFICATION STRENGTHS, WEAKNESSES & RECOMMENDATIONS ───
    
    # Dynamic Strengths (Tailored specifically for SP Verification)
    strengths = []
    if has_greeting and not abuse_result['has_abuse']:
        strengths.append("Professional and polite opening greeting with company identification")
    if has_confirmation:
        strengths.append("Verified Service Provider's registered service background and practical experience")
    if any(kw in text_lower for kw in ['outside the app', 'insurance', 'ethical', 'policy', 'agree']):
        strengths.append("Confirmed SP's agreement to ethical policies and off-app booking prevention")
    if pos_score > 0 and not abuse_result['has_abuse']:
        strengths.append("Maintained positive, solution-oriented verification tone")
    if has_closing:
        strengths.append("Proper verification call closing with clear next steps")
    if not strengths:
        strengths.append("Verification call was recorded for quality monitoring purposes")

    # Dynamic Weaknesses / Areas for Coaching (Strictly for SP Verification)
    weaknesses = []
    if abuse_result['abuse_instances']:
        weaknesses.append(f"⚠️ CRITICAL: Abusive language detected — {', '.join(abuse_result['abuse_instances'][:5])}")
    if abuse_result['scolding_instances']:
        weaknesses.append(f"⚠️ Scolding/berating behavior detected — {', '.join(abuse_result['scolding_instances'][:3])}")
    if abuse_result['threat_instances']:
        weaknesses.append(f"⚠️ Threatening language detected — {', '.join(abuse_result['threat_instances'][:3])}")
    if abuse_result['negative_speech_flags']:
        weaknesses.append(f"Negative speech patterns noted — {', '.join(abuse_result['negative_speech_flags'][:3])}")
    if qa_eval['has_mismatched_answers']:
        weaknesses.append("Service Provider gave mismatched or irrelevant answers to verification questions")
    if any(kw in text_lower for kw in ["don't agree", "outside app", "offline"]):
        weaknesses.append("Service Provider expressed hesitation regarding platform rules or taking bookings outside the app")
    if not has_closing:
        weaknesses.append("Call closing lacked formal appreciation and onboarding next-step summary")
    if not has_greeting:
        weaknesses.append("Missing proper greeting with Petsfolio brand identification")
    if is_escalated:
        weaknesses.append("Verification call required supervisor escalation or compliance review")
    if not weaknesses:
        weaknesses.append("Ensure cancellation policy and emergency protocols are explicitly stated to the Service Provider")

    # Dynamic Actionable AI Recommendations (Strictly for SP Verification Onboarding)
    recommendations = []
    if abuse_result['has_abuse']:
        recommendations.append("⚠️ MANDATORY: Conduct administrative review for use of abusive/inappropriate language")
        recommendations.append("Schedule professional communication coaching for the verifier involved")
    if abuse_result['scolding_instances']:
        recommendations.append("Provide conflict de-escalation coaching to handle verification conversations professionally")
    if qa_eval['has_mismatched_answers']:
        recommendations.append("Re-verify Service Provider on mismatched answer topics (e.g. experience or ethical app rules)")
    if any(kw in text_lower for kw in ["don't agree", "outside app", "offline"]):
        recommendations.append("Re-verify Service Provider on ethical policies — strictly prohibit taking bookings outside the Petsfolio app")
    if not has_closing:
        recommendations.append("Always conclude verification calls with a professional sign-off and confirm onboarding next steps")
    
    # Onboarding verification recommendations
    if any(kw in text_lower for kw in ['walking', 'sitter', 'grooming', 'training', 'pet', 'dog', 'cat']):
        recommendations.append("Reiterate Petsfolio app active tracking feature, emergency contact protocol, and insurance coverage to the Service Provider")
    else:
        recommendations.append("Ensure comprehensive verification of Service Provider's registered service experience and qualifications")

    if len(recommendations) < 2:
        recommendations.append("Confirm key interaction summary and onboarding status before closing the call")

    keywords = extract_dynamic_keywords(clean_text)

    # Combine all abuse-related lists for storage
    all_abuse_instances = abuse_result['abuse_instances'] + abuse_result['scolding_instances']
    all_negative_flags = abuse_result['negative_speech_flags'] + abuse_result['threat_instances']
    unrelated_topics = abuse_result['unrelated_topics']

    # ─── WEIGHTED CALL RATING (Section 8, 9, 10 of Guidelines) ──────
    # Weights: Relevance 30%, Communication 25%, Professionalism 20%, Completion 15%, Unrelated 5%, Appropriate Language 5%
    rel_score = 10.0 if has_confirmation else (6.0 if word_count > 30 else 3.0)
    unrelated_subscore = 0.0 if unrelated_topics else 10.0
    lang_subscore = 0.0 if abuse_result['has_abuse'] else 10.0
    comp_subscore = float(comp_score)

    weighted_rating = round(
        (rel_score * 0.30) +
        (float(comm_score) * 0.25) +
        (float(prof_score) * 0.20) +
        (comp_subscore * 0.15) +
        (unrelated_subscore * 0.05) +
        (lang_subscore * 0.05),
        1
    )

    if abuse_result['has_abuse']:
        weighted_rating = min(weighted_rating, 4.0)

    audio_alert_detected = 1 if ('song' in text_lower or 'music' in text_lower or word_count < 5) else 0
    rating_color = "red" if (abuse_result['has_abuse'] or weighted_rating < 6.0 or audio_alert_detected) else "green"

    # Build Structured Alerts (Section 11)
    alerts = []
    if abuse_result['has_abuse']:
        alerts.append({
            "type": "Critical Alert",
            "category": "Bad / Abusive Language",
            "message": f"Abusive/Inappropriate language detected: {', '.join(all_abuse_instances[:3])}",
            "icon": "🔴"
        })
    if audio_alert_detected:
        alerts.append({
            "type": "Critical Alert",
            "category": "Unrelated Audio",
            "message": "Song/music or empty audio detected instead of verification call",
            "icon": "🔴"
        })
    if unrelated_topics:
        alerts.append({
            "type": "Warning",
            "category": "Unrelated Conversation",
            "message": f"Unrelated discussion detected: {', '.join(unrelated_topics[:3])}",
            "icon": "🟠"
        })
    if not alerts:
        alerts.append({
            "type": "Good",
            "category": "Verification Conducted",
            "message": "Verification process completed professionally with no major issues",
            "icon": "🟢"
        })

    # Build Highlighted Conversations (Section 12)
    highlighted_conversations = []
    if abuse_result['has_abuse']:
        for item in all_abuse_instances[:3]:
            highlighted_conversations.append({
                "speaker": "Service Provider / Executive",
                "timestamp": "00:00",
                "category": "Abusive Language",
                "sentence": f"Detected phrase: '{item}'",
                "severity": "Critical"
            })

    # Add Mismatched / Irrelevant Answers to Highlighted Conversations
    for item in qa_eval['mismatched_answers'][:3]:
        highlighted_conversations.append({
            "speaker": "Service Provider",
            "timestamp": "00:00",
            "category": "Mismatched Answer",
            "sentence": f"Q: '{item['question']}' -> Ans: '{item['detected_a'] or 'Mismatched/Dodge answer'}'",
            "severity": "Warning"
        })

    # Add Out-of-Box / Off-Topic Segments to Highlighted Conversations
    for item in qa_eval['out_of_box_segments'][:3]:
        highlighted_conversations.append({
            "speaker": "Executive / Provider",
            "timestamp": item['timestamp'],
            "category": "Out-of-Box Segment",
            "sentence": f"Topic '{item['topic']}': '{item['phrase']}'",
            "severity": "Warning"
        })

    if unrelated_topics:
        for item in unrelated_topics[:3]:
            highlighted_conversations.append({
                "speaker": "Service Provider / Executive",
                "timestamp": "00:00",
                "category": "Unrelated Conversation",
                "sentence": f"Detected topic: '{item}'",
                "severity": "Warning"
            })

    # Add QA Evaluation alerts
    if qa_eval['has_mismatched_answers']:
        alerts.append({
            "type": "Warning",
            "category": "Irrelevant Answers",
            "message": f"Mismatched or irrelevant answers detected for {len(qa_eval['mismatched_answers'])} questions.",
            "icon": "🟠"
        })
    if qa_eval['has_out_of_box_segments']:
        alerts.append({
            "type": "Warning",
            "category": "Out-of-Box Conversation",
            "message": f"{len(qa_eval['out_of_box_segments'])} conversation segments went outside Petsfolio verification topics.",
            "icon": "🟠"
        })

    ai_rec = "The call was mostly relevant to verification. Continue maintaining professional standards."
    if abuse_result['has_abuse']:
        ai_rec = "CRITICAL: Abusive or disrespectful communication was flagged. Conduct immediate administrative review."
    elif qa_eval['has_mismatched_answers']:
        ai_rec = "Re-verify service provider on mismatched answer topics (e.g. experience or ethical app rules)."
    elif qa_eval['has_out_of_box_segments'] or unrelated_topics:
        ai_rec = "The executive should avoid unrelated topics (e.g. sports, movies, personal life) and keep verification concise."

    return {
        "transcript": transcript,
        "summary": summary,
        "call_rating": weighted_rating,
        "rating_color": rating_color,
        "overall_rating": weighted_rating,
        "call_quality": "Good" if weighted_rating >= 8.0 else ("Fair" if weighted_rating >= 5.0 else "Poor"),
        "communication_quality": "Professional" if prof_score >= 8 else ("Moderate" if prof_score >= 5 else "Unprofessional"),
        "verification_relevance": "High" if rel_score >= 8.0 else ("Medium" if rel_score >= 5.0 else "Low"),
        "unrelated_conversation_detected": 1 if (unrelated_topics or qa_eval['has_out_of_box_segments']) else 0,
        "bad_language_detected": 1 if abuse_result['has_abuse'] else 0,
        "audio_alert_detected": audio_alert_detected,
        "communication": comm_score,
        "professionalism": prof_score,
        "empathy": emp_score,
        "compliance": comp_score,
        "customer_sentiment": sentiment,
        "provider_sentiment": sentiment,
        "resolution": "Resolved" if risk_score < 4 and not abuse_result['has_abuse'] else "Follow-up Required",
        "verification_status": verification_status,
        "qa_score": qa_score,
        "is_escalated": is_escalated,
        "has_complaint": has_complaint,
        "has_abuse": 1 if abuse_result['has_abuse'] else 0,
        "abuse_instances": all_abuse_instances,
        "negative_speech_flags": all_negative_flags,
        "risk_score": risk_score,
        "alerts": alerts,
        "highlighted_conversations": highlighted_conversations,
        "ai_recommendation": ai_rec,
        "strengths": strengths,
        "weaknesses": weaknesses,
        "recommendations": recommendations,
        "tags": ["Call Analysis", sentiment, "Automated QA", verification_status] + (["Abuse Flagged"] if abuse_result['has_abuse'] else []),
        "keywords": keywords,
        "verification_qa": qa_eval
    }


def main():
    if len(sys.argv) < 2:
        error_res = {"error": "Missing audio file argument. Usage: python analyze.py <filepath_or_url>"}
        print(json.dumps(error_res))
        sys.exit(1)

    raw_input = sys.argv[1]
    audio_path, is_temp = ensure_local_file(raw_input)

    if not audio_path or not os.path.exists(audio_path):
        error_res = {"error": f"Audio file or URL could not be accessed: {raw_input}"}
        print(json.dumps(error_res))
        sys.exit(1)

    try:
        transcript, audio_duration = transcribe_audio(audio_path)
        if not audio_duration or audio_duration <= 0:
            audio_duration = get_audio_duration(audio_path)
        
        result = dynamic_analyze_transcript(transcript)
        result["audio_duration"] = audio_duration
        result["duration"] = format_duration(audio_duration)
        result["duration_formatted"] = format_duration(audio_duration)
        print(json.dumps(result, ensure_ascii=False, indent=2))
    finally:
        if is_temp and os.path.exists(audio_path):
            try:
                os.remove(audio_path)
            except Exception:
                pass

if __name__ == "__main__":
    main()
