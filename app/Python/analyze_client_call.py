#!/usr/bin/env python3
"""
Petsfolio Client <-> Service Provider Call Summary & Service Conversation Analysis Engine
Analyzes mobile-to-mobile recorded calls between Clients/Pet Parents and Service Providers across:
- Walking
- Grooming
- Training
- Sitting

Adheres strictly to the Petsfolio AI Integration Specification (Sections 1-37).
Supports speech-to-text with automatic translation from regional languages (Telugu, Hindi, Tamil, etc.) to English.
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

# Safely import Qdrant Client RAG Vector Engine
try:
    import qdrant_client_rag
except ImportError:
    qdrant_client_rag = None

CORE_PET_TERMS = [
    'pet', 'pets', 'dog', 'dogs', 'puppy', 'puppies', 'cat', 'cats', 'kitten', 'kittens',
    'animal', 'animals', 'breed', 'labrador', 'golden retriever', 'german shepherd',
    'husky', 'shih tzu', 'beagle', 'indie', 'pug', 'rottweiler', 'doberman', 'persian',
    'petsfolio', 'leash', 'collar', 'paw', 'paws', 'vet', 'vaccination', 'veterinary'
]

SERVICE_KB = {
    'walking': {
        'name': 'Walking',
        'terms': [
            'walk', 'walking', 'walker', 'dog walk', 'morning walk', 'evening walk',
            'missed walk', 'leash', 'collar', 'paw cleaning', 'combing the dog',
            'potty', 'poop routine', 'poop walk', 'pee walk', 'dog park', 'dog walking'
        ],
        'inclusions': ['Walk timing', 'Leash requirement', 'Pet safety', 'Paw cleaning', 'Combing', 'Poop/potty routine'],
    },
    'grooming': {
        'name': 'Grooming',
        'terms': [
            'groom', 'grooming', 'groomer', 'dog bath', 'bathing the dog', 'hair cut', 'haircut',
            'hair trimming', 'trim', 'trimming', 'anti-tick', 'ticks', 'flea',
            'nail trimming', 'nail clipping', 'ear cleaning', 'blow dry', 'shampoo',
            'brushing fur', 'matting', 'pet spa', 'dog hygiene'
        ],
        'inclusions': ['Grooming appointment', 'Bath & drying', 'Hair trimming', 'Anti-tick treatment', 'Nail clipping', 'Ear cleaning'],
    },
    'training': {
        'name': 'Training',
        'terms': [
            'training', 'trainer', 'training session', 'puppy training', 'obedience',
            'command', 'commands', 'sit command', 'stay command', 'come command', 'heel command',
            'down command', 'jumping problem', 'barking problem', 'biting problem',
            'leash pulling', 'dog aggression', 'socialization', 'potty training'
        ],
        'inclusions': ['Training schedule', 'Basic/advanced obedience', 'Behavior correction', 'Puppy training', 'Command practice'],
    },
    'sitting': {
        'name': 'Sitting',
        'terms': [
            'pet sitting', 'dog sitting', 'cat sitting', 'sitter', 'day care', 'daycare',
            'overnight sitting', 'hourly sitting', 'boarding', 'feed the dog', 'feed the pet',
            'pet food', 'dog food', 'cat food', 'pet medication', 'kennel'
        ],
        'inclusions': ['Sitting duration', 'Feeding schedule', 'Routine & sleeping arrangements', 'Medication administration', 'Day/overnight care'],
    }
}

# ─── Unrelated Topics Dictionaries (Section 9 & 10) ──────────────────────────

UNRELATED_CATEGORIES = {
    'Cricket & Sports': ['cricket', 'ipl', 'match', 'score', 'stadium', 'football', 'fifa', 'tennis', 'badminton', 'kabaddi', 'world cup'],
    'Movies & Entertainment': ['movie', 'film', 'cinema', 'actor', 'actress', 'theatre', 'netflix', 'series', 'hero', 'heroine', 'song', 'singing', 'music'],
    'Politics & Government': ['politics', 'election', 'minister', 'party', 'vote', 'voting', 'government', 'bjp', 'congress', 'mla', 'mp', 'leader'],
    'Personal Vehicles & Assets': ['bought a new car', 'new car', 'bought car', 'my car', 'honda', 'hyundai', 'maruti', 'bike', 'new bike', 'buying a flat', 'real estate', 'property'],
    'Personal Relationships & Family': ['dating', 'relationship', 'girlfriend', 'boyfriend', 'marriage', 'wedding', 'divorce', 'relative', 'family gossip', 'your mom', 'my mother', 'friend', 'friends', 'my friend'],
    'Personal Work & Outside Business': ['developer', 'stunt developer', 'software', 'coding', 'office work', 'crypto', 'bitcoin', 'stock market', 'shares', 'trading', 'loan', 'credit card', 'personal business'],
    'General Gossip & Banter': ['where are you from', 'which city', 'native place', 'native village', 'village', 'how was your weekend', 'revan', 'days left', 'more than 20 days', 'ask for final']
}

# ─── Off-Platform Payment Triggers (Section 18) ─────────────────────────────

OFF_PLATFORM_PAYMENT_TRIGGERS = [
    'pay you directly', 'pay direct', 'pay directly', 'send directly', 'direct payment',
    'pay me directly', 'send me directly', 'cash in hand', 'cash to you',
    'outside petsfolio', 'outside the app', 'without petsfolio', 'skip petsfolio',
    'pay via gpay directly', 'pay via phonepe directly', 'send to my personal gpay',
    'send to my personal number', 'pay me cash', 'direct transfer to my account',
    'pay less if direct', 'discount if outside app', 'no need to pay in app'
]

# ─── Contact Sharing Triggers (Section 19) ──────────────────────────────────

CONTACT_SHARING_TRIGGERS = [
    'personal phone number', 'give me your number', 'take my number', 'call my personal number',
    'whatsapp me', 'share your whatsapp', 'my whatsapp number is', 'send on whatsapp',
    'personal email', 'give me your email', 'my personal contact', 'contact me directly on'
]

# ─── Inappropriate & Abusive Speech Dictionaries (Section 12) ────────────────

ABUSIVE_PHRASES = [
    'stupid', 'idiot', 'fool', 'dumb', 'moron', 'useless', 'worthless',
    'shut up', 'shut your mouth', 'damn', 'bastard', 'bloody',
    'crap', 'bullshit', 'nonsense', 'rubbish', 'trash', 'garbage',
    'pathetic', 'disgusting', 'shameless', 'liar', 'cheater', 'fraud',
    'mad', 'crazy', 'insane', 'what the hell', 'go to hell',
    'pagal', 'bewakoof', 'gadha', 'kamina', 'haramkhor', 'bakwas',
    'chutiya', 'saala', 'gadhe', 'ullu', 'nalayak'
]

THREATENING_PHRASES = [
    'legal action', 'lawyer', 'consumer court', 'police complaint',
    'call police', 'file a case', 'sue you', 'i will expose you',
    'you will pay for this', 'i will destroy you', 'shut you down'
]

# ─── Complaints & Service Issues Dictionaries (Section 13, 14, 15, 16) ────────

COMPLAINT_TRIGGERS = [
    'came late', 'came 30 minutes late', 'arrived late', 'very late', 'walker did not arrive',
    'did not come', 'didn\'t come', 'did not arrive', 'missed walk', 'not handled properly',
    'dog was hurt', 'incomplete grooming', 'missed session', 'missed training',
    'poor quality', 'bad service', 'behaved improperly', 'unprofessional behavior',
    'worst service', 'very disappointed', 'not happy with service', 'terrible service',
    'waste of money', 'angry because'
]

REFUND_TRIGGERS = [
    'want my money back', 'money back', 'refund my money', 'i want a refund',
    'refund request', 'give me refund', 'return my money', 'cancel and refund',
    'compensation', 'deduct money'
]

CANCELLATION_TRIGGERS = [
    'cancel the walk', 'cancel the booking', 'cancel my booking', 'want to cancel',
    'don\'t need tomorrow', 'don\'t need the walk', 'cancel today\'s service',
    'stop the service', 'cancellation'
]

RESCHEDULING_TRIGGERS = [
    'reschedule', 'change the time', 'move to tomorrow', 'postpone', 'shift the time',
    'come at different time', 'adjust the timing', 'rescheduled', 'can we do it tomorrow'
]


def ensure_local_file(input_path):
    if input_path.startswith("http://") or input_path.startswith("https://"):
        try:
            suffix = os.path.splitext(input_path)[1]
            if not suffix or len(suffix) > 5:
                suffix = ".mp3"
            
            temp_file = tempfile.NamedTemporaryFile(delete=False, suffix=suffix)
            req = urllib.request.Request(
                input_path, 
                headers={'User-Agent': 'Petsfolio-ClientCallAnalyst/1.0'}
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
            return max(1.0, round(file_size / 16000.0, 1))
    except Exception:
        pass

    return 0.0


def format_duration(seconds):
    sec = int(round(seconds or 0))
    mins = sec // 60
    rem_sec = sec % 60
    return f"{mins:02d}:{rem_sec:02d}"


def transcribe_audio(audio_path, booked_service='walking'):
    """Transcribes audio with Faster-Whisper, auto-translating regional languages to English."""
    audio_duration = get_audio_duration(audio_path)
    try:
        from faster_whisper import WhisperModel
        model_size = os.getenv("WHISPER_MODEL", "small")
        model = WhisperModel(model_size, device="cpu", compute_type="int8")

        segments = None
        info = None

        try:
            segments, info = model.transcribe(
                audio_path,
                beam_size=5,
                task="translate",
                initial_prompt=None,
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
            segments, info = model.transcribe(
                audio_path,
                beam_size=5,
                task="translate",
                initial_prompt=None,
                temperature=0.0,
                condition_on_previous_text=False
            )

        transcript_parts = []
        last_end = 0.0
        for segment in segments:
            text = segment.text.strip()
            if text:
                # Filter out generic whisper hallucination phrases on silence
                if re.search(r'^(Petsfolio client and service provider|Thank you for watching|Subtitles by|Amara\.org)', text, flags=re.IGNORECASE):
                    continue
                transcript_parts.append(f"[{segment.start:.1f}s - {segment.end:.1f}s] {text}")
                last_end = segment.end

        full_transcript = "\n".join(transcript_parts)
        if hasattr(info, 'duration') and info.duration and info.duration > 0:
            audio_duration = round(info.duration, 1)
        elif last_end > 0:
            audio_duration = round(last_end, 1)

        if full_transcript.strip():
            return full_transcript, audio_duration
    except Exception as e:
        sys.stderr.write(f"[WARN] Client Call Whisper transcription exception: {e}\n")

    filename = os.path.basename(audio_path)
    return f"[NO_SPEECH_DETECTED] Audio recording ({filename}) loaded. No client-provider conversation detected.", audio_duration


def clean_transcript_text(transcript):
    clean = re.sub(r'\[\d+\.\d+s\s*-\s*\d+\.\d+s\]', '', transcript)
    clean = re.sub(r'\s+', ' ', clean).strip()

    replacements = {
        r'\bSetsforu\b': 'Petsfolio',
        r'\bPetsflo\b': 'Petsfolio',
        r'\bPets for you\b': 'Petsfolio',
        r'\bPetfolio\b': 'Petsfolio',
        r'\bPetrol\b': 'Petsfolio',
        r'\bDog fighting\b': 'Dog walking',
    }
    for pattern, repl in replacements.items():
        clean = re.sub(pattern, repl, clean, flags=re.IGNORECASE)

    return clean


def analyze_with_llm(clean_text, booked_service='walking'):
    """
    Performs RAG-augmented Client <-> SP call analysis using Google Gemini Flash (Gemini 2.5 Flash / Flash Lite),
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
    if qdrant_client_rag:
        try:
            matches = qdrant_client_rag.query_client_qdrant_guidelines(clean_text[:1000], service_type=booked_service, top_k=4)
            if matches:
                rules = [f"[{m['title']}]\n{m['content']}" for m in matches]
                rag_context_str = "\n\nOFFICIAL CLIENT-PROVIDER CALL GUIDELINES (Retrieved via Qdrant Vector Store):\n" + "\n---\n".join(rules)
        except Exception:
            pass

    prompt = f"""You are an AI-based Conversation Auditor for Petsfolio pet care platform.
Analyze the following recorded Mobile-to-Mobile call transcript between a Client / Pet Parent and a Service Provider (Booked Service: {booked_service.upper()}).
Follow the official Client-Provider Call Guidelines strictly.

{rag_context_str}

CRITICAL AUDITING OBJECTIVES:
1. What was discussed? (Concise summary)
2. Was the conversation related to the booked Petsfolio service ({booked_service})?
3. Admin Attention: Flag any off-platform direct payments, personal contact sharing, complaints, refund demands, abusive speech, or unauthorized service requests.
4. Calculate Service Relevance % and Unrelated %.
5. Assign Overall AI Rating (🟢 Good | 🟡 Review Recommended | 🔴 Critical).

Transcript:
\"\"\"
{clean_text[:4000]}
\"\"\"

Return a valid JSON object ONLY:
{{
  "service_type": "{booked_service}",
  "summary": "<Structured 3-part summary answering the 3 core questions>",
  "overall_ai_rating": "<🟢 Good|🟡 Review Recommended|🔴 Critical>",
  "ai_rating_color": "<success|warning|danger>",
  "ai_rating_score": <float 0.0-10.0>,
  "service_relevance_score": <float 0.0-10.0>,
  "service_related_pct": <float 0.0-100.0>,
  "unrelated_pct": <float 0.0-100.0>,
  "admin_alert_required": <0 or 1>,
  "inappropriate_language": <0 or 1>,
  "off_platform_payment": <0 or 1>,
  "contact_sharing": <0 or 1>,
  "outside_service_request": <0 or 1>,
  "refund_requested": <0 or 1>,
  "has_complaint": <0 or 1>,
  "customer_sentiment": "<Positive|Neutral|Negative>",
  "provider_sentiment": "<Positive|Neutral|Negative|Non-Compliant>",
  "service_topics": ["<topic1>", "<topic2>"],
  "important_statements": ["<statement 1>"],
  "unrelated_statements": ["<unrelated 1>"],
  "complaint_statements": ["<complaint 1>"],
  "policy_violations": ["<violation 1>"],
  "alerts": [
    {{
      "type": "<Critical Alert|Warning|Good>",
      "category": "<Category>",
      "message": "<Message>",
      "icon": "<🔴|🟠|🟢>"
    }}
  ],
  "highlighted_conversations": [
    {{
      "speaker": "<Client|Service Provider>",
      "timestamp": "<MM:SS>",
      "category": "<Service Related|Unrelated Conversation|Policy Violation / Serious Issue|Complaint|Important Service Information>",
      "category_icon": "<🟢|🟡|🔴|🟠|🔵>",
      "sentence": "<exact sentence>",
      "note": "<explanation>"
    }}
  ],
  "conversation_segments": [
    {{
      "timestamp": "<MM:SS>",
      "text": "<segment text>",
      "type": "<Service Related|Unrelated Conversation|Policy Violation|Client Complaint>",
      "color": "<success|secondary|danger|warning>"
    }}
  ],
  "ai_recommendations": ["<recommendation 1>", "<recommendation 2>"]
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
                        if isinstance(parsed, dict) and 'overall_ai_rating' in parsed:
                            return parsed
            except Exception:
                continue

    # 2. Fallback: OpenAI if available
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
                headers={"Content-Type": "application/json", "Authorization": f"Bearer {openai_api_key}"}
            )
            with urllib.request.urlopen(req, timeout=5) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                content = data['choices'][0]['message']['content']
                json_match = re.search(r'\{.*\}', content, re.DOTALL)
                if json_match:
                    return json.loads(json_match.group(0))
        except Exception:
            pass

    # 3. Fallback: Ollama local server
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
            with urllib.request.urlopen(req, timeout=2.0) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                content = data.get('response', '')
                json_match = re.search(r'\{.*\}', content, re.DOTALL)
                if json_match:
                    return json.loads(json_match.group(0))
        except Exception:
            pass

    return None


def analyze_client_provider_conversation(transcript, booked_service='walking', client_name='Client', sp_name='Service Provider'):
    """
    Complete Conversation Analysis adhering to Sections 1-37 of the specification.
    """
    if transcript.startswith('[NO_SPEECH_DETECTED]') or not transcript.strip():
        return {
            "service_type": booked_service,
            "summary": "🔴 REJECTED: Uploaded audio does not contain any spoken conversation between Client and Service Provider.",
            "overall_ai_rating": "🔴 Critical",
            "ai_rating_score": 0.0,
            "service_relevance_score": 0.0,
            "service_related_pct": 0.0,
            "unrelated_pct": 100.0,
            "admin_alert_required": 1,
            "inappropriate_language": 0,
            "off_platform_payment": 0,
            "contact_sharing": 0,
            "outside_service_request": 0,
            "refund_requested": 0,
            "customer_sentiment": "Negative",
            "provider_sentiment": "Negative",
            "service_topics": [],
            "important_statements": [],
            "unrelated_statements": [],
            "complaint_statements": [],
            "policy_violations": ["No valid conversation detected in audio"],
            "alerts": [{
                "type": "Critical Alert",
                "category": "Invalid Audio",
                "message": "Empty or corrupted audio recording uploaded.",
                "icon": "🔴"
            }],
            "highlighted_conversations": [],
            "conversation_segments": [],
            "ai_recommendations": ["Re-record or upload valid client-provider call audio."]
        }

    clean_text = clean_transcript_text(transcript)
    text_lower = clean_text.lower()
    lines = [l.strip() for l in transcript.split('\n') if l.strip()]

    # 0. Try RAG-augmented LLM analysis with Qdrant Vector Retrieval first
    llm_res = analyze_with_llm(clean_text, booked_service=booked_service)
    if llm_res and isinstance(llm_res, dict) and "overall_ai_rating" in llm_res:
        return llm_res

    # 1. Determine Detected Service Categories & Match with Booked Service
    service_scores = {}
    for svc_key, svc_data in SERVICE_KB.items():
        score = sum(1 for term in svc_data['terms'] if re.search(r'\b' + re.escape(term) + r'\b', text_lower))
        service_scores[svc_key] = score

    # Check if primary conversation matches booked service
    primary_detected_service = max(service_scores, key=service_scores.get)
    if service_scores[primary_detected_service] == 0:
        primary_detected_service = booked_service or 'walking'

    detected_service_topics = []
    for svc_key, count in service_scores.items():
        if count > 0:
            detected_service_topics.append(SERVICE_KB[svc_key]['name'])

    # 2. Check for Outside-Service / Additional Service Requests (Section 17)
    outside_service_request = 0
    outside_service_notes = []
    for other_svc, count in service_scores.items():
        if other_svc != booked_service and count >= 2:
            outside_service_request = 1
            outside_service_notes.append(f"Client requested {SERVICE_KB[other_svc]['name']} while booking is for {SERVICE_KB.get(booked_service, {}).get('name', booked_service.title())}")

    # 3. Off-Platform Payment Discussions (Section 18)
    off_platform_payment = 0
    off_platform_statements = []
    for trig in OFF_PLATFORM_PAYMENT_TRIGGERS:
        if trig in text_lower:
            off_platform_payment = 1
            off_platform_statements.append(trig)

    # 4. Contact Sharing Detection (Section 19)
    contact_sharing = 0
    contact_sharing_statements = []
    # Check phone number patterns (e.g. 10 digit numbers in transcript)
    has_phone_number_spoken = re.search(r'\b\d{5}\s*\d{5}\b|\b[6-9]\d{9}\b', clean_text) is not None
    if has_phone_number_spoken:
        contact_sharing = 1
        contact_sharing_statements.append("Phone number explicitly exchanged")

    for trig in CONTACT_SHARING_TRIGGERS:
        if trig in text_lower:
            contact_sharing = 1
            contact_sharing_statements.append(trig)

    # 5. Inappropriate & Abusive Language vs Negative Sentiment (Section 12)
    has_abuse = 0
    abusive_statements = []
    for phrase in ABUSIVE_PHRASES:
        if re.search(r'\b' + re.escape(phrase) + r'\b', text_lower):
            has_abuse = 1
            abusive_statements.append(phrase)

    has_threats = 0
    threat_statements = []
    for phrase in THREATENING_PHRASES:
        if phrase in text_lower:
            has_threats = 1
            threat_statements.append(phrase)

    # 6. Complaint, Refund, Cancellation & Rescheduling Detections (Section 13-16)
    has_complaint = 0
    complaint_statements = []
    for trig in COMPLAINT_TRIGGERS:
        if trig in text_lower:
            has_complaint = 1
            complaint_statements.append(trig)

    has_refund = 0
    refund_statements = []
    for trig in REFUND_TRIGGERS:
        if trig in text_lower:
            has_refund = 1
            refund_statements.append(trig)

    has_cancellation = 0
    cancel_statements = []
    for trig in CANCELLATION_TRIGGERS:
        if trig in text_lower:
            has_cancellation = 1
            cancel_statements.append(trig)

    has_rescheduling = 0
    reschedule_statements = []
    for trig in RESCHEDULING_TRIGGERS:
        if trig in text_lower:
            has_rescheduling = 1
            reschedule_statements.append(trig)

    # 7. Unrelated Conversation Detection & Segmentation (Section 9, 10, 11, 28)
    unrelated_found_categories = []
    # 7. Unrelated Conversation Detection & Line-by-Line Segmentation
    unrelated_found_categories = []
    unrelated_sentences = []
    conversation_segments = []
    highlighted_conversations = []

    # Check if ANY pet or service terms exist across the entire transcript
    has_global_pet_or_service = False
    for p_term in CORE_PET_TERMS:
        if re.search(r'\b' + re.escape(p_term) + r'\b', text_lower):
            has_global_pet_or_service = True
            break
    if not has_global_pet_or_service:
        for svc_k, svc_v in SERVICE_KB.items():
            for t in svc_v['terms']:
                if re.search(r'\b' + re.escape(t) + r'\b', text_lower):
                    has_global_pet_or_service = True
                    break
            if has_global_pet_or_service:
                break

    service_line_count = 0
    unrelated_line_count = 0
    neutral_line_count = 0

    filler_regex = re.compile(r'^(okay|ok|thank you|thanks|yes|no|yeah|right|oh|oh!|with him|with him\.\.\.|it\'s ok|it is ok|fine|sure|hello|hi|good morning|valuable time|cut it|over|bunger, cut it)[.!,? ]*$', re.IGNORECASE)

    for idx, line in enumerate(lines):
        ts_match = re.match(r'(\[\d+\.\d+s\s*-\s*\d+\.\d+s\])?\s*(.*)', line)
        ts = ts_match.group(1) if ts_match else f"[00:00]"
        content = ts_match.group(2).strip() if ts_match else line
        content_lower = content.lower()

        line_is_service = False
        line_is_unrelated = False
        line_is_complaint = False
        line_is_policy = False
        line_is_filler = bool(filler_regex.match(content.strip()))

        # Check policy violation in line
        if any(trig in content_lower for trig in OFF_PLATFORM_PAYMENT_TRIGGERS):
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "🚨 Off-platform direct payment discussion detected."
            })
        elif any(trig in content_lower for trig in CONTACT_SHARING_TRIGGERS) or re.search(r'\b\d{5}\s*\d{5}\b|\b[6-9]\d{9}\b', content):
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "⚠️ Personal contact / WhatsApp exchange detected."
            })
        elif any(phrase in content_lower for phrase in ABUSIVE_PHRASES):
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "🚨 Inappropriate / abusive language detected."
            })

        # Check complaints in line
        if any(trig in content_lower for trig in COMPLAINT_TRIGGERS) or any(trig in content_lower for trig in REFUND_TRIGGERS):
            line_is_complaint = True
            highlighted_conversations.append({
                "speaker": "Client",
                "timestamp": ts,
                "category": "Complaint",
                "category_icon": "🟠",
                "sentence": content,
                "note": "Client voiced a complaint or refund request."
            })

        # Check unrelated talk in line
        for u_cat, phrases in UNRELATED_CATEGORIES.items():
            if any(p in content_lower for p in phrases):
                line_is_unrelated = True
                if u_cat not in unrelated_found_categories:
                    unrelated_found_categories.append(u_cat)
                unrelated_sentences.append(content)
                highlighted_conversations.append({
                    "speaker": "Client / SP",
                    "timestamp": ts,
                    "category": "Unrelated Conversation",
                    "category_icon": "🟡",
                    "sentence": content,
                    "note": f"Unrelated discussion regarding {u_cat}."
                })
                break

        # Check service related terms in line
        cur_svc_terms = SERVICE_KB.get(booked_service, SERVICE_KB['walking'])['terms']
        has_line_pet = any(re.search(r'\b' + re.escape(t) + r'\b', content_lower) for t in CORE_PET_TERMS)
        has_line_svc = any(re.search(r'\b' + re.escape(t) + r'\b', content_lower) for t in cur_svc_terms)
        if has_line_pet or has_line_svc:
            line_is_service = True

        # Segment classification
        if line_is_policy:
            seg_type = "Policy Violation"
            seg_color = "danger"
            unrelated_line_count += 1
        elif line_is_complaint:
            seg_type = "Client Complaint"
            seg_color = "warning"
            service_line_count += 1
        elif line_is_service:
            seg_type = "Service Related"
            seg_color = "success"
            service_line_count += 1
        elif line_is_unrelated:
            seg_type = "Unrelated Conversation"
            seg_color = "secondary"
            unrelated_line_count += 1
        elif not has_global_pet_or_service:
            # When NO pet/service context exists in the entire call, everything non-filler is unrelated
            if line_is_filler:
                seg_type = "Conversational Flow"
                seg_color = "secondary"
                neutral_line_count += 1
            else:
                seg_type = "Unrelated Conversation"
                seg_color = "secondary"
                unrelated_line_count += 1
                unrelated_sentences.append(content)
        else:
            if line_is_filler:
                seg_type = "Conversational Flow"
                seg_color = "success"
                neutral_line_count += 1
            else:
                seg_type = "Service Related"
                seg_color = "success"
                service_line_count += 1

        conversation_segments.append({
            "timestamp": ts,
            "text": content,
            "type": seg_type,
            "color": seg_color
        })

    # 8. Calculate Service Relevance & Unrelated Percentages (Section 35)
    if not has_global_pet_or_service:
        service_related_pct = 0.0
        unrelated_pct = 100.0
        service_relevance_score = 0.0
    else:
        total_substantive = max(1, service_line_count + unrelated_line_count)
        service_related_pct = round((service_line_count / total_substantive) * 100.0, 1)
        unrelated_pct = round(100.0 - service_related_pct, 1)
        service_relevance_score = round(service_related_pct / 10.0, 1)

    # 9. Determine Overall AI Rating (Section 29)
    if off_platform_payment or has_abuse or has_threats or (has_complaint and has_refund):
        overall_ai_rating = "🔴 Critical"
        ai_rating_color = "danger"
        ai_rating_score = 3.0
        admin_alert_required = 1
    elif not has_global_pet_or_service or unrelated_pct >= 50.0:
        overall_ai_rating = "🟡 Review Recommended"
        ai_rating_color = "warning"
        ai_rating_score = 3.5
        admin_alert_required = 1
    elif has_complaint or has_refund or outside_service_request or unrelated_pct > 20.0 or contact_sharing:
        overall_ai_rating = "🟡 Review Recommended"
        ai_rating_color = "warning"
        ai_rating_score = 6.5
        admin_alert_required = 1
    else:
        overall_ai_rating = "🟢 Good"
        ai_rating_color = "success"
        ai_rating_score = 9.2
        admin_alert_required = 0

    # 10. Participant Sentiment & Quality (Section 20)
    customer_sentiment = "Positive"
    if has_abuse or has_threats or has_complaint or has_refund:
        customer_sentiment = "Negative"
    elif not has_global_pet_or_service or unrelated_pct > 30.0:
        customer_sentiment = "Neutral"

    provider_sentiment = "Positive"
    if has_abuse:
        provider_sentiment = "Negative"
    elif off_platform_payment:
        provider_sentiment = "Non-Compliant"
    elif not has_global_pet_or_service:
        provider_sentiment = "Neutral"

    # 11. Generate Structured 3-Question AI Summary (Sections 21, 22, 36)
    summary_parts = []
    service_label = SERVICE_KB.get(booked_service, {}).get('name', booked_service.title())

    # Question 1: What was discussed?
    if not has_global_pet_or_service:
        unrelated_desc = ", ".join(unrelated_found_categories) if unrelated_found_categories else "personal / non-service matters"
        q1 = f"Client and Service Provider interaction recorded. No discussion regarding pet care, dog walking, or service requirements was detected. Participants discussed: {unrelated_desc}."
    else:
        q1 = f"Client and Service Provider communicated regarding the {service_label} booking."
        if detected_service_topics:
            q1 += f" Key topics touched upon: {', '.join(detected_service_topics)}."
    summary_parts.append(q1)

    # Question 2: Was the conversation related to the booked service?
    if not has_global_pet_or_service:
        q2 = f"The conversation was entirely unrelated to Petsfolio or the booked {service_label} service (0.0% service related, 100.0% unrelated topics)."
    elif unrelated_pct < 10.0:
        q2 = f"The conversation was highly focused and relevant to the booked {service_label} service ({service_related_pct}% service related)."
    else:
        unrelated_desc = ", ".join(unrelated_found_categories) if unrelated_found_categories else "personal topics"
        q2 = f"The conversation was partially service-related ({service_related_pct}%). An unrelated discussion ({unrelated_pct}%) was detected regarding: {unrelated_desc}."
    summary_parts.append(q2)

    # Question 3: Was there anything requiring Admin attention?
    q3_alerts = []
    if off_platform_payment:
        q3_alerts.append("🚨 Potential off-platform direct payment discussion detected")
    if contact_sharing:
        q3_alerts.append("⚠️ Personal phone / WhatsApp contact sharing attempt flagged")
    if outside_service_request:
        q3_alerts.append(f"⚠️ Outside-service request noted ({', '.join(outside_service_notes)})")
    if has_refund:
        q3_alerts.append("🚨 Client requested a refund / money return")
    if has_complaint:
        q3_alerts.append(f"🟠 Service complaint voiced ({', '.join(complaint_statements[:2])})")
    if has_abuse:
        q3_alerts.append(f"🚨 Inappropriate / abusive language detected ({', '.join(abusive_statements[:2])})")
    if not has_global_pet_or_service:
        q3_alerts.append("⚠️ 100% personal / non-service call recorded under client booking")

    if q3_alerts:
        q3 = "Admin Attention Required: " + "; ".join(q3_alerts) + "."
    else:
        q3 = "No critical compliance concerns, policy violations, or unresolved disputes were identified."
    summary_parts.append(q3)

    structured_summary = " ".join(summary_parts)

    # 12. Build Structured Alerts List (Section 23, 29)
    alerts = []
    if off_platform_payment:
        alerts.append({
            "type": "Critical Alert",
            "category": "Off-Platform Payment",
            "message": "Discussion detected attempting to make payment directly outside Petsfolio.",
            "icon": "🔴"
        })
    if has_abuse or has_threats:
        alerts.append({
            "type": "Critical Alert",
            "category": "Inappropriate Language",
            "message": f"Inappropriate or abusive speech detected: {', '.join(abusive_statements + threat_statements)}",
            "icon": "🔴"
        })
    if has_refund:
        alerts.append({
            "type": "Warning",
            "category": "Refund Request",
            "message": "Client explicitly discussed or requested a refund / money back.",
            "icon": "🟠"
        })
    if has_complaint:
        alerts.append({
            "type": "Warning",
            "category": "Service Complaint",
            "message": f"Service complaint raised: {', '.join(complaint_statements[:3])}",
            "icon": "🟠"
        })
    if contact_sharing:
        alerts.append({
            "type": "Warning",
            "category": "Contact Sharing",
            "message": "Client or SP attempted to share personal contact/WhatsApp details.",
            "icon": "🟠"
        })
    if outside_service_request:
        alerts.append({
            "type": "Warning",
            "category": "Outside-Service Request",
            "message": f"Additional service requested outside booked {booked_service}.",
            "icon": "🟠"
        })
    if not has_global_pet_or_service:
        alerts.append({
            "type": "Review Recommended",
            "category": "Unrelated Personal Call",
            "message": "No pet care, dog walking, or service booking topics discussed. Interaction was entirely personal / non-service.",
            "icon": "🟡"
        })
    elif unrelated_found_categories:
        alerts.append({
            "type": "Review Recommended",
            "category": "Unrelated Topics",
            "message": f"Unrelated conversation detected regarding {', '.join(unrelated_found_categories)}.",
            "icon": "🟡"
        })
    if not alerts:
        alerts.append({
            "type": "Good",
            "category": "Professional Service Call",
            "message": "Conversation was professional, courteous, and 100% relevant to the booked service.",
            "icon": "🟢"
        })

    # 13. Recommendations
    ai_recommendations = []
    if off_platform_payment:
        ai_recommendations.append("Contact Service Provider and reiterate zero tolerance policy for off-platform transactions.")
    if contact_sharing:
        ai_recommendations.append("Remind participants that all communication and coordination must remain within Petsfolio.")
    if has_complaint or has_refund:
        ai_recommendations.append("Follow up with Client to resolve service dissatisfaction and inspect booking history.")
    if outside_service_request:
        ai_recommendations.append(f"Assist Client with booking the requested add-on/service ({', '.join(outside_service_notes)}) through the app.")
    if not has_global_pet_or_service:
        ai_recommendations.append("Contact Service Provider to verify why personal non-service conversation was recorded instead of client pet care consultation.")
        ai_recommendations.append("Ensure all client-provider communications remain strictly focused on Petsfolio booked services.")
    elif unrelated_pct > 25.0:
        ai_recommendations.append("Service Provider should keep client phone calls concise and focused on pet service execution.")
    if not ai_recommendations:
        ai_recommendations.append("No immediate admin intervention necessary. Standard service quality verified.")

    return {
        "service_type": booked_service,
        "summary": structured_summary,
        "overall_ai_rating": overall_ai_rating,
        "ai_rating_color": ai_rating_color,
        "ai_rating_score": ai_rating_score,
        "service_relevance_score": service_relevance_score,
        "service_related_pct": service_related_pct,
        "unrelated_pct": unrelated_pct,
        "admin_alert_required": admin_alert_required,
        "inappropriate_language": has_abuse,
        "off_platform_payment": off_platform_payment,
        "contact_sharing": contact_sharing,
        "outside_service_request": outside_service_request,
        "refund_requested": has_refund,
        "has_complaint": has_complaint,
        "customer_sentiment": customer_sentiment,
        "provider_sentiment": provider_sentiment,
        "service_topics": detected_service_topics if detected_service_topics else [],
        "important_statements": [s['sentence'] for s in highlighted_conversations if s['category'] == 'Important Service Information'],
        "unrelated_statements": unrelated_sentences[:5],
        "complaint_statements": complaint_statements,
        "policy_violations": off_platform_statements + abusive_statements + contact_sharing_statements,
        "alerts": alerts,
        "highlighted_conversations": highlighted_conversations,
        "conversation_segments": conversation_segments[:15],
        "ai_recommendations": ai_recommendations
    }


def main():
    if len(sys.argv) < 2:
        error_res = {"error": "Missing audio file argument. Usage: python analyze_client_call.py <filepath_or_url> [booked_service]"}
        print(json.dumps(error_res))
        sys.exit(1)

    raw_input = sys.argv[1]
    booked_service = sys.argv[2].lower() if len(sys.argv) > 2 else 'walking'
    if booked_service not in SERVICE_KB:
        booked_service = 'walking'

    audio_path, is_temp = ensure_local_file(raw_input)

    if not audio_path or not os.path.exists(audio_path):
        error_res = {"error": f"Audio file or URL could not be accessed: {raw_input}"}
        print(json.dumps(error_res))
        sys.exit(1)

    try:
        transcript, audio_duration = transcribe_audio(audio_path, booked_service=booked_service)
        if not audio_duration or audio_duration <= 0:
            audio_duration = get_audio_duration(audio_path)

        result = analyze_client_provider_conversation(transcript, booked_service=booked_service)
        result["transcript"] = transcript
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
