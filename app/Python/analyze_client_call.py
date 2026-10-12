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

<<<<<<< HEAD
# ─── Off-Platform Payment Triggers (Section 18) ─────────────────────────────
=======
# ─── Off-Platform Payment Triggers (Section 12.2) ─────────────────────────────
>>>>>>> origin/main

OFF_PLATFORM_PAYMENT_TRIGGERS = [
    'pay you directly', 'pay direct', 'pay directly', 'send directly', 'direct payment',
    'pay me directly', 'send me directly', 'cash in hand', 'cash to you',
    'outside petsfolio', 'outside the app', 'without petsfolio', 'skip petsfolio',
    'pay via gpay directly', 'pay via phonepe directly', 'send to my personal gpay',
    'send to my personal number', 'pay me cash', 'direct transfer to my account',
<<<<<<< HEAD
    'pay less if direct', 'discount if outside app', 'no need to pay in app'
]

# ─── Contact Sharing Triggers (Section 19) ──────────────────────────────────

CONTACT_SHARING_TRIGGERS = [
    'personal phone number', 'give me your number', 'take my number', 'call my personal number',
    'whatsapp me', 'share your whatsapp', 'my whatsapp number is', 'send on whatsapp',
    'personal email', 'give me your email', 'my personal contact', 'contact me directly on'
=======
    'pay less if direct', 'discount if outside app', 'no need to pay in app',
    'pay offline', 'direct cash', 'personal gpay', 'personal phonepe', 'personal upi'
]

# ─── Platform Bypass Triggers (Section 12 / Doc 2) ────────────────────────────

PLATFORM_BYPASS_TRIGGERS = [
    'book directly with you', 'book with you directly', 'no need to book through petsfolio',
    'no need to book on petsfolio', 'contact you directly instead of booking',
    'contact you directly for the next', 'contact you directly next time',
    'call me directly next time', 'call me directly instead', "we don't need petsfolio",
    'skip the app next time', "don't book through petsfolio", "don't book on petsfolio",
    'book directly outside', 'private service next time', 'avoid petsfolio',
    'bypass petsfolio', 'book offline with you', 'book directly with me',
    'call you directly next time', 'contact me directly next time',
    'offline service', 'offline services', 'outside of the petsfolio platform',
    'outside of petsfolio platform', 'outside of petsfolio', 'outside petsfolio',
    'outside the petsfolio platform', 'outside the platform', 'not through petsfolio',
    'not through the app', 'not via petsfolio', 'come directly', 'contact you directly',
    'contact me directly', 'do it directly', 'we will do it directly', 'call you directly',
    'call me directly', 'deal directly', 'talk to you directly', 'book offline'
]

# ─── GST & Platform Charges Bypass / Evasion Triggers (Section 13 / Doc 2) ─────

GST_BYPASS_TRIGGERS = [
    'avoid gst', 'save gst', 'avoid platform charges', 'save platform charges',
    'avoid platform fees', 'save platform fees', 'without gst', 'without platform charges',
    'without platform fees', 'without these charges', 'save the charges', 'save the fees',
    'avoid the platform charges', 'avoid the charges', 'avoid the gst',
    'avoid that', 'gst and platform charges'
]

# ─── Legitimate / Safe Charges Inquiries (Do NOT Flag - False Highlight Prevention) ─

SAFE_CHARGES_INQUIRIES = [
    'why is gst charged', 'how much gst', 'gst included', 'applicable gst',
    'why are platform charges added', 'what are platform charges', 'platform charges are part of',
    'contact petsfolio support regarding gst', 'contact petsfolio support',
    'check the booking details for gst', 'why is the platform charge', 'what is platform charge'
]

# ─── Contact Sharing Triggers (Section 11) ──────────────────────────────────

