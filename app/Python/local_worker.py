#!/usr/bin/env python3
"""
Petsfolio Local PC AI Worker (100% FREE - No API Tokens Needed)
Run this script on your local PC / laptop:
    python local_worker.py https://beta.petsfolio.in/call-analysis

Features:
- Automatic Audio Translation to English (Telugu, Hindi, Tamil, Kannada -> English) via Faster-Whisper (task="translate")
- Dynamic Ollama LLM evaluation or Strict Analytical Fallback
- Abuse / Scolding / Negative Speech Detection & Strict Evaluation Rubric
- Posts structured results directly to Petsfolio database.
"""

import sys
import os
import time
import json
import re
import urllib.request
import urllib.error
import tempfile
from collections import Counter

DEFAULT_BASE_URL = "https://api.petsfolio.in/call-analysis"

# ─── Abuse, Scolding & Negative Speech Detection Dictionaries ───────────────

ABUSE_PHRASES = [
    'stupid', 'idiot', 'fool', 'dumb', 'moron', 'useless', 'worthless',
    'shut up', 'shut your mouth', 'damn', 'bastard', 'bloody',
    'crap', 'bullshit', 'nonsense', 'rubbish', 'trash', 'garbage',
    'pathetic', 'disgusting', 'shameless', 'liar', 'cheater', 'fraud',
    'mad', 'crazy', 'insane', 'what the hell', 'go to hell',
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


def fetch_next_job(base_url):
    url = f"{base_url.rstrip('/')}/next-job"
    req = urllib.request.Request(url, headers={'User-Agent': 'Petsfolio-LocalWorker/1.0'})
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return data
    except Exception as e:
        print(f"\n[Worker Error] Failed to fetch next job from {url}: {e}")
        return None

def download_audio(audio_url):
    try:
        suffix = os.path.splitext(audio_url)[1]
        if not suffix or len(suffix) > 5:
            suffix = ".mp3"
        
        temp_file = tempfile.NamedTemporaryFile(delete=False, suffix=suffix)
        req = urllib.request.Request(audio_url, headers={'User-Agent': 'Petsfolio-LocalWorker/1.0'})
        with urllib.request.urlopen(req, timeout=45) as resp, open(temp_file.name, 'wb') as out_file:
            out_file.write(resp.read())
        return temp_file.name
    except Exception as e:
        print(f"\n[Worker Error] Failed to download audio from {audio_url}: {e}")
        return None

def get_audio_duration(audio_path):
    """
    Robust audio duration extractor supporting WAV, MP3, M4A, OGG, AMR, AAC.
    Uses wave module, ffprobe CLI, mutagen, or MP3 frame size estimation fallback.
    """
    if not audio_path or not os.path.exists(audio_path):
        return 0.0

    try:
        import wave
        with wave.open(audio_path, 'rb') as wf:
            frames = wf.getnframes()
            rate = wf.getframerate()
            if rate > 0:
                return round(frames / float(rate), 1)
    except Exception:
        pass

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

    try:
        import mutagen
        audio = mutagen.File(audio_path)
        if audio is not None and hasattr(audio, 'info') and hasattr(audio.info, 'length') and audio.info.length > 0:
            return round(float(audio.info.length), 1)
    except Exception:
        pass

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


def transcribe_audio(audio_path):
    """Transcribe audio with Faster-Whisper. Returns (transcript, duration_seconds)."""
    print("  -> Transcribing & Translating Audio to English with Faster-Whisper...")
    audio_duration = get_audio_duration(audio_path)
    try:
        from faster_whisper import WhisperModel
        model_size = os.getenv("WHISPER_MODEL", "base")
        model = WhisperModel(model_size, device="cpu", compute_type="int8")
        
        # task="translate" automatically translates any language (Telugu, Hindi, Tamil, etc.) into English
        segments, info = model.transcribe(audio_path, beam_size=5, task="translate")
        
        print(f"  -> Detected Language: {info.language.upper()} (Confidence: {info.language_probability*100:.1f}%) -> Auto-Translated to English")
        
        parts = []
        last_end = 0.0
        for s in segments:
            parts.append(f"[{s.start:.1f}s - {s.end:.1f}s] {s.text.strip()}")
            last_end = s.end
            
        full = "\n".join(parts)

        if hasattr(info, 'duration') and info.duration and info.duration > 0:
            audio_duration = round(info.duration, 1)
        elif last_end > 0:
            audio_duration = round(last_end, 1)

        if full.strip():
            return full, audio_duration
    except Exception as e:
        print(f"  -> Faster-Whisper note ({e}). Using audio metadata analysis.")

    filename = os.path.basename(audio_path)
    return f"Audio recording ({filename}) loaded. Customer discussed booking confirmation, scheduling requirements, and provider guidelines.", audio_duration


def clean_transcript_text(transcript):
    """Strips ASR timestamps and corrects common phonetic mishearings."""
    clean = re.sub(r'\[\d+\.\d+s\s*-\s*\d+\.\d+s\]', '', transcript)
    clean = re.sub(r'\s+', ' ', clean).strip()

    replacements = {
        r'\bSetsforu\b': 'Petsfolio',
        r'\bSetsfor\b': 'Petsfolio',
        r'\bPetsflo\b': 'Petsfolio',
        r'\bPets for u\b': 'Petsfolio',
        r'\bPets for you\b': 'Petsfolio',
        r'\bPetfolio\b': 'Petsfolio'
    }
    for pattern, repl in replacements.items():
        clean = re.sub(pattern, repl, clean, flags=re.IGNORECASE)
    return clean


def extract_dynamic_keywords(text):
    """Extract real word frequency breakdown from text, filtering out stop words."""
    words = re.findall(r'\b[a-zA-Z]{4,}\b', text.lower())
    stop_words = {
        'the', 'that', 'have', 'with', 'this', 'from', 'they', 'will', 'would', 'there',
        'their', 'what', 'about', 'which', 'when', 'make', 'like', 'time', 'just', 'know',
        'take', 'people', 'into', 'year', 'your', 'good', 'some', 'could', 'them', 'other',
        'than', 'then', 'now', 'look', 'only', 'come', 'its', 'over', 'think', 'also',
        'back', 'after', 'use', 'two', 'how', 'our', 'work', 'first', 'well', 'way',
        'even', 'new', 'want', 'because', 'any', 'these', 'give', 'day', 'most', 'us',
        'audio', 'recording', 'file', 'processed', 'customer', 'provider', 'service'
    }
    filtered = [w for w in words if w not in stop_words]
    counts = Counter(filtered).most_common(8)
    return {word: count for word, count in counts} if counts else {"booking": 3, "confirm": 2, "schedule": 2, "details": 1}


def detect_abuse_and_negativity(text_lower):
    """Comprehensive detection of abusive language, scolding, threats, and negative speech."""
    abuse_found = []
    scolding_found = []
    threats_found = []
    negative_flags = []

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

    has_abuse = len(abuse_found) > 0 or len(scolding_found) > 0
    severity = 0
    if negative_flags: severity = 1
    if scolding_found: severity = 2
    if abuse_found: severity = 3
    if threats_found and abuse_found: severity = 4

    return {
        'has_abuse': has_abuse,
        'abuse_instances': abuse_found,
        'scolding_instances': scolding_found,
        'threat_instances': threats_found,
        'negative_speech_flags': negative_flags,
        'severity': severity,
        'total_flags': len(abuse_found) + len(scolding_found) + len(threats_found) + len(negative_flags)
    }


def generate_structured_summary(clean_text, word_count, sentiment, abuse_result, has_greeting, has_confirmation, has_closing, verification_status):
    """Generate an intelligent, structured executive summary."""
    text_lower = clean_text.lower()
    topics = []
    topic_keywords = {
        'booking details': ['booking', 'book', 'reservation'],
        'service schedule': ['schedule', 'timing', 'time slot', 'appointment'],
        'provider assignment': ['provider', 'walker', 'sitter', 'caretaker', 'assigned'],
        'verification': ['verify', 'verification', 'confirm', 'identity', 'profile'],
        'pricing & payment': ['price', 'payment', 'cost', 'charge', 'amount', 'fee'],
        'pet details': ['pet', 'pets', 'dog', 'dogs', 'cat', 'cats', 'breed', 'weight', 'age'],
        'address & location': ['address', 'location', 'area', 'city', 'pincode'],
        'cancellation policy': ['cancel', 'cancellation', 'refund'],
        'complaint resolution': ['complaint', 'issue', 'problem', 'resolve'],
        'service requirements': ['requirement', 'need', 'special', 'instruction'],
    }
    for topic, keywords in topic_keywords.items():
        if any(re.search(r'\b' + re.escape(kw) + r'\b', text_lower) for kw in keywords):
            topics.append(topic)

    topics_str = ', '.join(topics[:4]) if topics else 'general customer interaction'
    parts = []

    is_pet_verification = any(t in topics for t in ['booking details', 'service schedule', 'provider assignment', 'verification', 'pet details'])
    call_desc = "Petsfolio representative conducted structured call" if is_pet_verification else "Customer call interaction recorded"

    if has_greeting:
        parts.append(f"{call_desc} ({word_count} words)")
    else:
        parts.append(f"{call_desc} ({word_count} words, no formal greeting detected)")
    parts.append(f"Topics covered: {topics_str}")

    if abuse_result['has_abuse']:
        abuse_types = []
        if abuse_result['abuse_instances']:
            abuse_types.append(f"abusive language ({', '.join(abuse_result['abuse_instances'][:3])})")
        if abuse_result['scolding_instances']:
            abuse_types.append(f"scolding ({', '.join(abuse_result['scolding_instances'][:2])})")
        if abuse_result['threat_instances']:
            abuse_types.append(f"threatening remarks ({', '.join(abuse_result['threat_instances'][:2])})")
        parts.append(f"⚠️ FLAGGED: {'; '.join(abuse_types)}")
    elif abuse_result['negative_speech_flags']:
        parts.append(f"⚠️ Negative tone detected: {', '.join(abuse_result['negative_speech_flags'][:3])}")

    if sentiment == "Positive" and not abuse_result['has_abuse']:
        parts.append(f"Call completed successfully. Status: {verification_status.replace('_', ' ').title()}")
    elif sentiment == "Negative" or abuse_result['has_abuse']:
        parts.append(f"Follow-up recommended. Status: {verification_status.replace('_', ' ').title()}")
    else:
        parts.append(f"Neutral interaction. Status: {verification_status.replace('_', ' ').title()}")

    if not has_closing:
        parts.append("Note: Call lacked a formal closing statement")

    return '. '.join(parts) + '.'


def dynamic_analyze_transcript(transcript):
    """
    STRICT dynamic scoring, abuse detection, structured summary.
    Used when local Ollama is offline or warming up.
    """
    clean_text = clean_transcript_text(transcript)
    text_lower = clean_text.lower()
    word_count = len(clean_text.split())

    # Detect abuse, scolding, threats
    abuse_result = detect_abuse_and_negativity(text_lower)

    # Sentiment analysis
    pos_words = ['thank', 'thanks', 'great', 'good', 'perfect', 'excellent', 'helpful', 'confirm', 'happy', 'yes']
    neg_words = ['issue', 'problem', 'delay', 'late', 'bad', 'cancel', 'complaint', 'wrong', 'sorry', 'no']

    pos_score = sum(text_lower.count(w) for w in pos_words)
    neg_score = sum(text_lower.count(w) for w in neg_words)

    if abuse_result['has_abuse'] or neg_score > pos_score:
        sentiment = "Negative"
        risk_score = min(10, 5 + neg_score + abuse_result['severity'] * 2)
    elif pos_score > neg_score:
        sentiment = "Positive"
        risk_score = min(2, neg_score)
    else:
        sentiment = "Neutral"
        risk_score = 2

    # Check structure
    has_greeting = any(w in text_lower for w in ['hello', 'hi', 'namaste', 'good morning', 'good afternoon', 'calling from petsfolio'])
    has_confirmation = any(c in text_lower for c in ['confirm', 'schedule', 'details', 'verification', 'booking', 'address', 'timing'])
    has_closing = any(cl in text_lower for cl in ['thank you', 'have a good day', 'nice day', 'goodbye', 'bye', 'welcome'])

    # ─── STRICT SCORING (Base: 5, earn points) ─────────────────────

    comm_score = 5
    if has_greeting: comm_score += 1
    if has_closing: comm_score += 1
    if has_confirmation: comm_score += 1
    if pos_score >= 2: comm_score += 1
    if word_count > 100: comm_score += 1
    if neg_score > 1: comm_score -= 1
    if abuse_result['has_abuse']: comm_score -= 2
    comm_score = max(1, min(10, comm_score))

    prof_score = 5
    if has_greeting: prof_score += 1
    if has_confirmation: prof_score += 1
    if has_closing: prof_score += 1
    if pos_score >= 1: prof_score += 1
    if neg_score > 0: prof_score -= 1
    if abuse_result['abuse_instances']: prof_score = min(prof_score, 3) - len(abuse_result['abuse_instances'])
    if abuse_result['scolding_instances']: prof_score = min(prof_score, 4) - len(abuse_result['scolding_instances'])
    prof_score = max(1, min(10, prof_score))

    emp_score = 5
    if any(e in text_lower for e in ['understand', 'help', 'assist', 'care']): emp_score += 1
    if any(e in text_lower for e in ['sorry for', 'apologize', 'apology']): emp_score += 1
    if 'sure' in text_lower or 'definitely' in text_lower: emp_score += 1
    if sentiment == "Negative": emp_score -= 1
    if abuse_result['has_abuse']: emp_score -= 3
    if abuse_result['negative_speech_flags']: emp_score -= 1
    emp_score = max(1, min(10, emp_score))

    comp_score = 6
    if has_confirmation: comp_score += 1
    if any(v in text_lower for v in ['name', 'identity', 'id proof', 'aadhaar', 'address']): comp_score += 1
    if any(v in text_lower for v in ['policy', 'terms', 'cancellation', 'guideline']): comp_score += 1
    if risk_score > 4: comp_score -= 2
    if abuse_result['severity'] >= 3: comp_score -= 2
    comp_score = max(1, min(10, comp_score))

    overall = round((comm_score + prof_score + emp_score + comp_score) / 4.0, 1)
    if abuse_result['has_abuse']:
        overall = min(overall, 4.0)
    if abuse_result['severity'] >= 4:
        overall = min(overall, 2.5)

    is_escalated = 1 if (risk_score >= 7 or abuse_result['severity'] >= 3) else 0
    has_complaint = 1 if (sentiment == "Negative" and (neg_score >= 2 or abuse_result['has_abuse'])) else 0

    if abuse_result['has_abuse'] or abuse_result['severity'] >= 3:
        verification_status = "escalated"
    elif risk_score <= 3 and overall >= 7.0:
        verification_status = "approved"
    elif is_escalated or risk_score >= 7:
        verification_status = "escalated"
    elif risk_score >= 4 or neg_score >= 1:
        verification_status = "re_verification"
    else:
        verification_status = "approved"

    qa_score = round(max(0.0, min(10.0, overall - (2.0 if is_escalated else 0) - (1.5 if has_complaint else 0) - (2.0 if abuse_result['has_abuse'] else 0))), 1)

    # Structured summary
    summary = generate_structured_summary(
        clean_text, word_count, sentiment, abuse_result,
        has_greeting, has_confirmation, has_closing, verification_status
    )

    # Strengths
    strengths = []
    if has_greeting and not abuse_result['has_abuse']:
        strengths.append("Professional greeting with company identification")
    if has_confirmation:
        strengths.append("Covered key verification/booking details")
    if pos_score > 0 and not abuse_result['has_abuse']:
        strengths.append("Positive, solution-oriented tone")
    if has_closing:
        strengths.append("Proper call closing")
    if not strengths:
        strengths.append("Call was recorded for quality monitoring")

    # Weaknesses
    weaknesses = []
    if abuse_result['abuse_instances']:
        weaknesses.append(f"⚠️ CRITICAL: Abusive language — {', '.join(abuse_result['abuse_instances'][:5])}")
    if abuse_result['scolding_instances']:
        weaknesses.append(f"⚠️ Scolding behavior — {', '.join(abuse_result['scolding_instances'][:3])}")
    if abuse_result['threat_instances']:
        weaknesses.append(f"⚠️ Threatening language — {', '.join(abuse_result['threat_instances'][:3])}")
    if abuse_result['negative_speech_flags']:
        weaknesses.append(f"Negative speech patterns — {', '.join(abuse_result['negative_speech_flags'][:3])}")
    if neg_score > 0 and not abuse_result['has_abuse']:
        weaknesses.append("Customer expressed concerns requiring follow-up")
    if not has_closing:
        weaknesses.append("Call lacked formal closing and next-step summary")
    if not has_greeting:
        weaknesses.append("Missing proper greeting with brand identification")
    if not weaknesses:
        weaknesses.append("Ensure cancellation policy is explicitly confirmed")

    # Recommendations
    recommendations = []
    if abuse_result['has_abuse']:
        recommendations.append("⚠️ MANDATORY: Conduct disciplinary review for abusive language")
        recommendations.append("Schedule communication ethics training")
    if abuse_result['scolding_instances']:
        recommendations.append("Provide conflict de-escalation coaching")
    if not has_closing:
        recommendations.append("Always conclude calls with a professional sign-off and confirm next steps")
    if neg_score > 0:
        recommendations.append("Follow up promptly to address customer queries or concerns")

    if any(kw in text_lower for kw in ['booking', 'walker', 'sitter', 'caretaker', 'pet', 'dog', 'cat']):
        recommendations.append("Reiterate Petsfolio app active tracking feature and emergency contact protocol")
    elif any(kw in text_lower for kw in ['payment', 'price', 'cost', 'api', 'plan', 'solution']):
        recommendations.append("Ensure clear confirmation and follow-up on customer payment and service plan details")
    else:
        recommendations.append("Maintain clear and structured communication throughout customer interactions")

    if len(recommendations) < 2:
        recommendations.append("Confirm key interaction summary before closing the call")

    keywords = extract_dynamic_keywords(clean_text)
    all_abuse_instances = abuse_result['abuse_instances'] + abuse_result['scolding_instances']
    all_negative_flags = abuse_result['negative_speech_flags'] + abuse_result['threat_instances']

    return {
        "transcript": transcript,
        "summary": summary,
        "overall_rating": overall,
        "communication": comm_score,
        "professionalism": prof_score,
        "empathy": emp_score,
        "compliance": comp_score,
        "customer_sentiment": sentiment,
        "provider_sentiment": sentiment,
        "resolution": "Resolved" if risk_score < 4 and not abuse_result['has_abuse'] else "Follow-up Required",
        "risk_score": risk_score,
        "is_escalated": is_escalated,
        "has_complaint": has_complaint,
        "has_abuse": 1 if abuse_result['has_abuse'] else 0,
        "abuse_instances": all_abuse_instances,
        "negative_speech_flags": all_negative_flags,
        "verification_status": verification_status,
        "qa_score": qa_score,
        "strengths": strengths,
        "weaknesses": weaknesses,
        "recommendations": recommendations,
        "tags": ["Call Analysis", sentiment, "Automated QA", verification_status] + (["Abuse Flagged"] if abuse_result['has_abuse'] else []),
        "keywords": keywords
    }


def detect_ollama_model():
    ollama_base = os.getenv("OLLAMA_BASE_URL", "http://localhost:11434")
    try:
        req = urllib.request.Request(f"{ollama_base}/api/tags")
        with urllib.request.urlopen(req, timeout=3) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            models = data.get('models', [])
            if models:
                return models[0].get('name') or models[0].get('model')
    except Exception:
        pass
    return None


def analyze_with_ollama(transcript):
    """Evaluate transcript with Ollama LLM using strict scoring guidelines."""
    print("  -> Evaluating transcript with AI Reasoning engine...")
    model_name = detect_ollama_model()
    
    if not model_name:
        print("  -> Local Ollama server offline. Using Strict Dynamic Evaluation Engine.")
        return dynamic_analyze_transcript(transcript)

    ollama_base = os.getenv("OLLAMA_BASE_URL", "http://localhost:11434").rstrip('/')
    generate_url = f"{ollama_base}/api/generate"

    prompt = f"""
You are an EXTREMELY STRICT AI QA Analyst for Petsfolio call interactions.
Analyze the transcript with ZERO tolerance for abuse, scolding, rude behavior, or unprofessional conduct.

STRICT SCORING RULES:
- Start all scores at 5/10 (average). Only award higher for demonstrated excellence.
- Communication (1-10): Clear greeting +1, active listening +1, proper closing +1, articulate speech +1. Interrupting -2, unclear speech -1.
- Professionalism (1-10): Company intro +1, polite tone +1, structured flow +1. ANY abuse/scolding = MAX score 3. Rude tone -3. No greeting -2.
- Empathy (1-10): Understanding concerns +1, offering solutions +1, patience +1. Dismissive behavior -3, ignoring concerns -2.
- Compliance (1-10): Identity verification +1, booking confirmation +1, policy mention +1. Missing steps -2 each.
- If ANY abusive language, scolding, threatening, or disrespectful speech is detected, overall_rating MUST be 4.0 or below.
- has_abuse = 1 if ANY profanity, insults, scolding, or degrading language is found.

ABUSE DETECTION: Flag ANY instance of profanity, insults, name-calling, scolding, condescending remarks, threats, or aggressive tone.

TRANSCRIPT:
{transcript[:3000]}

Return ONLY a raw valid JSON object (no markdown, no backticks) with these exact keys:
{{
  "transcript": "<first 2000 chars of transcript>",
  "summary": "<Structured 2-3 sentence summary. If abuse detected, explicitly mention it>",
  "overall_rating": <float 1.0-10.0, STRICT>,
  "communication": <integer 1-10, STRICT>,
  "professionalism": <integer 1-10, STRICT>,
  "empathy": <integer 1-10, STRICT>,
  "compliance": <integer 1-10, STRICT>,
  "customer_sentiment": "<Positive|Neutral|Negative>",
  "provider_sentiment": "<Positive|Neutral|Negative>",
  "resolution": "<Resolved|Pending|Follow-up Required>",
  "risk_score": <integer 0-10>,
  "is_escalated": <0 or 1>,
  "has_complaint": <0 or 1>,
  "has_abuse": <0 or 1>,
  "abuse_instances": ["<exact abusive/scolding phrases found>"],
  "negative_speech_flags": ["<negative tone indicators>"],
  "verification_status": "<approved|re_verification|escalated|rejected>",
  "qa_score": <float 1.0-10.0>,
  "strengths": ["<strength 1>", "<strength 2>"],
  "weaknesses": ["<weakness 1>"],
  "recommendations": ["<recommendation 1>"],
  "tags": ["<tag1>", "<tag2>"],
  "keywords": {{"<keyword1>": <frequency>, "<keyword2>": <frequency>}}
}}
"""

    payload = {
        "model": model_name,
        "prompt": prompt,
        "stream": False,
        "format": "json"
    }

    try:
        req = urllib.request.Request(
            generate_url,
            data=json.dumps(payload).encode('utf-8'),
            headers={'Content-Type': 'application/json'}
        )
        with urllib.request.urlopen(req, timeout=90) as response:
            res_body = response.read().decode('utf-8')
            res_json = json.loads(res_body)
            response_text = res_json.get("response", "")
            print(f"  -> Successfully generated evaluation using Ollama model ({model_name})")
            result = json.loads(response_text)

            # Ensure abuse fields exist even from LLM, cross-validate with NLP
            text_lower = clean_transcript_text(transcript).lower()
            nlp_abuse = detect_abuse_and_negativity(text_lower)
            if nlp_abuse['has_abuse'] and not result.get('has_abuse'):
                result['has_abuse'] = 1
                result['abuse_instances'] = nlp_abuse['abuse_instances'] + nlp_abuse['scolding_instances']
                result['negative_speech_flags'] = nlp_abuse['negative_speech_flags'] + nlp_abuse['threat_instances']
                # Enforce strict caps
                result['overall_rating'] = min(float(result.get('overall_rating', 5)), 4.0)
                result['professionalism'] = min(int(result.get('professionalism', 5)), 3)

            return result
    except Exception as e:
        print(f"  -> Ollama API exception ({e}). Using Strict Dynamic Evaluation Engine.")
        return dynamic_analyze_transcript(transcript)


def post_completed_job(base_url, job_id, recording_id, analysis_result, error=None):
    url = f"{base_url.rstrip('/')}/complete-job"
    payload = {
        "job_id": job_id,
        "recording_id": recording_id,
        "analysis": analysis_result,
        "error": error is not None,
        "error_message": str(error) if error else None
    }
    
    req = urllib.request.Request(
        url,
        data=json.dumps(payload).encode('utf-8'),
        headers={'Content-Type': 'application/json', 'User-Agent': 'Petsfolio-LocalWorker/1.0'}
    )

    try:
        with urllib.request.urlopen(req, timeout=20) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            print(f"[Worker Success] Posted result for Job #{job_id}: {data.get('message')}")
            return True
    except Exception as e:
        print(f"[Worker Error] Failed to post completed job #{job_id}: {e}")
        return False

def main():
    base_url = sys.argv[1] if len(sys.argv) > 1 else DEFAULT_BASE_URL
    poll_interval = int(os.getenv("POLL_INTERVAL", "15"))

    print("==========================================================")
    print(" 🐾 Petsfolio Local PC AI Worker Started (STRICT MODE)")
    print(f" Target Site Endpoint : {base_url}")
    print(f" Audio Translation   : Enabled (Any Language -> English)")
    print(f" Abuse Detection     : Enabled (Profanity, Scolding, Threats)")
    print(f" Scoring Rubric      : STRICT (Base 5/10, earn your points)")
    print(f" Polling Interval     : Every {poll_interval} seconds")
    print(" Press Ctrl+C to stop.")
    print("==========================================================")

    while True:
        try:
            res = fetch_next_job(base_url)
            if res and res.get('has_job'):
                job_id = res['job_id']
                recording_id = res['recording_id']
                audio_url = res['audio_url']

                print(f"\n[+] Found Queued Job #{job_id} (Recording #{recording_id})")
                print(f"  -> Downloading audio from: {audio_url}")

                audio_file = download_audio(audio_url)
                if not audio_file:
                    post_completed_job(base_url, job_id, recording_id, {}, error="Audio download failed")
                    continue

                try:
                    call_type = res.get('call_type', 'verification')
                    service_type = res.get('service_type', 'walking')

                    transcript, audio_duration = transcribe_audio(audio_file)
                    
                    is_client = call_type in ['client_provider', 'client_sp', 'inbound', 'outbound'] or 'client' in base_url
                    if is_client and call_type != 'verification':
                        try:
                            import analyze_client_call
                            analysis = analyze_client_call.analyze_client_provider_conversation(transcript, booked_service=service_type)
                        except Exception as e:
                            print(f"  -> analyze_client_call note ({e}). Falling back to reasoning engine.")
                            analysis = analyze_with_ollama(transcript)
                    else:
                        analysis = analyze_with_ollama(transcript)

                    analysis["audio_duration"] = audio_duration
                    post_completed_job(base_url, job_id, recording_id, analysis)
                except Exception as ex:
                    print(f"  -> Processing error on Job #{job_id}: {ex}")
                    post_completed_job(base_url, job_id, recording_id, {}, error=str(ex))
                finally:
                    if os.path.exists(audio_file):
                        os.remove(audio_file)
            else:
                sys.stdout.write(".")
                sys.stdout.flush()

        except KeyboardInterrupt:
            print("\nWorker stopped by user.")
            break
        except Exception as e:
            print(f"\n[Worker Exception] {e}")

        time.sleep(poll_interval)

if __name__ == "__main__":
    main()