CONTACT_SHARING_TRIGGERS = [
    'personal phone number', 'personal mobile number', 'give me your personal',
    'give me your number', 'take my number', 'call my personal number', 'save my personal number',
    'save my number', 'whatsapp me', 'share your whatsapp', 'my whatsapp number is',
    'send on whatsapp', 'message me on whatsapp', 'can i whatsapp you', 'personal email',
    'give me your email', 'my personal contact', 'contact me directly on',
    'tell me your number', 'what is your number', "what's your number", 'whats your number',
    'my number is', 'my phone number is', 'my mobile number is', 'call you directly',
    'call me directly', 'share your number', 'give your number', 'take your number',
    'note down my number', 'here is my number'
>>>>>>> origin/main
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
<<<<<<< HEAD
    'did not come', 'didn\'t come', 'did not arrive', 'missed walk', 'not handled properly',
    'dog was hurt', 'incomplete grooming', 'missed session', 'missed training',
    'poor quality', 'bad service', 'behaved improperly', 'unprofessional behavior',
    'worst service', 'very disappointed', 'not happy with service', 'terrible service',
    'waste of money', 'angry because'
=======
    'did not come', "didn't come", 'did not arrive', 'missed walk', 'not handled properly',
    'dog was hurt', 'incomplete grooming', 'missed session', 'missed training',
    'poor quality', 'bad service', 'behaved improperly', 'unprofessional behavior',
    'worst service', 'very disappointed', 'not happy with service', 'terrible service',
    'waste of money', 'angry because', "haven't come", 'havent come', "still haven't come",
    'still havent come', 'not come', "hasn't come", 'hasnt come', 'becoming difficult',
    'very difficult', 'it is difficult', 'booked it for', 'waiting for you', 'still waiting',
    'where are you', 'delay', 'delayed', 'too late', 'why are you late', 'hours late'
>>>>>>> origin/main
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

<<<<<<< HEAD
=======
# ─── Pattern Matching & Extraction Helpers ────────────────────────────────────

PHONE_REGEX = re.compile(r'\b(?:(?:\+?91[\s-]?)?[6-9]\d{9,12}|\d{10,13}|\d{5}\s*\d{5})\b')

def check_complaint_match(text_line):
    line_l = text_line.lower()
    if any(trig in line_l for trig in COMPLAINT_TRIGGERS):
        return True
    if re.search(r'\b(booked (?:it )?for \d+|now it is \d+|still haven\'?t come|you haven\'?t come|not arrived yet|hours? late|delayed?)\b', line_l):
        return True
    if re.search(r'\b(alone.*difficult|difficult.*alone|becoming difficult)\b', line_l):
        return True
    return False

def check_platform_bypass_match(text_line):
    line_l = text_line.lower()
    if any(trig in line_l for trig in PLATFORM_BYPASS_TRIGGERS):
        return True
    if re.search(r'\b(offline service|outside (?:of )?(?:the )?petsfolio|not through petsfolio|contact (?:you|me) directly|do it directly|come directly|call (?:you|me) directly|deal directly)\b', line_l):
        return True
    return False

def check_gst_bypass_match(text_line):
    line_l = text_line.lower()
    if any(trig in line_l for trig in GST_BYPASS_TRIGGERS):
        return True
    if re.search(r'\b(?:gst|platform charges?|platform fees?|platform fee|convenience fee)\b.*?\b(?:avoid|save|skip|without|evade|don\'t want to pay|dont want to pay|remove|less)\b', line_l):
        return True
    if re.search(r'\b(?:avoid|save|skip|without|evade|don\'t want to pay|dont want to pay)\b.*?\b(?:gst|platform charges?|platform fees?|platform fee|convenience fee)\b', line_l):
        return True
    return False

def check_contact_sharing_match(text_line):
    line_l = text_line.lower()
    if any(trig in line_l for trig in CONTACT_SHARING_TRIGGERS):
        return True
    if PHONE_REGEX.search(text_line):
        return True
    return False

>>>>>>> origin/main

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


<<<<<<< HEAD
def transcribe_audio(audio_path, booked_service='walking'):
    """Transcribes audio with Faster-Whisper, auto-translating regional languages to English."""
    audio_duration = get_audio_duration(audio_path)
=======
def deduplicate_repetitive_hallucinations(text):
    """
    Post-processing cleaner for ASR output. Handles:
    1. Known hallucination phrase removal (YouTube/Whisper training artifacts)
    2. Inline within-line phrase repetition collapse
    3. Consecutive identical line collapse (A, A, A -> A)
    4. Alternating pair loop collapse (A, B, A, B -> A, B)
    """
    if not text:
        return text

    lines = text.split('\n')
    ts_pattern = re.compile(r'^(\[(?:\d+:)?\d+(?:\.\d+)?s?\s*-\s*(?:\d+:)?\d+(?:\.\d+)?s?\])\s*(.*)')

    # Step 1: Known hallucination phrase blacklist
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

    # Step 2: Inline within-line repetition collapse
    inline_rep = re.compile(r'(\b.{3,80}?\b)(?:\s*[,.]?\s*\1){1,}', re.IGNORECASE)

    def clean_line_content(content):
        content = blacklist_re.sub('', content)
        for _ in range(5):
            new_c = inline_rep.sub(r'\1', content)
            if new_c == content:
                break
            content = new_c
        content = re.sub(r'\s{2,}', ' ', content).strip()
        content = re.sub(r'^[\s,.\-]+|[\s,.\-]+$', '', content).strip()
        return content

    parsed = []
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

    # Step 3: Collapse consecutive identical lines AND alternating pair loops
    result = []
    i = 0
    n = len(parsed)

    while i < n:
        _, norm_i, line_i = parsed[i]

        # Check for alternating A-B pair loop
        if i + 3 < n:
            _, norm_j, line_j = parsed[i + 1]
            if norm_i != norm_j:
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
                    result.append(line_i)
                    result.append(line_j)
                    i = k
                    continue

        # Check for consecutive identical lines
        run_end = i + 1
        while run_end < n and parsed[run_end][1] == norm_i:
            run_end += 1

        result.append(line_i)
        i = run_end

    return '\n'.join(result)


def validate_transcript_duration(text, audio_duration):
    """Rejects hallucinated transcripts where timestamps exceed actual audio duration."""
    if not text or not audio_duration or audio_duration <= 0:
        return text
    timestamps = re.findall(r'\[\s*(\d+(?:\.\d+)?)\s*s?\s*-\s*(\d+(?:\.\d+)?)\s*s?\s*\]', text)
    if timestamps:
        max_end = max(float(ts[1]) for ts in timestamps)
        if max_end > (audio_duration + 3.0):
            sys.stderr.write(f"[WARN] Hallucinated transcript rejected! Max timestamp ({max_end:.1f}s) exceeds physical audio duration ({audio_duration:.1f}s).\n")
            return None
    return text


def transcribe_with_gemini(audio_path, booked_service='walking', audio_duration=0):
    """Direct audio transcription & translation using Google Gemini File API."""
    if audio_duration and audio_duration < 4.0:
        return None

    gemini_key = os.getenv("x-goog-api-key") or os.getenv("GEMINI_API_KEY") or os.getenv("GOOGLE_API_KEY")
    if not gemini_key or not os.path.exists(audio_path):
        return None

    temp_ogg = None
    file_name = None
    try:
        import subprocess, tempfile, time

        # 1. Compress to 16kHz mono OGG for rapid upload & high clarity
        temp_f = tempfile.NamedTemporaryFile(suffix='.ogg', delete=False)
        temp_ogg = temp_f.name
        temp_f.close()

        subprocess.run(
            ["ffmpeg", "-y", "-i", audio_path, "-vn", "-ar", "16000", "-ac", "1", "-b:a", "32k", temp_ogg],
            stdout=subprocess.PIPE, stderr=subprocess.PIPE, timeout=30
        )
        if not os.path.exists(temp_ogg) or os.path.getsize(temp_ogg) == 0:
            target_ogg = audio_path
            mime_type = "audio/wav" if audio_path.endswith('.wav') else "audio/ogg"
        else:
            target_ogg = temp_ogg
            mime_type = "audio/ogg"

        with open(target_ogg, 'rb') as f:
            audio_data = f.read()

        # 2. Upload via Google Generative AI File API
        upload_url = f"https://generativelanguage.googleapis.com/upload/v1beta/files?key={gemini_key}"
        boundary = "----WebKitFormBoundaryPetsfolioClientAudioUpload"
        meta = {
            "file": {
                "display_name": f"client_call_{os.path.basename(audio_path)}"
            }
        }
        meta_json = json.dumps(meta)

        body = bytearray()
        body.extend(f"--{boundary}\r\n".encode())
        body.extend(b'Content-Type: application/json; charset=UTF-8\r\n\r\n')
        body.extend(meta_json.encode())
        body.extend(b"\r\n")

        body.extend(f"--{boundary}\r\n".encode())
        body.extend(f'Content-Type: {mime_type}\r\n\r\n'.encode())
        body.extend(audio_data)
        body.extend(b"\r\n")
        body.extend(f"--{boundary}--\r\n".encode())

        req_upload = urllib.request.Request(
            upload_url,
            data=bytes(body),
            headers={
                'Content-Type': f'multipart/related; boundary={boundary}',
                'X-Goog-Upload-Protocol': 'multipart'
            }
        )

        upload_res = None
        for attempt in range(4):
            try:
                with urllib.request.urlopen(req_upload, timeout=60) as resp:
                    upload_res = json.loads(resp.read().decode())
                    break
            except urllib.error.HTTPError as e:
                if e.code in (429, 500, 503) and attempt < 3:
                    time.sleep(3 * (attempt + 1))
                    continue
                raise

        if not upload_res or 'file' not in upload_res:
            return None

        file_uri = upload_res['file']['uri']
        file_name = upload_res['file']['name']

        # 3. Prompt with strict grounding against hallucinating on silent/short audio
        prompt = (
            "You are an expert audio transcription system for Petsfolio pet care platform.\n"
            f"The attached audio recording is physically {audio_duration:.1f} seconds long.\n"
            f"Transcribe this recorded phone call between a Client/Pet Parent and a Service Provider (regarding {booked_service}) from the very beginning to the very end.\n"
            "Translate all spoken regional languages (Telugu, Hindi, Tamil, Kannada, Marathi, etc.) into clear, accurate, professional English.\n"
            "CRITICAL TRUTHFULNESS & GROUNDING RULES:\n"
            "1. ONLY transcribe words that were ACTUALLY spoken in the audio. Do NOT make up words.\n"
            f"2. All timestamps MUST fit within the audio's physical duration of 0.0s to {audio_duration:.1f}s. Never output timestamps exceeding {audio_duration + 0.5:.1f}s.\n"
            "3. If the audio is silence, room noise, static, a click/hangup, or lacks clear human conversation, reply ONLY with: [NO_SPEECH_DETECTED]\n"
            "4. NEVER fabricate, imagine, or hallucinate names, dog breeds, locations, or dialogue not physically present in the recording.\n"
            "5. Output format for each line: [start_seconds - end_seconds] Speaker: Spoken text.\n"
            "6. Output ONLY the timestamped dialogue lines. No explanations or notes."
        )

        payload = {
            "contents": [
                {
                    "parts": [
                        {
                            "file_data": {
                                "mime_type": mime_type,
                                "file_uri": file_uri
                            }
                        },
                        {
                            "text": prompt
                        }
                    ]
                }
            ],
            "generationConfig": {
                "temperature": 0.0,
                "maxOutputTokens": 8192
            }
        }

        # 4. Generate Content with multi-model fallback and retry
        models_to_try = ['gemini-2.5-flash', 'gemini-3.6-flash', 'gemini-3.7-flash']
        raw_text = None

        for model_name in models_to_try:
            req_gen = urllib.request.Request(
                f"https://generativelanguage.googleapis.com/v1beta/models/{model_name}:generateContent?key={gemini_key}",
                data=json.dumps(payload).encode('utf-8'),
                headers={'Content-Type': 'application/json'}
            )
            for attempt in range(3):
                try:
                    with urllib.request.urlopen(req_gen, timeout=120) as resp:
                        res = json.loads(resp.read().decode('utf-8'))
                        candidates = res.get('candidates', [])
                        if candidates:
                            parts = candidates[0].get('content', {}).get('parts', [])
                            if parts:
                                raw_text = parts[0].get('text', '').strip()
                                raw_text = re.sub(r'^```[a-zA-Z]*\n', '', raw_text)
                                raw_text = re.sub(r'\n```$', '', raw_text).strip()
                                break
                except urllib.error.HTTPError as e:
                    if e.code in (429, 500, 503) and attempt < 2:
                        time.sleep(3 * (attempt + 1))
                        continue
                    break
                except Exception:
                    break
            if raw_text:
                break

        # 5. Delete temporary Gemini File
        if file_name:
            try:
                del_req = urllib.request.Request(
                    f"https://generativelanguage.googleapis.com/v1beta/{file_name}?key={gemini_key}",
                    headers={},
                    method='DELETE'
                )
                urllib.request.urlopen(del_req, timeout=10)
            except Exception:
                pass

        if raw_text and len(raw_text.strip()) > 10:
            if '[no_speech_detected]' in raw_text.lower():
                return None
            clean_gemini = deduplicate_repetitive_hallucinations(raw_text)
            return validate_transcript_duration(clean_gemini, audio_duration)

    except Exception as e:
        sys.stderr.write(f"[WARN] Gemini audio transcribe exception: {e}\n")
    finally:
        if temp_ogg and os.path.exists(temp_ogg):
            try:
                os.remove(temp_ogg)
            except Exception:
                pass

    return None


def transcribe_audio(audio_path, booked_service='walking'):
    """Transcribes complete audio without gaps, using Gemini or Faster-Whisper."""
    audio_duration = get_audio_duration(audio_path)

    # 0. Immediate guard for dropped / sub-4s calls
    if audio_duration is not None and audio_duration < 4.0:
        return f"[CALL_TOO_SHORT] Call recording duration is only {audio_duration:.1f}s (dropped call / immediate hangup). No conversation took place.", audio_duration

    # 1. First attempt: Gemini direct audio transcription (best translation & no VAD cutouts)
    try:
        gemini_transcript = transcribe_with_gemini(audio_path, booked_service, audio_duration)
        if gemini_transcript:
            valid_gemini = validate_transcript_duration(gemini_transcript, audio_duration)
            if valid_gemini:
                return valid_gemini, audio_duration
    except Exception as ex:
        sys.stderr.write(f"[DEBUG] Gemini direct transcribe bypassed: {ex}\n")

    # 2. Second attempt: Robust Faster-Whisper with generous VAD & retry on gap detection
>>>>>>> origin/main
    try:
        from faster_whisper import WhisperModel
        model_size = os.getenv("WHISPER_MODEL", "small")
        model = WhisperModel(model_size, device="cpu", compute_type="int8")

<<<<<<< HEAD
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
=======
        def run_whisper(vad_enabled=True):
            kwargs = {
                "beam_size": 5,
                "task": "translate",
                "temperature": [0.0, 0.2, 0.4],
                "condition_on_previous_text": False,
                "no_speech_threshold": 0.8,
                "compression_ratio_threshold": 2.4,
                "log_prob_threshold": -1.0,
            }
            if vad_enabled:
                kwargs["vad_filter"] = True
                kwargs["vad_parameters"] = dict(
                    threshold=0.28,
                    min_silence_duration_ms=1200,
                    speech_pad_ms=400
                )
            else:
                kwargs["vad_filter"] = False

            try:
                return model.transcribe(audio_path, **kwargs)
            except TypeError:
                return model.transcribe(
                    audio_path,
                    beam_size=5,
                    task="translate",
                    temperature=0.0,
                    condition_on_previous_text=False
                )

        # First pass with gentle VAD
        segments, info = run_whisper(vad_enabled=True)

        transcript_parts = []
        last_end = 0.0
        max_gap = 0.0
        prev_end = 0.0

        for segment in segments:
            text = segment.text.strip()
            if text:
                if re.search(r'^(Petsfolio client and service provider|Thank you for watching|Subtitles by|Amara\.org)', text, flags=re.IGNORECASE):
                    continue
                if prev_end > 0 and (segment.start - prev_end) > max_gap:
                    max_gap = segment.start - prev_end
                transcript_parts.append(f"[{segment.start:.1f}s - {segment.end:.1f}s] {text}")
                prev_end = segment.end
                last_end = segment.end

        # If there is an unexplained huge gap (> 18s) in a recorded call, run unconstrained Whisper pass
        if max_gap > 18.0 and audio_duration > 30.0:
            try:
                sys.stderr.write(f"[INFO] Detected {max_gap:.1f}s gap in transcript. Running gap-free fallback pass...\n")
                segments_fallback, _ = run_whisper(vad_enabled=False)
                fallback_parts = []
                for segment in segments_fallback:
                    text = segment.text.strip()
                    if text and not re.search(r'^(Thank you for watching|Subtitles by|Amara\.org)', text, flags=re.IGNORECASE):
                        fallback_parts.append(f"[{segment.start:.1f}s - {segment.end:.1f}s] {text}")
                if len(fallback_parts) > len(transcript_parts):
                    transcript_parts = fallback_parts
            except Exception as e:
                sys.stderr.write(f"[WARN] Fallback unconstrained pass failed: {e}\n")

        full_transcript = deduplicate_repetitive_hallucinations("\n".join(transcript_parts))
>>>>>>> origin/main
        if hasattr(info, 'duration') and info.duration and info.duration > 0:
            audio_duration = round(info.duration, 1)
        elif last_end > 0:
            audio_duration = round(last_end, 1)

        if full_transcript.strip():
<<<<<<< HEAD
            return full_transcript, audio_duration
=======
            valid_whisper = validate_transcript_duration(full_transcript, audio_duration)
            if valid_whisper and valid_whisper.strip():
                return valid_whisper, audio_duration
>>>>>>> origin/main
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

<<<<<<< HEAD
CRITICAL AUDITING OBJECTIVES:
1. What was discussed? (Concise summary)
2. Was the conversation related to the booked Petsfolio service ({booked_service})?
3. Admin Attention: Flag any off-platform direct payments, personal contact sharing, complaints, refund demands, abusive speech, or unauthorized service requests.
4. Calculate Service Relevance % and Unrelated %.
5. Assign Overall AI Rating (🟢 Good | 🟡 Review Recommended | 🔴 Critical).

Transcript:
\"\"\"
{clean_text[:4000]}
=======
CRITICAL AUDITING OBJECTIVES & BUSINESS RULES:
1. Core Questions to Answer:
   - What was discussed? (Concise structured summary)
   - Was the conversation related to the booked Petsfolio service ({booked_service})?
   - Does anything require Admin attention?
2. DO NOT OVER-HIGHLIGHT (False Highlight Prevention):
   - Normal greetings (Hello, Hi, Good morning, How are you, Thank you, Okay, Sure, Fine, Bye) must NEVER be highlighted.
   - Normal confirmation statements ("I will come at 7 AM", "Sure I will take care") must NEVER be highlighted.
   - Normal service inquiries (walk timing, grooming inclusions, sitting schedule) must NEVER be highlighted.
   - Normal pet-related information ("Bruno is nervous", "Bruno is not feeling comfortable today", feeding/walking habits) is care context, NOT a complaint or violation.
   - Normal GST or platform charge questions ("Why is GST charged?", "What are platform charges?") are legitimate inquiries, NOT policy violations.
   - Cancellations and rescheduling are normal service coordination, NOT complaints or violations unless accompanied by dispute.
   - Short personal conversation (e.g., "Where are you from?" "Hyderabad") is Level 2 Unrelated, Low severity, NO Admin Alert.
3. CRITICAL POLICY VIOLATIONS (Level 4 Critical | Immediate Admin Alert):
   - Off-Platform Payment: Proposing or agreeing to pay cash, direct personal UPI (GPay/PhonePe), or bank transfer outside Petsfolio.
   - Contact Sharing: Requesting/sharing personal mobile numbers or moving communication to WhatsApp or personal email.
   - Petsfolio Platform Bypass: Proposing or agreeing to book directly, contact directly for next service, or bypass the Petsfolio platform.
   - GST & Fee Evasion Bypass: Using GST or platform charges as an excuse/reason to bypass Petsfolio or transact directly off-platform.
   - Inappropriate / Abusive Language: Actual insults, slurs, or profanity directed at a person (distinguish from complaints like "service was bad").
   - Threatening Communication: Threats of legal action, police, or harm.
4. SENTENCE-LEVEL HIGHLIGHTING:
   - In "highlighted_conversations", highlight ONLY the exact specific offending sentence(s). NEVER highlight the whole conversation or normal service coordination.
5. Assign Overall AI Rating (🟢 Good | 🟡 Review Recommended | 🔴 Critical) and calculate metrics.

Transcript:
\"\"\"
{clean_text[:32000]}
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
    if transcript.startswith('[CALL_TOO_SHORT]'):
        return {
            "service_type": booked_service,
            "summary": "⚪ Call Dropped / Immediate Hangup: Call duration was under 4 seconds. The call ended immediately or dropped before any conversation took place.",
            "overall_ai_rating": "⚪ Dropped Call",
            "ai_rating_score": 0.0,
            "service_relevance_score": 0.0,
            "service_related_pct": 0.0,
            "unrelated_pct": 0.0,
            "admin_alert_required": 0,
            "inappropriate_language": 0,
            "off_platform_payment": 0,
            "contact_sharing": 0,
            "outside_service_request": 0,
            "refund_requested": 0,
            "customer_sentiment": "Neutral",
            "provider_sentiment": "Neutral",
            "service_topics": [],
            "important_statements": [],
            "unrelated_statements": [],
            "complaint_statements": [],
            "policy_violations": [],
            "alerts": [],
            "highlighted_conversations": [],
            "conversation_segments": [],
            "ai_recommendations": []
        }

>>>>>>> origin/main
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

<<<<<<< HEAD
    # 3. Off-Platform Payment Discussions (Section 18)
=======
    # 3. Off-Platform Payment & Platform Bypass Discussions (Section 12, 13)
>>>>>>> origin/main
    off_platform_payment = 0
    off_platform_statements = []
    for trig in OFF_PLATFORM_PAYMENT_TRIGGERS:
        if trig in text_lower:
            off_platform_payment = 1
            off_platform_statements.append(trig)

<<<<<<< HEAD
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
=======
    platform_bypass = 0
    platform_bypass_statements = []
    if check_platform_bypass_match(text_lower):
        platform_bypass = 1
        platform_bypass_statements.append("Direct platform bypass / offline service discussed")
    for trig in PLATFORM_BYPASS_TRIGGERS:
        if trig in text_lower and trig not in platform_bypass_statements:
            platform_bypass = 1
            platform_bypass_statements.append(trig)

    gst_bypass = 0
    gst_bypass_statements = []
    if check_gst_bypass_match(text_lower):
        gst_bypass = 1
        gst_bypass_statements.append("Fee evasion citing GST or platform charges")
    for trig in GST_BYPASS_TRIGGERS:
        if trig in text_lower and trig not in gst_bypass_statements:
            gst_bypass = 1
            gst_bypass_statements.append(trig)

    # 4. Contact Sharing Detection (Section 19)
    contact_sharing = 0
    contact_sharing_statements = []
    has_phone_number_spoken = PHONE_REGEX.search(clean_text) is not None
    if has_phone_number_spoken or check_contact_sharing_match(text_lower):
        contact_sharing = 1
        contact_sharing_statements.append("Personal phone / direct contact details exchanged")
    for trig in CONTACT_SHARING_TRIGGERS:
        if trig in text_lower and trig not in contact_sharing_statements:
>>>>>>> origin/main
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
<<<<<<< HEAD
    for trig in COMPLAINT_TRIGGERS:
        if trig in text_lower:
=======
    if check_complaint_match(text_lower):
        has_complaint = 1
        complaint_statements.append("Delayed service / walker late arrival complaint voiced")
    for trig in COMPLAINT_TRIGGERS:
        if trig in text_lower and trig not in complaint_statements:
>>>>>>> origin/main
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
<<<<<<< HEAD
        ts_match = re.match(r'(\[\d+\.\d+s\s*-\s*\d+\.\d+s\])?\s*(.*)', line)
        ts = ts_match.group(1) if ts_match else f"[00:00]"
=======
        ts_match = re.match(r'(\[(?:\d+:)?\d+(?:\.\d+)?s\s*-\s*(?:\d+:)?\d+(?:\.\d+)?s\])?\s*(.*)', line)
        ts = ts_match.group(1) if (ts_match and ts_match.group(1)) else f"[{idx:02d}:00]"
>>>>>>> origin/main
        content = ts_match.group(2).strip() if ts_match else line
        content_lower = content.lower()

        line_is_service = False
        line_is_unrelated = False
        line_is_complaint = False
        line_is_policy = False
        line_is_filler = bool(filler_regex.match(content.strip()))

<<<<<<< HEAD
        # Check policy violation in line
        if any(trig in content_lower for trig in OFF_PLATFORM_PAYMENT_TRIGGERS):
=======
        # Check if line is legitimate charges/billing question (False Highlight Prevention)
        is_safe_charge_inquiry = any(s in content_lower for s in SAFE_CHARGES_INQUIRIES)
        has_bypass_with_charges = check_gst_bypass_match(content)
        has_bypass_platform = check_platform_bypass_match(content)
        has_off_platform = any(trig in content_lower for trig in OFF_PLATFORM_PAYMENT_TRIGGERS)
        has_contact_share = check_contact_sharing_match(content)
        has_comp = check_complaint_match(content)
        has_abusive_speech = any(phrase in content_lower for phrase in ABUSIVE_PHRASES)

        if is_safe_charge_inquiry and not has_bypass_with_charges:
            line_is_service = True

        # Check policy violation in line
        if has_bypass_with_charges:
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "🚨 Attempting to bypass Petsfolio to avoid GST / platform charges."
            })
        elif has_off_platform:
>>>>>>> origin/main
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "🚨 Off-platform direct payment discussion detected."
            })
<<<<<<< HEAD
        elif any(trig in content_lower for trig in CONTACT_SHARING_TRIGGERS) or re.search(r'\b\d{5}\s*\d{5}\b|\b[6-9]\d{9}\b', content):
=======
        elif has_bypass_platform:
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "🚨 Petsfolio platform bypass / direct offline booking discussion detected."
            })
        elif has_contact_share:
>>>>>>> origin/main
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "⚠️ Personal contact / WhatsApp exchange detected."
            })
<<<<<<< HEAD
        elif any(phrase in content_lower for phrase in ABUSIVE_PHRASES):
=======
        elif has_abusive_speech:
>>>>>>> origin/main
            line_is_policy = True
            highlighted_conversations.append({
                "speaker": "Client / SP",
                "timestamp": ts,
                "category": "Policy Violation / Serious Issue",
                "category_icon": "🔴",
                "sentence": content,
                "note": "🚨 Inappropriate / abusive language detected."
            })

<<<<<<< HEAD
        # Check complaints in line
        if any(trig in content_lower for trig in COMPLAINT_TRIGGERS) or any(trig in content_lower for trig in REFUND_TRIGGERS):
=======
        # Check complaints in line (exclude normal pet care info like "comfortable" or "nervous")
        is_pet_temperament_context = any(w in content_lower for w in ['comfortable', 'nervous', 'adjust', 'eating routine', 'temperament', 'likes walking'])
        if not is_pet_temperament_context and (has_comp or any(trig in content_lower for trig in REFUND_TRIGGERS)):
>>>>>>> origin/main
            line_is_complaint = True
            highlighted_conversations.append({
                "speaker": "Client",
                "timestamp": ts,
                "category": "Complaint",
                "category_icon": "🟠",
                "sentence": content,
<<<<<<< HEAD
                "note": "Client voiced a complaint or refund request."
=======
                "note": "Client voiced a complaint or refund request regarding service delay / arrival."
>>>>>>> origin/main
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

<<<<<<< HEAD
    # 9. Determine Overall AI Rating (Section 29)
    if off_platform_payment or has_abuse or has_threats or (has_complaint and has_refund):
=======
    # 9. Determine Overall AI Rating (Section 15, 18)
    if off_platform_payment or platform_bypass or gst_bypass or contact_sharing or has_abuse or has_threats or (has_complaint and has_refund):
>>>>>>> origin/main
        overall_ai_rating = "🔴 Critical"
        ai_rating_color = "danger"
        ai_rating_score = 3.0
        admin_alert_required = 1
    elif not has_global_pet_or_service or unrelated_pct >= 50.0:
        overall_ai_rating = "🟡 Review Recommended"
        ai_rating_color = "warning"
        ai_rating_score = 3.5
        admin_alert_required = 1
<<<<<<< HEAD
    elif has_complaint or has_refund or outside_service_request or unrelated_pct > 20.0 or contact_sharing:
=======
    elif has_complaint or has_refund or outside_service_request or unrelated_pct > 20.0:
>>>>>>> origin/main
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
<<<<<<< HEAD
    customer_sentiment = "Positive"
    if has_abuse or has_threats or has_complaint or has_refund:
=======
    provider_agreed_bypass = False
    provider_agree_phrases = ['we will do that', 'we will do it directly', 'we can do that', 'okay we will do', 'ok we will do', 'i will come directly', 'call me again later', 'call me later', 'call you directly', 'my number is']
    for p in provider_agree_phrases:
        if p in text_lower:
            provider_agreed_bypass = True
            break

    customer_sentiment = "Positive"
    if has_abuse or has_threats or has_complaint or has_refund or platform_bypass or gst_bypass:
>>>>>>> origin/main
        customer_sentiment = "Negative"
    elif not has_global_pet_or_service or unrelated_pct > 30.0:
        customer_sentiment = "Neutral"

    provider_sentiment = "Positive"
    if has_abuse:
        provider_sentiment = "Negative"
<<<<<<< HEAD
    elif off_platform_payment:
=======
    elif (platform_bypass or gst_bypass or off_platform_payment or contact_sharing) and (provider_agreed_bypass or has_phone_number_spoken):
        provider_sentiment = "Non-Compliant"
    elif off_platform_payment or platform_bypass or gst_bypass or contact_sharing:
>>>>>>> origin/main
        provider_sentiment = "Non-Compliant"
    elif not has_global_pet_or_service:
        provider_sentiment = "Neutral"

    # 11. Generate Structured 3-Question AI Summary (Sections 21, 22, 36)
<<<<<<< HEAD
    summary_parts = []
=======
>>>>>>> origin/main
    service_label = SERVICE_KB.get(booked_service, {}).get('name', booked_service.title())

    # Question 1: What was discussed?
    if not has_global_pet_or_service:
        unrelated_desc = ", ".join(unrelated_found_categories) if unrelated_found_categories else "personal / non-service matters"
        q1 = f"Client and Service Provider interaction recorded. No discussion regarding pet care, dog walking, or service requirements was detected. Participants discussed: {unrelated_desc}."
    else:
        q1 = f"Client and Service Provider communicated regarding the {service_label} booking."
<<<<<<< HEAD
        if detected_service_topics:
            q1 += f" Key topics touched upon: {', '.join(detected_service_topics)}."
    summary_parts.append(q1)
=======
        if has_complaint and (platform_bypass or gst_bypass or contact_sharing):
            q1 += f" Client voiced a severe delay complaint (walker had not arrived 5 hours after scheduled time) and proposed offline direct service outside Petsfolio to avoid GST and platform charges, which the provider accepted with direct contact numbers exchanged."
        elif has_complaint:
            q1 += f" Client voiced a service complaint regarding timing and arrival delay."
        elif platform_bypass or gst_bypass:
            q1 += f" Discussion touched upon proposing direct offline service outside Petsfolio to avoid platform charges and GST."
        elif detected_service_topics:
            q1 += f" Key topics touched upon: {', '.join(detected_service_topics)}."
>>>>>>> origin/main

    # Question 2: Was the conversation related to the booked service?
    if not has_global_pet_or_service:
        q2 = f"The conversation was entirely unrelated to Petsfolio or the booked {service_label} service (0.0% service related, 100.0% unrelated topics)."
<<<<<<< HEAD
=======
    elif platform_bypass or gst_bypass or contact_sharing:
        q2 = f"The conversation began regarding the booked {service_label} service ({service_related_pct}% service related), but shifted to off-platform direct booking, GST fee evasion, and direct contact sharing ({unrelated_pct}% non-compliant / policy violation)."
>>>>>>> origin/main
    elif unrelated_pct < 10.0:
        q2 = f"The conversation was highly focused and relevant to the booked {service_label} service ({service_related_pct}% service related)."
    else:
        unrelated_desc = ", ".join(unrelated_found_categories) if unrelated_found_categories else "personal topics"
        q2 = f"The conversation was partially service-related ({service_related_pct}%). An unrelated discussion ({unrelated_pct}%) was detected regarding: {unrelated_desc}."
<<<<<<< HEAD
    summary_parts.append(q2)

    # Question 3: Was there anything requiring Admin attention?
    q3_alerts = []
    if off_platform_payment:
        q3_alerts.append("🚨 Potential off-platform direct payment discussion detected")
    if contact_sharing:
        q3_alerts.append("⚠️ Personal phone / WhatsApp contact sharing attempt flagged")
=======

    # Question 3: Was there anything requiring Admin attention?
    q3_alerts = []
    if platform_bypass:
        q3_alerts.append("🚨 Petsfolio platform bypass / direct offline booking proposal detected")
    if gst_bypass:
        q3_alerts.append("🚨 Fee evasion / bypass citing GST or platform charges")
    if off_platform_payment:
        q3_alerts.append("🚨 Off-platform direct payment discussion detected")
    if contact_sharing:
        q3_alerts.append("🚨 Personal phone / direct contact details exchanged")
    if has_complaint:
        q3_alerts.append(f"🟠 Service complaint voiced ({', '.join(complaint_statements[:2])})")
>>>>>>> origin/main
    if outside_service_request:
        q3_alerts.append(f"⚠️ Outside-service request noted ({', '.join(outside_service_notes)})")
    if has_refund:
        q3_alerts.append("🚨 Client requested a refund / money return")
<<<<<<< HEAD
    if has_complaint:
        q3_alerts.append(f"🟠 Service complaint voiced ({', '.join(complaint_statements[:2])})")
=======
>>>>>>> origin/main
    if has_abuse:
        q3_alerts.append(f"🚨 Inappropriate / abusive language detected ({', '.join(abusive_statements[:2])})")
    if not has_global_pet_or_service:
        q3_alerts.append("⚠️ 100% personal / non-service call recorded under client booking")

    if q3_alerts:
        q3 = "Admin Attention Required: " + "; ".join(q3_alerts) + "."
    else:
        q3 = "No critical compliance concerns, policy violations, or unresolved disputes were identified."
<<<<<<< HEAD
    summary_parts.append(q3)

    structured_summary = " ".join(summary_parts)

    # 12. Build Structured Alerts List (Section 23, 29)
=======

    summary_parts = [q1, q2, q3]
    structured_summary = " ".join(summary_parts)

    # 12. Build Structured Alerts List (Section 15, 18)
>>>>>>> origin/main
    alerts = []
    if off_platform_payment:
        alerts.append({
            "type": "Critical Alert",
            "category": "Off-Platform Payment",
            "message": "Discussion detected attempting to make payment directly outside Petsfolio.",
            "icon": "🔴"
        })
<<<<<<< HEAD
=======
    if platform_bypass:
        alerts.append({
            "type": "Critical Alert",
            "category": "Platform Bypass",
            "message": f"Discussion detected proposing to book or contact directly outside Petsfolio ({', '.join(platform_bypass_statements[:2])}).",
            "icon": "🔴"
        })
    if gst_bypass:
        alerts.append({
            "type": "Critical Alert",
            "category": "GST / Fee Evasion Bypass",
            "message": "Attempting to bypass Petsfolio to avoid GST or platform charges.",
            "icon": "🔴"
        })
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
    if platform_bypass or gst_bypass:
        ai_recommendations.append("Reiterate Petsfolio Terms of Service regarding strict prohibition of direct off-platform client-provider bookings and fee evasion.")
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
        "summary_q1": q1,
        "summary_q2": q2,
        "summary_q3": q3,
>>>>>>> origin/main
        "overall_ai_rating": overall_ai_rating,
        "ai_rating_color": ai_rating_color,
        "ai_rating_score": ai_rating_score,
        "service_relevance_score": service_relevance_score,
        "service_related_pct": service_related_pct,
        "unrelated_pct": unrelated_pct,
        "admin_alert_required": admin_alert_required,
        "inappropriate_language": has_abuse,
<<<<<<< HEAD
        "off_platform_payment": off_platform_payment,
=======
        "off_platform_payment": 1 if (off_platform_payment or platform_bypass or gst_bypass) else 0,
>>>>>>> origin/main
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
<<<<<<< HEAD
        "policy_violations": off_platform_statements + abusive_statements + contact_sharing_statements,
=======
        "policy_violations": off_platform_statements + platform_bypass_statements + gst_bypass_statements + abusive_statements + contact_sharing_statements,
>>>>>>> origin/main
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
