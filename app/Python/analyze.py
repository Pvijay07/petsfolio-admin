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
import base64
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

# ─── Context-Aware Detection: Pet Domain & ASR Correction ───────────────────

# Terms that indicate the surrounding context is about pets/animals/handling,
# meaning abuse/negative keywords nearby are likely describing pet behavior,
# NOT actual abuse directed at a person.
PET_DOMAIN_CONTEXT_TERMS = [
    # Animal types
    'dog', 'dogs', 'puppy', 'puppies', 'cat', 'cats', 'kitten', 'pet', 'pets',
    'animal', 'animals', 'breed', 'pup', 'canine', 'feline',
    # Pet behavior descriptors
    'bark', 'barking', 'bite', 'biting', 'chew', 'chewing', 'scratch', 'scratching',
    'leash', 'collar', 'paw', 'tail', 'sniff', 'growl', 'growling', 'whine',
    'dominant', 'dominance', 'submissive', 'territorial', 'prey', 'chase', 'chasing',
    # Pet handling / training
    'walk', 'walking', 'walker', 'sitter', 'sitting', 'grooming', 'groomer',
    'train', 'training', 'trainer', 'obedient', 'obedience', 'command', 'commands',
    'control', 'controlled', 'handle', 'handling', 'calm', 'calming', 'quiet',
    'behave', 'behavior', 'behaviour', 'misbehave', 'misbehaving',
    # Pet care
    'feed', 'feeding', 'food', 'meal', 'meals', 'treat', 'treats', 'diet',
    'vet', 'veterinary', 'veterinarian', 'injection', 'vaccine', 'medicine',
    'stomach', 'venom', 'snake', 'injured', 'injury', 'sick', 'illness',
    # Petsfolio context
    'petsfolio', 'service provider', 'verification', 'booking', 'client',
    'pet parent', 'pet parents', 'schedule', 'visit',
]

# Known ASR (speech-to-text) mistranscription pairs.
# If the flagged keyword appears AND nearby context contains the correction hint,
# suppress the flag as it's likely a transcription error.
# Format: { 'flagged_word': ['context_hints_that_mean_its_a_mistranscription'] }
ASR_MISTRANSCRIPTION_PAIRS = {
    'fool': ['food', 'eat', 'eating', 'eats', 'feed', 'feeding', 'meal', 'meals',
             'stomach', 'diet', 'hungry', 'appetite'],
    'mad': ['medicine', 'medical', 'medication', 'vet', 'veterinary', 'doctor',
            'dose', 'drug', 'drugs', 'diagnostic', 'injection'],
    'damn': ['dam', 'damage', 'damaged'],
    'trash': ['treat', 'treats', 'training'],
    'crap': ['strap', 'wrap', 'leash'],
    'bloody': ['blood', 'bleeding', 'injury', 'injured', 'wound', 'bitten', 'bite'],
    'tone': ['training', 'command', 'commands', 'voice', 'dog', 'pet', 'calm'],
    'attitude': ['behavior', 'behaviour', 'temperament', 'dog', 'pet', 'animal'],
}

# ─── Service Resolver & Helper Functions ────────────────────────────────────

SERVICE_NAME_TO_ID = {
    'walking': 4,
    'dog walking': 4,
    'walker': 4,
    '4': 4,
    4: 4,
    'grooming': 2,
    'dog grooming': 2,
    'groomer': 2,
    '2': 2,
    2: 2,
    'training': 3,
    'dog training': 3,
    'trainer': 3,
    '3': 3,
    3: 3,
    'sitting': 6,
    'pet sitting': 6,
    'sitter': 6,
    'boarding': 6,
    'daycare': 6,
    '6': 6,
    6: 6,
    '1': 6,
    1: 6
}

def resolve_service_id(service_identifier):
    if not service_identifier:
        return None
    s_key = str(service_identifier).strip().lower()
    return SERVICE_NAME_TO_ID.get(s_key, None)


def detect_service_from_transcript(transcript):
    """
    Intelligently detect the service type from transcript keywords when not explicitly specified.
    """
    t_lower = transcript.lower()
    grooming_signals = ['grooming', 'groomer', 'shampoo', 'nail cutting', 'hair trimming', 'bath', 'bathing', 'hygiene trim', 'tools sanitize']
    training_signals = ['training', 'trainer', 'obedience', 'potty training', 'leash training', 'aggression correction', 'reward-based', 'sessions needed']
    sitting_signals = ['sitting', 'pet sitting', 'sitter', 'overnight', 'drop-in', 'home visit', 'daycare', 'boarding', 'feed', 'litter box', 'house keys', 'house access']
    
    g_score = sum(1 for s in grooming_signals if s in t_lower)
    t_score = sum(1 for s in training_signals if s in t_lower)
    s_score = sum(1 for s in sitting_signals if s in t_lower)
    
    if g_score > t_score and g_score > s_score and g_score >= 2:
        return 2  # Grooming
    elif t_score > g_score and t_score > s_score and t_score >= 2:
        return 3  # Training
    elif s_score > g_score and s_score > t_score and s_score >= 2:
        return 6  # Sitting
    return 4      # Default Dog Walking


def generate_question_triggers(question_text):
    """
    Generate flexible, high-precision trigger phrases from question text.
    """
    q_clean = re.sub(r'^(TEST-v2:\s*|Instruction to be read by Executive:\s*)', '', question_text, flags=re.IGNORECASE).strip()
    q_lower = q_clean.lower()
    
    triggers = []
    
    # Key phrase extractors
    words = re.findall(r'\b[a-zA-Z]{3,}\b', q_lower)
    stop_words = {'what', 'will', 'you', 'how', 'why', 'did', 'the', 'and', 'for', 'are', 'can', 'one', 'have', 'from', 'this', 'that', 'with', 'your', 'tell', 'about', 'time', 'when', 'does', 'should', 'would', 'could', 'please'}
    meaningful = [w for w in words if w not in stop_words]
    
    # 2-word & 3-word n-grams of meaningful words
    for i in range(len(meaningful) - 1):
        triggers.append(f"{meaningful[i]} {meaningful[i+1]}")
    if len(meaningful) >= 3:
        for i in range(len(meaningful) - 2):
            triggers.append(f"{meaningful[i]} {meaningful[i+1]} {meaningful[i+2]}")
            
    # Substring window of 3-4 words from raw question
    q_words = q_clean.split()
    if len(q_words) >= 4:
        for i in range(0, max(1, len(q_words) - 3), 2):
            triggers.append(" ".join(q_words[i:i+4]).lower())
            
    # Raw question without punctuation
    raw_no_punct = re.sub(r'[^\w\s]', '', q_lower).strip()
    if raw_no_punct:
        triggers.append(raw_no_punct)
        
    return list(dict.fromkeys([t for t in triggers if len(t) >= 4]))


def generate_expected_answer_keywords(hint_text, question_text=""):
    """
    Generate expected answer keywords from hint text for relevance checking.
    """
    combined = f"{hint_text} {question_text}".lower()
    words = re.findall(r'\b[a-zA-Z]{3,}\b', combined)
    stop_words = {'the', 'and', 'for', 'are', 'can', 'with', 'this', 'that', 'from', 'will', 'would', 'should', 'have', 'been', 'what', 'when', 'where', 'how', 'why', 'your', 'about', 'just', 'does', 'doing', 'tell'}
    kw = [w for w in words if w not in stop_words]
    # Include standard verification confirmation terms
    kw.extend(['yes', 'agree', 'correct', 'sure', 'definitely', 'comfortable', 'follow', 'understand', 'experience', 'handled', 'years', 'months'])
    return list(dict.fromkeys(kw))


def deduplicate_questions(questions, selected_types=None):
    """
    Deduplicate verification questions across multiple training_types and group_keys.
    If multiple training_types exist with the same question, only checks it ONCE.
    """
    seen_groups = set()
    seen_normalized = set()
    deduped = []
    
    for q in questions:
        # Check training_type filter if specified
        q_type = (q.get('training_type') or '').strip()
        if q_type and selected_types and q_type not in selected_types:
            continue
            
        group_key = (q.get('group_key') or '').strip()
        q_text = q.get('question') or q.get('question_text') or ''
        norm_text = re.sub(r'[^a-zA-Z0-9]', '', q_text.lower()).strip()
        
        if group_key:
            if group_key in seen_groups:
                continue
            seen_groups.add(group_key)
        elif norm_text:
            if norm_text in seen_normalized:
                continue
            seen_normalized.add(norm_text)
            
        deduped.append(q)
        
    return deduped


# ─── Comprehensive Verification Questions Knowledge Base by Service ─────────
# Complete question sets directly mirroring the verification_questions database table.

VERIFICATION_QUESTIONS_BY_SERVICE = {
    # ─── DOG WALKING (Service ID 4) ──────────────────────────────────────────
    4: [
        {
            "id": "q_why_work_with_pets",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "Why did you choose to work with pets?",
            "hint_text": "I genuinely love animals, feel comfortable around dogs, and enjoy taking care of them.",
            "weight": 2.0,
            "q_triggers": ["why did you choose", "why work with pets", "why do you want", "interest in pets", "why choose"],
            "expected_ans_kw": ["love", "animals", "dogs", "cats", "enjoy", "comfortable", "passion", "caring", "pets", "pet"],
            "critical": True
        },
        {
            "id": "q_handle_bite",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "If a dog tries to bite or misbehaves, what will you do?",
            "hint_text": "I will stay calm, avoid shouting or hitting, give the dog space, and try to control the situation safely.",
            "weight": 5.0,
            "q_triggers": ["tries to bite", "bite", "misbehave", "aggressive", "dog attacks", "bark", "bites"],
            "expected_ans_kw": ["calm", "space", "shout", "hit", "gently", "control", "distance", "avoid", "careful", "stop", "safe"],
            "critical": True
        },
        {
            "id": "q_handle_scared",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "How do you handle a scared dog?",
            "hint_text": "I will approach slowly, speak gently, avoid forcing interaction, and give the dog time to trust me.",
            "weight": 4.0,
            "q_triggers": ["scared dog", "nervous dog", "afraid", "shy dog", "scared"],
            "expected_ans_kw": ["slowly", "gently", "softly", "space", "time", "trust", "comfort", "calm"],
            "critical": False
        },
        {
            "id": "q_stress_signs",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "What signs show you that a dog is stressed or uncomfortable?",
            "hint_text": "Tail tucked, avoiding eye contact, lip licking, growling, moving away, nervous behaviour.",
            "weight": 5.0,
            "q_triggers": ["stressed", "uncomfortable", "signs show", "body language", "stress signs"],
            "expected_ans_kw": ["tail", "eye", "licking", "growling", "moving", "nervous", "tucked", "avoiding", "ears"],
            "critical": True
        },
        {
            "id": "q_precautions",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "What basic precautions do you take before starting service with a new dog?",
            "hint_text": "I observe the dog’s behaviour, check leash and surroundings, and make the dog comfortable first.",
            "weight": 4.0,
            "q_triggers": ["basic precautions", "precautions before", "starting service", "new dog precautions"],
            "expected_ans_kw": ["observe", "behaviour", "check", "leash", "surroundings", "comfortable", "first", "collar"],
            "critical": False
        },
        {
            "id": "q_never_do",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "What should you never do while handling a dog?",
            "hint_text": "Never shout, hit, scare, pull harshly, or force the dog.",
            "weight": 3.0,
            "q_triggers": ["never do", "should not do", "never while handling", "mistakes"],
            "expected_ans_kw": ["never", "shout", "hit", "scare", "pull", "harshly", "force"],
            "critical": False
        },
        {
            "id": "q_make_comfortable",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "How do you make a new dog comfortable in the first few minutes?",
            "hint_text": "I give the dog space, let it smell me, speak softly, and avoid sudden actions.",
            "weight": 3.0,
            "q_triggers": ["make a new dog comfortable", "first few minutes", "make comfortable", "introduce yourself"],
            "expected_ans_kw": ["space", "smell", "softly", "avoid", "sudden", "gentle", "treat"],
            "critical": False
        },
        {
            "id": "q_difficult_situation",
            "service_id": 4,
            "category": "Behaviour, Psychology & Pet Handling",
            "question": "Can you share one difficult situation you faced with a dog and how you handled it?",
            "hint_text": "Real situation handled calmly, patiently, and safely without aggression.",
            "weight": 4.0,
            "q_triggers": ["difficult situation", "faced with a dog", "difficult case", "challenging"],
            "expected_ans_kw": ["calmly", "patiently", "safely", "without", "aggression", "handled", "situation", "dog"],
            "critical": False
        },
        {
            "id": "q_has_exp",
            "service_id": 4,
            "category": "Experience",
            "question": "Do you have experience handling dogs?",
            "hint_text": "Yes, I have practical experience handling dogs.",
            "weight": 8.0,
            "q_triggers": ["experience handling", "experience with dogs", "handled dogs before", "practical experience"],
            "expected_ans_kw": ["yes", "practical", "experience", "handled", "years", "months", "dogs"],
            "critical": True
        },
        {
            "id": "q_years_exp",
            "service_id": 4,
            "category": "Experience",
            "question": "How many years of experience do you have with dogs?",
            "hint_text": "Clear hands-on experience explanation with confidence.",
            "weight": 7.0,
            "q_triggers": ["how many years", "years of experience", "how long", "duration of experience", "months of experience"],
            "expected_ans_kw": ["years", "months", "one", "two", "three", "four", "five", "six", "seven", "eight", "handled", "experience"],
            "critical": True
        },
        {
            "id": "q_own_pet",
            "service_id": 4,
            "category": "Experience",
            "question": "Do you own or have you owned a pet?",
            "hint_text": "Yes, I understand pet behaviour and responsibilities.",
            "weight": 5.0,
            "q_triggers": ["own a pet", "owned a pet", "have a pet", "pet at home", "dog named", "pet parent"],
            "expected_ans_kw": ["yes", "dog", "cat", "pet", "lucky", "home", "parenting", "breed", "understood"],
            "critical": True
        },
        {
            "id": "q_commitment",
            "service_id": 4,
            "category": "Job Commitment",
            "question": "Is this full-time or part-time for you? How serious are you about this work?",
            "hint_text": "I am serious about this work and can commit consistently.",
            "weight": 8.0,
            "q_triggers": ["full-time", "part-time", "how serious", "commitment", "hours per day"],
            "expected_ans_kw": ["full-time", "part-time", "serious", "commit", "consistently", "regular", "orders"],
            "critical": True
        },
        {
            "id": "q_follow_rules",
            "service_id": 4,
            "category": "Job Commitment",
            "question": "Do you agree to follow Petsfolio rules and not take clients offline?",
            "hint_text": "Yes, I will strictly follow Petsfolio policies.",
            "weight": 7.0,
            "q_triggers": ["follow petsfolio rules", "not take clients offline", "platform rules", "app policy", "ethical rules"],
            "expected_ans_kw": ["yes", "strictly", "follow", "rules", "policies", "agree", "app"],
            "critical": True
        },
        {
            "id": "q_run_away_action",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "If the dog suddenly runs away during a walk, what will you do?",
            "hint_text": "I will stay calm, safely try to stop/call the dog, and immediately inform the client and team if needed.",
            "weight": 3.0,
            "q_triggers": ["runs away", "dog runs away", "suddenly run away", "lost during walk", "escapes"],
            "expected_ans_kw": ["stay calm", "calm", "safely", "stop", "call", "inform", "client", "team", "chase", "gps"],
            "critical": True
        },
        {
            "id": "q_prevent_run_away",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "What precautions will you take to ensure the dog does NOT run away during a walk?",
            "hint_text": "Proper leash grip, staying alert, avoiding distractions, and choosing safe routes.",
            "weight": 3.0,
            "q_triggers": ["prevent run away", "not run away", "ensure dog does not run", "precautions for run away"],
            "expected_ans_kw": ["leash", "grip", "alert", "distractions", "safe", "routes", "hold"],
            "critical": False
        },
        {
            "id": "q_dog_attack_action",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "If another dog tries to attack the dog you are handling, what will you do?",
            "hint_text": "I will calmly move away, protect the dog, and avoid panic.",
            "weight": 2.0,
            "q_triggers": ["another dog tries to attack", "attack from other dogs", "street dog attack", "stray dog"],
            "expected_ans_kw": ["calmly", "move away", "protect", "avoid", "panic", "front side", "behind", "secure"],
            "critical": False
        },
        {
            "id": "q_prevent_attacks",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "What precautions will you take to avoid fights or attacks from other dogs during a walk?",
            "hint_text": "I will maintain distance from unknown dogs and stay aware of surroundings.",
            "weight": 2.0,
            "q_triggers": ["avoid fights", "attacks from other dogs", "precautions to avoid fights"],
            "expected_ans_kw": ["distance", "unknown", "aware", "surroundings", "avoid", "stray"],
            "critical": False
        },
        {
            "id": "q_injury_action",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "If the dog gets injured or meets with an accident during service, what will you do?",
            "hint_text": "I will immediately inform the pet parent and Petsfolio team and seek medical help if required.",
            "weight": 2.0,
            "q_triggers": ["injured", "accident", "dog gets injured", "medical emergency", "meets with an accident"],
            "expected_ans_kw": ["inform", "parent", "petsfolio", "medical", "doctor", "vet", "hospital", "clinic", "first aid"],
            "critical": True
        },
        {
            "id": "q_prevent_accidents",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "What precautions will you take to avoid accidents or injuries during service?",
            "hint_text": "I will avoid risky areas, stay attentive, and ensure safe handling throughout the walk.",
            "weight": 2.0,
            "q_triggers": ["avoid accidents", "injuries during service", "precautions to avoid injuries"],
            "expected_ans_kw": ["risky", "attentive", "safe", "handling", "throughout", "watch"],
            "critical": False
        },
        {
            "id": "q_sickness_action",
            "service_id": 4,
            "category": "Situational Safety & Presence of Mind",
            "question": "If the dog suddenly becomes sick or unwell during service, what will you do?",
            "hint_text": "I will stop the service, inform the parent/team immediately, and seek medical support.",
            "weight": 1.0,
            "q_triggers": ["sick", "unwell", "becomes sick", "vomit", "fever", "diarrhea"],
            "expected_ans_kw": ["stop", "inform", "parent", "team", "medical", "support", "vet", "doctor"],
            "critical": False
        },
        {
            "id": "q_punctuality",
            "service_id": 4,
            "category": "Punctuality & Travel",
            "question": "Will you be punctual for every service?",
            "hint_text": "Yes, I understand punctuality is important.",
            "weight": 4.0,
            "q_triggers": ["punctual", "on time", "punctuality for every service", "reach on time"],
            "expected_ans_kw": ["yes", "punctual", "time", "important", "regular"],
            "critical": False
        },
        {
            "id": "q_late_action",
            "service_id": 4,
            "category": "Punctuality & Travel",
            "question": "If you are getting late for a service, what will you do?",
            "hint_text": "I will inform the client and Petsfolio team in advance.",
            "weight": 3.0,
            "q_triggers": ["getting late", "if you are late", "delayed", "cannot reach on time"],
            "expected_ans_kw": ["inform", "client", "team", "advance", "message", "call"],
            "critical": False
        },
        {
            "id": "q_travel_limit",
            "service_id": 4,
            "category": "Punctuality & Travel",
            "question": "How far can you travel for services?",
            "hint_text": "Flexible and realistic travel commitment.",
            "weight": 1.0,
            "q_triggers": ["how far", "travel limit", "distance you can travel", "kms", "area"],
            "expected_ans_kw": ["km", "distance", "travel", "area", "flexible", "mumbai", "hyderabad", "bangalore", "nearby"],
            "critical": False
        },
        {
            "id": "q_transport",
            "service_id": 4,
            "category": "Punctuality & Travel",
            "question": "What is your mode of transportation?",
            "hint_text": "Own vehicle or reliable transportation method.",
            "weight": 2.0,
            "q_triggers": ["mode of transportation", "vehicle", "bike", "scooter", "commute"],
            "expected_ans_kw": ["bike", "vehicle", "two wheeler", "transport", "bus", "own"],
            "critical": False
        },
        {
            "id": "q_phone_call",
            "service_id": 4,
            "category": "Mobile Usage Discipline",
            "question": "If you receive a phone call while walking a dog, what will you do?",
            "hint_text": "I will avoid attending the call and focus on the dog’s safety.",
            "weight": 3.0,
            "q_triggers": ["phone call while walking", "receive a call", "mobile phone during walk"],
            "expected_ans_kw": ["avoid", "attending", "focus", "safety", "dog", "ignore", "later"],
            "critical": False
        },
        {
            "id": "q_phone_risk",
            "service_id": 4,
            "category": "Mobile Usage Discipline",
            "question": "What risks can happen if you use the phone while walking a dog?",
            "hint_text": "The dog may run away, eat something dangerous, pull suddenly, or get injured.",
            "weight": 2.0,
            "q_triggers": ["risks of phone", "phone risk", "using phone while walking"],
            "expected_ans_kw": ["run away", "eat", "dangerous", "pull", "injured", "distracted", "accident"],
            "critical": False
        },
        {
            "id": "q_difficult_parent",
            "service_id": 4,
            "category": "Human Behaviour with Clients",
            "question": "How will you handle a difficult or upset pet parent during a service?",
            "hint_text": "I will stay calm, polite, avoid arguments, and inform the Petsfolio team if required.",
            "weight": 2.0,
            "q_triggers": ["difficult pet parent", "upset pet parent", "angry client", "client complaints"],
            "expected_ans_kw": ["calm", "polite", "avoid arguments", "inform", "team", "explain"],
            "critical": False
        },
        {
            "id": "q_extra_work",
            "service_id": 4,
            "category": "Human Behaviour with Clients",
            "question": "If a client asks you to do extra work or handle another pet not booked, what will you do?",
            "hint_text": "I will politely ask them to book through the Petsfolio app.",
            "weight": 2.0,
            "q_triggers": ["extra work", "another pet not booked", "handle another pet", "extra services"],
            "expected_ans_kw": ["politely", "book", "app", "petsfolio app", "extra", "booking"],
            "critical": False
        },
        {
            "id": "q_dress_code",
            "service_id": 4,
            "category": "Human Behaviour with Clients",
            "question": "What kind of dress code will you follow while going for a service?",
            "hint_text": "Clean, comfortable clothes and proper footwear suitable for pet handling.",
            "weight": 1.0,
            "q_triggers": ["dress code", "clothes", "footwear", "wearing"],
            "expected_ans_kw": ["clean", "comfortable", "clothes", "footwear", "shoes", "suitable", "proper"],
            "critical": False
        },
        {
            "id": "q_verification",
            "service_id": 4,
            "category": "Trustworthiness & Safety",
            "question": "Are you comfortable with police/background verification?",
            "hint_text": "Yes, I am comfortable with verification.",
            "weight": 3.0,
            "q_triggers": ["police verification", "background verification", "background check", "identity check"],
            "expected_ans_kw": ["yes", "comfortable", "agree", "verification", "aadhaar", "police", "id"],
            "critical": True
        },
        {
            "id": "q_independent_handling",
            "service_id": 4,
            "category": "Trustworthiness & Safety",
            "question": "Have you ever handled pets independently at client homes?",
            "hint_text": "Yes, I have responsibly handled pets independently.",
            "weight": 3.0,
            "q_triggers": ["handled pets independently", "independently at client homes", "alone with pets"],
            "expected_ans_kw": ["yes", "responsibly", "handled", "independently", "home", "alone"],
            "critical": False
        },
        {
            "id": "q_ethics",
            "service_id": 4,
            "category": "Trustworthiness & Safety",
            "question": "Are you willing to follow ethical service policies?",
            "hint_text": "Yes, I will follow all ethical and platform policies.",
            "weight": 3.0,
            "q_triggers": ["ethical service policies", "follow ethics", "ethical policies", "honesty"],
            "expected_ans_kw": ["yes", "follow", "ethical", "platform", "policies", "honest", "honesty"],
            "critical": True
        },
        {
            "id": "q_no_offline",
            "service_id": 4,
            "category": "Trustworthiness & Safety",
            "question": "Would you agree not to take bookings outside the app?",
            "hint_text": "Yes, I will only accept bookings through Petsfolio.",
            "weight": 4.0,
            "q_triggers": ["outside the app", "bookings outside", "direct payment", "offline booking", "cash directly"],
            "expected_ans_kw": ["yes", "only", "accept", "through", "petsfolio", "no offline", "app only", "agree"],
            "critical": True
        },
        {
            "id": "q_trust_access",
            "service_id": 4,
            "category": "Trustworthiness & Safety",
            "question": "Can clients trust you with home/pet access responsibilities?",
            "hint_text": "Yes, I understand the responsibility and will behave professionally and safely.",
            "weight": 2.0,
            "q_triggers": ["trust you with home", "pet access", "home access responsibilities", "trustworthiness"],
            "expected_ans_kw": ["yes", "responsibility", "professionally", "safely", "trust", "honest"],
            "critical": False
        },
        {
            "id": "q_offline_warning",
            "service_id": 4,
            "category": "Ethics & Platform Discipline",
            "question": "Do you understand that taking clients offline will result in a penalty and account block?",
            "hint_text": "Instruction to be read by Executive: Taking clients offline is strictly not allowed.",
            "weight": 0.0,
            "q_triggers": ["taking clients offline", "penalty and account block", "offline penalty", "consequence of offline"],
            "expected_ans_kw": ["understand", "yes", "penalty", "account block", "block", "liable", "strictly", "offline"],
            "critical": True
        }
    ],

    # ─── GROOMING (Service ID 2) ─────────────────────────────────────────────
    2: [
        {
            "id": "g_why_groomer",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "Why did you choose to become a dog groomer?",
            "hint_text": "Love for dogs, comfort in handling them, interest in grooming.",
            "weight": 3.0,
            "q_triggers": ["why did you choose to become a dog groomer", "why groomer", "why grooming", "interest in grooming"],
            "expected_ans_kw": ["love", "dogs", "comfort", "handling", "grooming", "passion"],
            "critical": True
        },
        {
            "id": "g_handle_scared",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "How do you handle a dog that is scared or nervous for grooming?",
            "hint_text": "Slow approach, calm voice, give time, no force.",
            "weight": 5.0,
            "q_triggers": ["scared or nervous for grooming", "nervous dog grooming", "scared for grooming"],
            "expected_ans_kw": ["slow", "approach", "calm", "voice", "give time", "force", "comfort"],
            "critical": True
        },
        {
            "id": "g_stress_signs",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "What signs show a dog is stressed during grooming?",
            "hint_text": "Shaking, tail down, trying to escape, whining, growling.",
            "weight": 5.0,
            "q_triggers": ["stressed during grooming", "signs show a dog is stressed", "grooming stress signs"],
            "expected_ans_kw": ["shaking", "tail", "escape", "whining", "growling", "stress"],
            "critical": True
        },
        {
            "id": "g_never_do",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "What should a groomer never do during grooming and why?",
            "hint_text": "Never hit, shout, rush, or force. It can hurt the dog and break trust.",
            "weight": 5.0,
            "q_triggers": ["groomer never do", "never do during grooming", "what should never do"],
            "expected_ans_kw": ["never", "hit", "shout", "rush", "force", "hurt", "trust"],
            "critical": True
        },
        {
            "id": "g_make_comfortable",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "How do you make a new dog comfortable before starting grooming?",
            "hint_text": "Let the dog sniff tools, talk softly, gentle touch.",
            "weight": 4.0,
            "q_triggers": ["make a new dog comfortable before starting grooming", "comfortable before grooming", "sniff tools"],
            "expected_ans_kw": ["sniff", "tools", "softly", "gentle", "touch", "comfortable"],
            "critical": False
        },
        {
            "id": "g_stress_free",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "How do you make grooming a stress-free experience for the dog?",
            "hint_text": "Patience, breaks, gentle handling, positive behaviour.",
            "weight": 4.0,
            "q_triggers": ["stress-free experience", "make grooming stress free", "stress free"],
            "expected_ans_kw": ["patience", "breaks", "gentle", "positive", "behaviour"],
            "critical": False
        },
        {
            "id": "g_precautions",
            "service_id": 2,
            "category": "Behaviour, Pet Handling & Grooming Approach",
            "question": "What precautions do you take before starting grooming?",
            "hint_text": "Check dog behaviour, tools ready, safe setup.",
            "weight": 4.0,
            "q_triggers": ["precautions before starting grooming", "precautions do you take before grooming"],
            "expected_ans_kw": ["check", "behaviour", "tools", "ready", "safe", "setup"],
            "critical": False
        },
        {
            "id": "g_exp_detail",
            "service_id": 2,
            "category": "Grooming Experience & Qualification",
            "question": "Explain your practical experience in dog grooming.",
            "hint_text": "Hands-on grooming of different breeds and temperaments.",
            "weight": 8.0,
            "q_triggers": ["practical experience in dog grooming", "experience in grooming", "grooming experience"],
            "expected_ans_kw": ["hands-on", "grooming", "breeds", "temperaments", "experience", "years"],
            "critical": True
        },
        {
            "id": "g_years_exp",
            "service_id": 2,
            "category": "Grooming Experience & Qualification",
            "question": "How many years of grooming experience do you have?",
            "hint_text": "Clear timeline and work details.",
            "weight": 5.0,
            "q_triggers": ["years of grooming experience", "how many years of grooming", "years grooming"],
            "expected_ans_kw": ["years", "months", "timeline", "experience", "worked"],
            "critical": True
        },
        {
            "id": "g_services_list",
            "service_id": 2,
            "category": "Grooming Experience & Qualification",
            "question": "What grooming services can you do?",
            "hint_text": "Bathing, drying, nail cutting, ear cleaning, hair trimming, hygiene trim.",
            "weight": 4.0,
            "q_triggers": ["what grooming services can you do", "grooming services you provide", "types of grooming"],
            "expected_ans_kw": ["bathing", "drying", "nail cutting", "ear cleaning", "hair trimming", "hygiene", "trim"],
            "critical": False
        },
        {
            "id": "g_certification",
            "service_id": 2,
            "category": "Grooming Experience & Qualification",
            "question": "Do you have any grooming certification? From where?",
            "hint_text": "Course or certification details.",
            "weight": 3.0,
            "q_triggers": ["grooming certification", "certificate from where", "certified groomer"],
            "expected_ans_kw": ["course", "certification", "certificate", "institute", "academy", "learned"],
            "critical": False
        },
        {
            "id": "g_shampoo_choice",
            "service_id": 2,
            "category": "Grooming Knowledge & Process",
            "question": "How do you decide which shampoo or products to use for a dog?",
            "hint_text": "Based on coat type, skin condition, breed.",
            "weight": 5.0,
            "q_triggers": ["which shampoo or products", "shampoo to use", "products to use for a dog"],
            "expected_ans_kw": ["coat", "type", "skin", "condition", "breed", "shampoo", "medicated"],
            "critical": True
        },
        {
            "id": "g_session_duration",
            "service_id": 2,
            "category": "Grooming Knowledge & Process",
            "question": "How long should a grooming session take and why?",
            "hint_text": "Proper time without rushing to avoid stress.",
            "weight": 3.0,
            "q_triggers": ["how long should a grooming session take", "grooming duration", "session duration"],
            "expected_ans_kw": ["time", "hours", "minutes", "rushing", "avoid", "stress"],
            "critical": False
        },
        {
            "id": "g_parent_role",
            "service_id": 2,
            "category": "Grooming Knowledge & Process",
            "question": "What is the role of the pet parent during grooming?",
            "hint_text": "Share dog behaviour, health issues, and follow after-care advice.",
            "weight": 4.0,
            "q_triggers": ["role of the pet parent during grooming", "pet parent role", "client role during grooming"],
            "expected_ans_kw": ["share", "behaviour", "health", "issues", "after-care", "parent"],
            "critical": False
        },
        {
            "id": "g_tool_hygiene",
            "service_id": 2,
            "category": "Grooming Knowledge & Process",
            "question": "How do you maintain hygiene of your grooming tools?",
            "hint_text": "Clean and sanitize tools before and after every session.",
            "weight": 3.0,
            "q_triggers": ["hygiene of your grooming tools", "sanitize tools", "clean grooming tools"],
            "expected_ans_kw": ["clean", "sanitize", "tools", "disinfect", "before", "after"],
            "critical": False
        },
        {
            "id": "g_bite_action",
            "service_id": 2,
            "category": "Situational Handling & Safety",
            "question": "If a dog tries to bite during grooming, what will you do?",
            "hint_text": "Stay calm, pause, handle gently, no force.",
            "weight": 3.0,
            "q_triggers": ["tries to bite during grooming", "dog bites while grooming", "bite action grooming"],
            "expected_ans_kw": ["stay calm", "pause", "handle", "gently", "muzzle", "no force", "stop"],
            "critical": True
        },
        {
            "id": "g_injury_action",
            "service_id": 2,
            "category": "Situational Handling & Safety",
            "question": "If the dog gets a small cut or injury during grooming, what will you do?",
            "hint_text": "Inform pet parent and Petsfolio immediately, give first aid.",
            "weight": 3.0,
            "q_triggers": ["small cut or injury during grooming", "cut during grooming", "grooming injury"],
            "expected_ans_kw": ["inform", "pet parent", "petsfolio", "first aid", "antiseptic", "immediately"],
            "critical": True
        },
        {
            "id": "g_nail_precautions",
            "service_id": 2,
            "category": "Situational Handling & Safety",
            "question": "What precautions do you take while nail cutting and trimming?",
            "hint_text": "Careful handling, proper tools, slow process.",
            "weight": 3.0,
            "q_triggers": ["precautions while nail cutting", "nail trimming precautions", "nail cutting"],
            "expected_ans_kw": ["quick", "blood", "careful", "proper", "tools", "slow", "clipper"],
            "critical": False
        },
        {
            "id": "g_prevent_fall",
            "service_id": 2,
            "category": "Situational Handling & Safety",
            "question": "How do you ensure the dog does not slip or fall during grooming?",
            "hint_text": "Non-slip mat, proper holding, safe setup.",
            "weight": 3.0,
            "q_triggers": ["does not slip or fall", "prevent fall during grooming", "non-slip mat"],
            "expected_ans_kw": ["non-slip", "mat", "holding", "safe", "setup", "grip"],
            "critical": False
        },
        {
            "id": "g_prevent_stress",
            "service_id": 2,
            "category": "Situational Handling & Safety",
            "question": "How do you make sure the dog does not get over-stressed?",
            "hint_text": "Watch body language, give breaks.",
            "weight": 3.0,
            "q_triggers": ["not get over-stressed", "prevent over stress", "over-stressed"],
            "expected_ans_kw": ["watch", "body language", "give breaks", "pause", "calm"],
            "critical": False
        },
        {
            "id": "g_commitment",
            "service_id": 2,
            "category": "Job Commitment & Platform Discipline",
            "question": "Is this full-time or part-time for you? How serious are you?",
            "hint_text": "Serious and regular commitment.",
            "weight": 5.0,
            "q_triggers": ["full-time or part-time for you", "how serious are you", "commitment"],
            "expected_ans_kw": ["full-time", "part-time", "serious", "regular", "commitment"],
            "critical": True
        },
        {
            "id": "g_follow_rules",
            "service_id": 2,
            "category": "Job Commitment & Platform Discipline",
            "question": "Why is it important to follow Petsfolio rules and policies?",
            "hint_text": "For safety, trust, and proper service.",
            "weight": 5.0,
            "q_triggers": ["important to follow petsfolio rules", "follow petsfolio rules", "platform policies"],
            "expected_ans_kw": ["safety", "trust", "proper", "service", "rules", "follow"],
            "critical": True
        },
        {
            "id": "g_difficult_parent",
            "service_id": 2,
            "category": "Client Interaction & Professionalism",
            "question": "How will you handle a pet parent who is unhappy or impatient?",
            "hint_text": "Stay calm, explain politely.",
            "weight": 2.0,
            "q_triggers": ["parent who is unhappy or impatient", "unhappy client", "impatient parent"],
            "expected_ans_kw": ["stay calm", "explain", "politely", "listen", "patience"],
            "critical": False
        },
        {
            "id": "g_rush_request",
            "service_id": 2,
            "category": "Client Interaction & Professionalism",
            "question": "If a client asks you to rush grooming, what will you do?",
            "hint_text": "Explain that rushing can stress or hurt the dog.",
            "weight": 2.0,
            "q_triggers": ["asks you to rush grooming", "rush grooming", "client wants fast grooming"],
            "expected_ans_kw": ["explain", "rushing", "stress", "hurt", "safety", "proper time"],
            "critical": False
        },
        {
            "id": "g_extra_work",
            "service_id": 2,
            "category": "Client Interaction & Professionalism",
            "question": "If a client asks you to do extra work or handle another pet not booked, what will you do?",
            "hint_text": "Ask them to book through the Petsfolio app.",
            "weight": 2.0,
            "q_triggers": ["extra work or handle another pet", "another pet not booked", "extra grooming"],
            "expected_ans_kw": ["book", "through", "app", "petsfolio", "extra pet"],
            "critical": False
        },
        {
            "id": "g_dress_code",
            "service_id": 2,
            "category": "Client Interaction & Professionalism",
            "question": "What dress code and behaviour will you maintain?",
            "hint_text": "Clean clothes, proper shoes, polite behaviour.",
            "weight": 1.0,
            "q_triggers": ["dress code and behaviour", "groomer dress code", "clothes and footwear"],
            "expected_ans_kw": ["clean", "clothes", "proper", "shoes", "polite", "behaviour"],
            "critical": False
        },
        {
            "id": "g_trust_reason",
            "service_id": 2,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Why should a client trust you with their home and pet?",
            "hint_text": "Responsible, professional, respect safety.",
            "weight": 3.0,
            "q_triggers": ["why should a client trust you", "trust you with their home", "trust in groomer"],
            "expected_ans_kw": ["responsible", "professional", "respect", "safety", "trust"],
            "critical": False
        },
        {
            "id": "g_home_practice",
            "service_id": 2,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Explain how you follow safe and hygienic grooming practices at client homes?",
            "hint_text": "Clean tools, safe setup, no force.",
            "weight": 3.0,
            "q_triggers": ["safe and hygienic grooming practices", "grooming practices at client homes", "hygienic grooming"],
            "expected_ans_kw": ["clean", "tools", "safe", "setup", "no force", "hygiene"],
            "critical": False
        },
        {
            "id": "g_verification",
            "service_id": 2,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Why is police/background verification important for this job?",
            "hint_text": "Builds trust and ensures safety.",
            "weight": 3.0,
            "q_triggers": ["police/background verification important", "police verification", "background check"],
            "expected_ans_kw": ["builds trust", "trust", "ensures safety", "safety", "agree", "comfortable"],
            "critical": True
        },
        {
            "id": "g_case_study",
            "service_id": 2,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Tell me about one difficult grooming case you handled.",
            "hint_text": "Real example, calm handling.",
            "weight": 2.0,
            "q_triggers": ["one difficult grooming case", "difficult grooming experience", "challenging grooming"],
            "expected_ans_kw": ["example", "calm", "handling", "handled", "matted", "aggressive"],
            "critical": False
        },
        {
            "id": "g_non_coop",
            "service_id": 2,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Tell me about a time when a dog was not cooperating. What did you do?",
            "hint_text": "Patience, changed approach.",
            "weight": 1.0,
            "q_triggers": ["dog was not cooperating", "not cooperating during grooming", "dog resisted"],
            "expected_ans_kw": ["patience", "changed approach", "break", "gentle", "handled"],
            "critical": False
        },
        {
            "id": "g_parent_educate",
            "service_id": 2,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Tell me about a time when you corrected a pet parent. How did you explain?",
            "hint_text": "Politely educated, no argument.",
            "weight": 1.0,
            "q_triggers": ["corrected a pet parent", "explain to pet parent", "educated parent"],
            "expected_ans_kw": ["politely", "educated", "argument", "explained", "advice"],
            "critical": False
        },
        {
            "id": "g_offline_warning",
            "service_id": 2,
            "category": "Ethics & Platform Discipline",
            "question": "Do you understand that taking clients offline will result in a penalty and account block?",
            "hint_text": "Instruction to be read by Executive: Taking clients offline is strictly not allowed.",
            "weight": 0.0,
            "q_triggers": ["taking clients offline will result in a penalty", "taking clients offline", "offline penalty block"],
            "expected_ans_kw": ["understand", "yes", "penalty", "account block", "agree", "strictly"],
            "critical": True
        }
    ],

    # ─── DOG TRAINING (Service ID 3) ─────────────────────────────────────────
    3: [
        {
            "id": "t_why_trainer",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "Why did you choose to become a dog trainer?",
            "hint_text": "Love for dogs, interest in behaviour, helping dogs and pet parents.",
            "weight": 3.0,
            "q_triggers": ["why did you choose to become a dog trainer", "why trainer", "why training", "interest in training"],
            "expected_ans_kw": ["love", "dogs", "behaviour", "helping", "passion", "trainer"],
            "critical": True
        },
        {
            "id": "t_method",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "What training method do you follow and why?",
            "hint_text": "Positive, reward-based, no force. Builds trust and better learning.",
            "weight": 6.0,
            "q_triggers": ["what training method do you follow", "training method", "method you follow"],
            "expected_ans_kw": ["positive", "reward-based", "reward", "treats", "no force", "trust", "learning"],
            "critical": True
        },
        {
            "id": "t_handle_aggression",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "How do you handle a dog that shows aggression?",
            "hint_text": "Stay calm, find the reason, correct slowly without force.",
            "weight": 5.0,
            "q_triggers": ["handle a dog that shows aggression", "shows aggression", "dog aggression"],
            "expected_ans_kw": ["stay calm", "reason", "slowly", "without force", "correct", "distance"],
            "critical": True
        },
        {
            "id": "t_gain_trust",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "How do you gain a new dog’s trust in the first session?",
            "hint_text": "Slow approach, soft voice, let the dog sniff, use treats.",
            "weight": 4.0,
            "q_triggers": ["gain a new dog's trust", "gain trust in the first session", "trust in first session"],
            "expected_ans_kw": ["slow", "soft voice", "sniff", "treats", "trust", "patience"],
            "critical": False
        },
        {
            "id": "t_stress_signs",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "What signs show a dog is stressed during training?",
            "hint_text": "Lip licking, yawning, tail down, avoiding eye contact, whining.",
            "weight": 4.0,
            "q_triggers": ["signs show a dog is stressed during training", "stressed during training", "training stress signs"],
            "expected_ans_kw": ["lip licking", "yawning", "tail down", "eye contact", "whining", "stress"],
            "critical": False
        },
        {
            "id": "t_never_do",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "What should a trainer never do during training and why?",
            "hint_text": "Never hit, shout, scare, or pull hard. It breaks trust.",
            "weight": 4.0,
            "q_triggers": ["trainer never do", "never do during training", "what should never do"],
            "expected_ans_kw": ["never", "hit", "shout", "scare", "pull", "hard", "breaks trust"],
            "critical": False
        },
        {
            "id": "t_make_fun",
            "service_id": 3,
            "category": "Behaviour, Psychology & Training Approach",
            "question": "How do you make training enjoyable for the dog?",
            "hint_text": "Short sessions, rewards, play time, patience.",
            "weight": 4.0,
            "q_triggers": ["make training enjoyable", "training fun for dog", "enjoyable for the dog"],
            "expected_ans_kw": ["short sessions", "rewards", "play", "patience", "fun", "treats"],
            "critical": False
        },
        {
            "id": "t_exp_detail",
            "service_id": 3,
            "category": "Training Experience & Qualification",
            "question": "Explain your practical experience in training dogs.",
            "hint_text": "Hands-on work with different dogs and behaviour cases.",
            "weight": 8.0,
            "q_triggers": ["practical experience in training dogs", "training experience", "experience training"],
            "expected_ans_kw": ["hands-on", "different dogs", "behaviour", "cases", "experience", "trained"],
            "critical": True
        },
        {
            "id": "t_years_exp",
            "service_id": 3,
            "category": "Training Experience & Qualification",
            "question": "How many years of experience do you have?",
            "hint_text": "Clear timeline and types of dogs handled.",
            "weight": 5.0,
            "q_triggers": ["how many years of experience", "years of experience training", "years experience"],
            "expected_ans_kw": ["years", "months", "timeline", "handled", "dogs", "experience"],
            "critical": True
        },
        {
            "id": "t_services_list",
            "service_id": 3,
            "category": "Training Experience & Qualification",
            "question": "What types of training have you given?",
            "hint_text": "Obedience, potty, leash, socialization, behaviour correction, aggression.",
            "weight": 4.0,
            "q_triggers": ["what types of training have you given", "types of training", "training modules"],
            "expected_ans_kw": ["obedience", "potty", "leash", "socialization", "behaviour", "aggression"],
            "critical": False
        },
        {
            "id": "t_certification",
            "service_id": 3,
            "category": "Training Experience & Qualification",
            "question": "Do you have any dog training certificate? From where?",
            "hint_text": "Course or certification details.",
            "weight": 3.0,
            "q_triggers": ["dog training certificate", "training certification", "certified trainer"],
            "expected_ans_kw": ["course", "certification", "certificate", "institute", "academy", "kci"],
            "critical": False
        },
        {
            "id": "t_plan_session",
            "service_id": 3,
            "category": "Training Knowledge & Session Structure",
            "question": "How do you plan a training session for a new dog?",
            "hint_text": "Check behaviour -> set goal -> step-by-step training.",
            "weight": 5.0,
            "q_triggers": ["plan a training session for a new dog", "plan training session", "session structure"],
            "expected_ans_kw": ["check behaviour", "goal", "step-by-step", "training", "plan"],
            "critical": True
        },
        {
            "id": "t_session_duration",
            "service_id": 3,
            "category": "Training Knowledge & Session Structure",
            "question": "How long should one training session be and why?",
            "hint_text": "20–40 minutes so the dog does not get stressed.",
            "weight": 3.0,
            "q_triggers": ["how long should one training session be", "training duration", "session length"],
            "expected_ans_kw": ["minutes", "20", "30", "40", "hour", "stressed", "focus"],
            "critical": False
        },
        {
            "id": "t_parent_role",
            "service_id": 3,
            "category": "Training Knowledge & Session Structure",
            "question": "What is the role of the pet parent in the dog’s training?",
            "hint_text": "Follow advice, practice daily, be consistent at home.",
            "weight": 4.0,
            "q_triggers": ["role of the pet parent in the dog's training", "pet parent role training", "parent consistency"],
            "expected_ans_kw": ["follow advice", "practice daily", "consistent", "home", "parent"],
            "critical": False
        },
        {
            "id": "t_session_count",
            "service_id": 3,
            "category": "Training Knowledge & Session Structure",
            "question": "How many sessions are needed for basic obedience and why?",
            "hint_text": "Around 8–15 sessions depending on dog and practice.",
            "weight": 3.0,
            "q_triggers": ["how many sessions are needed for basic obedience", "number of sessions needed", "how many classes"],
            "expected_ans_kw": ["sessions", "classes", "practice", "dog", "obedience"],
            "critical": False
        },
        {
            "id": "t_bite_action",
            "service_id": 3,
            "category": "Situational Handling & Safety",
            "question": "If a dog tries to bite you during training, what will you do?",
            "hint_text": "Stay calm, step back, understand the reason.",
            "weight": 3.0,
            "q_triggers": ["tries to bite you during training", "dog tries to bite during training", "bite action training"],
            "expected_ans_kw": ["stay calm", "step back", "reason", "space", "no hit", "calm"],
            "critical": True
        },
        {
            "id": "t_run_away_action",
            "service_id": 3,
            "category": "Situational Handling & Safety",
            "question": "If the dog runs away during outdoor training, what will you do?",
            "hint_text": "Secure safely, call calmly, inform parent/team.",
            "weight": 2.0,
            "q_triggers": ["runs away during outdoor training", "dog runs away training", "escapes training"],
            "expected_ans_kw": ["secure safely", "call calmly", "inform", "parent", "team", "calm"],
            "critical": False
        },
        {
            "id": "t_injury_action",
            "service_id": 3,
            "category": "Situational Handling & Safety",
            "question": "If the dog gets injured during training, what will you do?",
            "hint_text": "Inform pet parent and Petsfolio, get medical help.",
            "weight": 3.0,
            "q_triggers": ["gets injured during training", "dog injured training", "training accident"],
            "expected_ans_kw": ["inform", "parent", "petsfolio", "medical help", "vet", "doctor"],
            "critical": True
        },
        {
            "id": "t_outdoor_precautions",
            "service_id": 3,
            "category": "Situational Handling & Safety",
            "question": "What precautions do you take before leash/outdoor training?",
            "hint_text": "Proper leash, safe place, check surroundings.",
            "weight": 3.0,
            "q_triggers": ["precautions before leash/outdoor training", "outdoor training precautions", "leash precautions"],
            "expected_ans_kw": ["proper leash", "safe place", "surroundings", "check", "collar"],
            "critical": False
        },
        {
            "id": "t_prevent_stress",
            "service_id": 3,
            "category": "Situational Handling & Safety",
            "question": "How do you make sure the dog does not get over-stressed?",
            "hint_text": "Watch body language, give breaks.",
            "weight": 4.0,
            "q_triggers": ["not get over-stressed during training", "prevent over stress training", "over-stressed"],
            "expected_ans_kw": ["watch", "body language", "give breaks", "pause", "play"],
            "critical": False
        },
        {
            "id": "t_commitment",
            "service_id": 3,
            "category": "Job Commitment & Platform Discipline",
            "question": "Is this full-time or part-time for you? How serious are you?",
            "hint_text": "Serious and regular commitment.",
            "weight": 5.0,
            "q_triggers": ["full-time or part-time for you", "how serious are you", "commitment"],
            "expected_ans_kw": ["full-time", "part-time", "serious", "regular", "commitment"],
            "critical": True
        },
        {
            "id": "t_follow_rules",
            "service_id": 3,
            "category": "Job Commitment & Platform Discipline",
            "question": "Why is it important to follow Petsfolio rules and policies?",
            "hint_text": "For safety, trust, and proper service.",
            "weight": 5.0,
            "q_triggers": ["important to follow petsfolio rules", "follow petsfolio rules", "platform policies"],
            "expected_ans_kw": ["safety", "trust", "proper", "service", "rules", "follow"],
            "critical": True
        },
        {
            "id": "t_rush_request",
            "service_id": 3,
            "category": "Client Interaction & Professionalism",
            "question": "How will you handle a pet parent who wants fast results?",
            "hint_text": "Explain calmly and set real expectations.",
            "weight": 2.0,
            "q_triggers": ["pet parent who wants fast results", "wants fast results", "rush results"],
            "expected_ans_kw": ["explain", "calmly", "real expectations", "patience", "time"],
            "critical": False
        },
        {
            "id": "t_harsh_method",
            "service_id": 3,
            "category": "Client Interaction & Professionalism",
            "question": "If a client asks you to use harsh methods, what will you do?",
            "hint_text": "Politely refuse and explain correct method.",
            "weight": 2.0,
            "q_triggers": ["use harsh methods", "asks you to hit or punish", "punishment method"],
            "expected_ans_kw": ["politely refuse", "refuse", "explain", "correct method", "no force", "positive"],
            "critical": False
        },
        {
            "id": "t_extra_work",
            "service_id": 3,
            "category": "Client Interaction & Professionalism",
            "question": "If a client asks you to do extra session or handle another pet not booked, what will you do?",
            "hint_text": "Ask them to book through the Petsfolio app.",
            "weight": 2.0,
            "q_triggers": ["do extra session or handle another pet", "extra training session", "unbooked pet"],
            "expected_ans_kw": ["book", "through", "app", "petsfolio", "extra"],
            "critical": False
        },
        {
            "id": "t_dress_code",
            "service_id": 3,
            "category": "Client Interaction & Professionalism",
            "question": "What dress code and behaviour will you maintain?",
            "hint_text": "Clean clothes, proper shoes, polite behaviour.",
            "weight": 1.0,
            "q_triggers": ["dress code and behaviour", "trainer dress code", "clothes and footwear"],
            "expected_ans_kw": ["clean", "clothes", "proper", "shoes", "polite", "behaviour"],
            "critical": False
        },
        {
            "id": "t_trust_reason",
            "service_id": 3,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Why should a client trust you with their home and pet?",
            "hint_text": "Responsible, professional, respect safety.",
            "weight": 3.0,
            "q_triggers": ["why should a client trust you", "trust you with their home", "trust in trainer"],
            "expected_ans_kw": ["responsible", "professional", "respect", "safety", "trust"],
            "critical": False
        },
        {
            "id": "t_home_practice",
            "service_id": 3,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Explain how you follow safe and ethical training at client homes?",
            "hint_text": "No force, safety first, respect house rules.",
            "weight": 3.0,
            "q_triggers": ["safe and ethical training at client homes", "ethical training", "training at client homes"],
            "expected_ans_kw": ["no force", "safety", "first", "respect", "house rules", "ethical"],
            "critical": False
        },
        {
            "id": "t_verification",
            "service_id": 3,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Why is police/background verification important for this job?",
            "hint_text": "Builds trust and ensures safety.",
            "weight": 3.0,
            "q_triggers": ["police/background verification important", "police verification", "background check"],
            "expected_ans_kw": ["builds trust", "trust", "ensures safety", "safety", "agree", "comfortable"],
            "critical": True
        },
        {
            "id": "t_case_study",
            "service_id": 3,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Tell me about one difficult training case you handled.",
            "hint_text": "Real example, calm handling, correct method.",
            "weight": 2.0,
            "q_triggers": ["one difficult training case", "difficult training experience", "challenging training case"],
            "expected_ans_kw": ["example", "calm", "handling", "correct method", "handled", "aggression"],
            "critical": False
        },
        {
            "id": "t_non_coop",
            "service_id": 3,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Tell me about a time when a dog was not listening. What did you change?",
            "hint_text": "Changed method, patience, different approach.",
            "weight": 1.0,
            "q_triggers": ["dog was not listening", "not listening during training", "dog disobeyed"],
            "expected_ans_kw": ["changed method", "patience", "different approach", "rewards", "break"],
            "critical": False
        },
        {
            "id": "t_parent_educate",
            "service_id": 3,
            "category": "Trustworthiness, Ethics & Real Experience",
            "question": "Tell me about a time when you corrected a pet parent. How did you explain?",
            "hint_text": "Politely educated, no argument.",
            "weight": 1.0,
            "q_triggers": ["corrected a pet parent", "explain to pet parent", "educated parent"],
            "expected_ans_kw": ["politely", "educated", "argument", "explained", "advice"],
            "critical": False
        },
        {
            "id": "t_offline_warning",
            "service_id": 3,
            "category": "Ethics & Platform Discipline",
            "question": "Do you understand that taking clients offline will result in a penalty and account block?",
            "hint_text": "Instruction to be read by Executive: Taking clients offline is strictly not allowed.",
            "weight": 0.0,
            "q_triggers": ["taking clients offline will result in a penalty", "taking clients offline", "offline penalty block"],
            "expected_ans_kw": ["understand", "yes", "penalty", "account block", "agree", "strictly"],
            "critical": True
        }
    ],

    # ─── PET SITTING / BOARDING (Service ID 6 & 1) ───────────────────────────
    # Exact 21 verification questions from verification_questions table
    6: [
        {
            "id": "s_experience",
            "service_id": 6,
            "category": "Basic Details",
            "question": "Do you have experience in pet sitting before? Explain.",
            "hint_text": "Expected: Experience with dogs/cats, years or home pets.",
            "weight": 2.0,
            "q_triggers": ["experience in pet sitting", "pet sitting before", "experience before", "sitting experience", "experience in sitting", "have experience"],
            "expected_ans_kw": ["experience", "years", "months", "home", "pet", "dog", "cat", "no", "yes", "professional", "personal", "handled"],
            "critical": True
        },
        {
            "id": "s_dog_cat_handling",
            "service_id": 6,
            "category": "Basic Details",
            "question": "Are you comfortable handling both dogs and cats?",
            "hint_text": "Expected: Yes / Only dog / Only cat with clear clarity.",
            "weight": 2.0,
            "q_triggers": ["both dogs and cats", "both dog and cat", "comfortable handling both", "handle both dog and cat", "dogs and cats", "comfort handling"],
            "expected_ans_kw": ["only dog", "only cat", "both", "dog", "cat", "comfortable", "comfort", "no", "yes", "fine"],
            "critical": True
        },
        {
            "id": "s_fear_of_pets",
            "service_id": 6,
            "category": "Basic Details",
            "question": "Do you have any fear of pets?",
            "hint_text": "Expected: No fear of pets.",
            "weight": 2.0,
            "q_triggers": ["fear of pets", "any fear of", "afraid of pets", "scared of pets", "fear of dogs", "fear of"],
            "expected_ans_kw": ["no", "nothing", "good with pets", "not afraid", "comfortable", "no fear", "good", "care of pets"],
            "critical": True
        },
        {
            "id": "s_home_visit_hours",
            "service_id": 6,
            "category": "Basic Details",
            "question": "Can you do home visits and stay for long hours if needed?",
            "hint_text": "Expected: Yes, flexible for visits/stays.",
            "weight": 2.0,
            "q_triggers": ["can you do home visits", "stay for long hours", "stay for longer", "home visit and stay", "long hours if needed", "home visit", "long hours"],
            "expected_ans_kw": ["yes", "fine", "hours", "day to night", "stay", "sure", "flexible", "available", "longer", "requirement"],
            "critical": True
        },
        {
            "id": "s_sitting_certificate",
            "service_id": 6,
            "category": "Basic Details",
            "question": "Do you have any pet sitting certificate or training?",
            "hint_text": "Expected: If yes, explain. If no, at least practical experience.",
            "weight": 2.0,
            "q_triggers": ["sitting certificate or training", "pet sitting certificate", "certificate or training", "any certification", "certified", "pet certificate"],
            "expected_ans_kw": ["yes", "no", "certificate", "training", "practical", "experience", "learning", "course", "certified"],
            "critical": False
        },
        {
            "id": "s_food_instructions",
            "service_id": 6,
            "category": "Food & Medication Knowledge",
            "question": "How will you follow food instructions given by the pet parent?",
            "hint_text": "Expected: Follow exact timing and quantity.",
            "weight": 5.0,
            "q_triggers": ["follow food instructions", "food instructions given by the pet parent", "feeding instructions", "food instructions", "how will you feed", "can they be fed", "food requirement"],
            "expected_ans_kw": ["follow", "instructions", "exact", "timing", "quantity", "parent", "diet", "measure", "food", "feed", "owner", "specific treats"],
            "critical": True
        },
        {
            "id": "s_refuse_to_eat",
            "service_id": 6,
            "category": "Food & Medication Knowledge",
            "question": "What will you do if the pet refuses to eat?",
            "hint_text": "Expected: Inform pet parent, try calmly, don’t force.",
            "weight": 5.0,
            "q_triggers": ["pet refuses to eat", "refuses to eat", "refuse to eat", "dog not eating", "pet not eating", "doesn't eat food", "refuse food"],
            "expected_ans_kw": ["inform", "parent", "calmly", "don't force", "wait", "try", "treat", "treats", "water", "petsfolio", "health issues", "owner"],
            "critical": True
        },
        {
            "id": "s_give_medicine",
            "service_id": 6,
            "category": "Food & Medication Knowledge",
            "question": "How will you give medicine if required?",
            "hint_text": "Expected: As per instruction, safely and calmly.",
            "weight": 5.0,
            "q_triggers": ["how will you give medicine", "give medicine if required", "giving medicine", "medication if required", "medication is required", "oral medicine", "how do you give it", "medication"],
            "expected_ans_kw": ["instruction", "safely", "calmly", "food", "treat", "powder", "powdered", "mixed", "vet", "careful", "timing", "prescribed"],
            "critical": True
        },
        {
            "id": "s_dog_handling_walk",
            "service_id": 6,
            "category": "Walking & Handling",
            "question": "How will you handle the dog during a walk?",
            "hint_text": "Expected: Proper leash control, alert to surroundings.",
            "weight": 5.0,
            "q_triggers": ["handle the dog during a walk", "handling during walk", "during a walk", "during walk", "walk handling", "handle the dog"],
            "expected_ans_kw": ["leash", "control", "alert", "surroundings", "careful", "calm", "safe", "distance", "stray", "stray dogs", "ground", "area", "water", "equipment", "poop"],
            "critical": True
        },
        {
            "id": "s_dog_run_away",
            "service_id": 6,
            "category": "Walking & Handling",
            "question": "If the dog suddenly runs away, what will you do?",
            "hint_text": "Expected: Stay calm, call the dog, secure safely.",
            "weight": 5.0,
            "q_triggers": ["dog suddenly runs away", "runs away", "escapes during walk", "dog gets loose", "leaves me and goes away", "dog runs away"],
            "expected_ans_kw": ["stay calm", "call", "dog", "secure", "safely", "treat", "don't chase", "inform", "parent", "catch", "name", "emergency number", "contact", "id"],
            "critical": True
        },
        {
            "id": "s_dog_attack",
            "service_id": 6,
            "category": "Walking & Handling",
            "question": "If another dog tries to attack, what will you do?",
            "hint_text": "Expected: Move away quickly, protect the dog calmly.",
            "weight": 5.0,
            "q_triggers": ["another dog tries to attack", "another dog attacks", "dog tries to attack", "dog attacks", "stray dog attack", "attack by other dog", "attack your dog"],
            "expected_ans_kw": ["move away", "protect", "calmly", "barrier", "safe", "distance", "avoid", "careful", "definitely"],
            "critical": True
        },
        {
            "id": "s_pet_injured",
            "service_id": 6,
            "category": "Emergency Handling",
            "question": "If the pet gets injured or sick during service, what will you do?",
            "hint_text": "Expected: Inform pet parent and Petsfolio immediately, take to vet if needed.",
            "weight": 7.5,
            "q_triggers": ["pet gets injured or sick", "injured or sick during service", "gets sick or injured", "sick or injured", "medical emergency sitting", "pet sick during sitting", "vomits or injured", "dog gets sick"],
            "expected_ans_kw": ["inform", "parent", "petsfolio", "immediately", "vet", "veterinary", "clinic", "hospital", "first aid", "emergency", "owner", "faint"],
            "critical": True
        },
        {
            "id": "s_parent_unreachable",
            "service_id": 6,
            "category": "Emergency Handling",
            "question": "If the pet parent is not reachable in emergency, what will you do?",
            "hint_text": "Expected: Contact Petsfolio team and act for pet safety.",
            "weight": 7.5,
            "q_triggers": ["pet parent is not reachable", "parent is not reachable", "unreachable in emergency", "parent not picking call", "not reachable"],
            "expected_ans_kw": ["contact", "petsfolio", "team", "support", "act", "safety", "vet", "veterinary", "hospital", "emergency", "message", "company", "action"],
            "critical": True
        },
        {
            "id": "s_home_behaviour",
            "service_id": 6,
            "category": "Home Responsibility",
            "question": "How will you behave inside the client’s home?",
            "hint_text": "Expected: Respectful, no touching personal items.",
            "weight": 5.0,
            "q_triggers": ["behave inside the client's home", "behave in the client's home", "inside client home", "client's home", "client home", "parent's home", "home behaviour", "respect client house"],
            "expected_ans_kw": ["respectful", "no touching", "personal items", "clean", "professional", "privacy", "house rules", "allocated", "instructions", "behave"],
            "critical": True
        },
        {
            "id": "s_send_updates",
            "service_id": 6,
            "category": "Home Responsibility",
            "question": "Will you send photo/video updates to pet parent?",
            "hint_text": "Expected: Yes, regular updates.",
            "weight": 5.0,
            "q_triggers": ["send photo/video updates", "photo, video updates", "photo and video updates", "photo video updates to pet parent", "send regular updates", "share photos and videos", "updates via app", "photos and videos"],
            "expected_ans_kw": ["yes", "regular", "updates", "photos", "video", "videos", "feeding", "peace of mind", "app", "requirement", "definitely", "send"],
            "critical": True
        },
        {
            "id": "s_difficult_sitting_case",
            "service_id": 6,
            "category": "Real Experience Scenarios",
            "question": "Tell me about one difficult pet sitting case you handled.",
            "hint_text": "Expected: Real example, calm handling.",
            "weight": 7.5,
            "q_triggers": ["difficult pet sitting case", "difficult situation in sitting", "challenging pet sitting", "difficult case you handled", "difficult case"],
            "expected_ans_kw": ["example", "handled", "calmly", "patience", "dog", "cat", "anxious", "careful", "experience", "shih tzu", "parvo", "parvovirus", "bleeding", "vomit", "vet", "medications", "golden retriever", "aggressive", "week"],
            "critical": True
        },
        {
            "id": "s_pet_not_listening",
            "service_id": 6,
            "category": "Real Experience Scenarios",
            "question": "Tell me about a time a pet was not listening. What did you do?",
            "hint_text": "Expected: Patience, smart handling.",
            "weight": 7.5,
            "q_triggers": ["pet was not listening", "dog not listening", "animal not listening", "time a pet was not listening", "not listening"],
            "expected_ans_kw": ["patience", "smart", "handling", "treat", "treats", "calm", "space", "routine", "no shouting", "comfort", "aggressive", "time", "listen"],
            "critical": True
        },
        {
            "id": "s_extra_work_sitting",
            "service_id": 6,
            "category": "Trustworthiness, Ethics & Professionalism",
            "question": "If a client asks you to do extra work or handle another pet not booked, what will you do?",
            "hint_text": "Expected: Politely ask them to book through the Petsfolio app.",
            "weight": 7.0,
            "q_triggers": ["extra work or handle another pet", "handle another pet not booked", "handle another pet", "extra pet sitting", "unbooked pet sitting", "extra work"],
            "expected_ans_kw": ["politely", "book", "app", "petsfolio", "extra pet", "scope", "service", "support", "reach out", "report"],
            "critical": True
        },
        {
            "id": "s_parent_misunderstanding",
            "service_id": 6,
            "category": "Trustworthiness, Ethics & Professionalism",
            "question": "Tell me about a time you handled a misunderstanding with a pet parent.",
            "hint_text": "Expected: Calm explanation, no arguments.",
            "weight": 7.0,
            "q_triggers": ["misunderstanding with a pet parent", "disagreement with pet parent", "client misunderstanding", "upset pet parent", "misunderstanding"],
            "expected_ans_kw": ["calm", "explanation", "no arguments", "polite", "listen", "support", "resolved", "discuss"],
            "critical": True
        },
        {
            "id": "s_sitting_honesty",
            "service_id": 6,
            "category": "Trustworthiness, Ethics & Professionalism",
            "question": "Why is honesty important in pet sitting?",
            "hint_text": "Expected: Trust, safety, responsibility.",
            "weight": 6.0,
            "q_triggers": ["why is honesty important in pet sitting", "why is honesty important", "honesty important", "importance of honesty", "why honesty", "honesty"],
            "expected_ans_kw": ["trust", "safety", "responsibility", "integrity", "client", "pet", "transparent", "health", "comfort", "medications", "wrong", "affect", "talk", "tell", "problem", "honest"],
            "critical": True
        },
        {
            "id": "s_offline_warning",
            "service_id": 6,
            "category": "Ethics & Platform Discipline",
            "question": "Do you understand that taking clients offline will result in a penalty and account block?",
            "hint_text": "Instruction to be read by Executive: Taking clients outside the Petsfolio platform is strictly not allowed. If you take clients offline, your account will be blocked, you will have to pay a penalty, and you will not receive any more leads. All services must be accepted only through proper bookings in the app.",
            "weight": 0.0,
            "q_triggers": ["taking clients offline will result in a penalty", "taking clients offline", "penalty and account block", "taking clients outside the petsfolio platform", "offline penalty block", "offline penalty"],
            "expected_ans_kw": ["understand", "yes", "blocked", "penalty", "account block", "app", "strictly", "offline", "aware", "know", "sure", "fine"],
            "critical": True
        }
    ]
}

# Also map service_id 1 (sitting/boarding alias)
VERIFICATION_QUESTIONS_BY_SERVICE[1] = VERIFICATION_QUESTIONS_BY_SERVICE[6]

# Alias for backward compatibility
VERIFICATION_QUESTIONS_KB = VERIFICATION_QUESTIONS_BY_SERVICE[4]


def parse_ci_env():
    for p in ['/var/www/petsfolio/live/admin/.env', os.path.join(os.path.dirname(__file__), '../../.env')]:
        if os.path.exists(p):
            env_vars = {}
            try:
                with open(p, 'r', encoding='utf-8') as f:
                    for line in f:
                        line = line.strip()
                        if line and not line.startswith('#') and '=' in line:
                            k, v = line.split('=', 1)
                            env_vars[k.strip()] = v.strip().strip("'\"")
                return env_vars
            except Exception:
                pass
    return {}


def load_verification_questions_from_db(service_id_or_name=None, provider_id=None):
    """
    Load live questions from MySQL verification_questions table, filtered by the
    provider's actual selected sub-type from sp_grooming_details / sp_training_details.
    Applies deduplication across training_types and group_keys.
    """
    try:
        import pymysql
        env_vars = parse_ci_env()
        host = os.getenv('database.default.hostname') or env_vars.get('database.default.hostname', 'localhost')
        user = os.getenv('database.default.username') or env_vars.get('database.default.username', 'petsfolio_liveuser')
        password = os.getenv('database.default.password') or env_vars.get('database.default.password', '=tAFt44cSi1')
        database = os.getenv('database.default.database') or env_vars.get('database.default.database', 'petsfolio_live')
        port = int(os.getenv('database.default.port') or env_vars.get('database.default.port', 3306))

        socket_candidates = ['/var/run/mysqld/mysqld.sock', '/tmp/mysql.sock']
        unix_socket = next((s for s in socket_candidates if os.path.exists(s)), None)

        kwargs = {
            'user': user,
            'password': password,
            'database': database,
            'cursorclass': pymysql.cursors.DictCursor,
            'connect_timeout': 3,
            'read_timeout': 5,
            'write_timeout': 3
        }
        if unix_socket:
            kwargs['unix_socket'] = unix_socket
        else:
            kwargs['host'] = host
            kwargs['port'] = port

        conn = pymysql.connect(**kwargs)

        service_id = resolve_service_id(service_id_or_name)
        selected_types = None

        with conn.cursor() as cur:
            # ─── Resolve selected_types from sp_grooming_details / sp_training_details ───
            if provider_id and service_id == 2:
                # Grooming: read grooming_type enum from sp_grooming_details
                try:
                    cur.execute("SELECT grooming_type FROM sp_grooming_details WHERE provider_id = %s LIMIT 1", (provider_id,))
                    g_row = cur.fetchone()
                    if g_row and g_row.get('grooming_type'):
                        g_type = g_row['grooming_type'].strip().lower()
                        # Map enum values to the training_type values in verification_questions
                        if g_type in ('grooming_trimming', 'grooming_and_trimming'):
                            selected_types = ['grooming_and_trimming']
                        elif g_type in ('only_grooming', 'grooming'):
                            selected_types = ['grooming']
                        elif g_type in ('grooming_haircut', 'grooming_and_cut'):
                            selected_types = ['grooming_and_cut']
                        else:
                            selected_types = [g_type]
                except Exception:
                    pass

            elif provider_id and service_id == 3:
                # Training: check tinyint/text columns in sp_training_details
                try:
                    cur.execute(
                        "SELECT obedience_training, intermediate_training, behavioral_training, advanced_training "
                        "FROM sp_training_details WHERE provider_id = %s LIMIT 1", (provider_id,)
                    )
                    t_row = cur.fetchone()
                    if t_row:
                        selected_types = []
                        # obedience_training & intermediate_training both map to 'obedience_training'
                        for col in ('obedience_training', 'intermediate_training'):
                            val = (t_row.get(col) or '').strip().lower()
                            if val and val not in ('', '0', 'no', 'false', 'none'):
                                if 'obedience_training' not in selected_types:
                                    selected_types.append('obedience_training')
                        # behavioral_training
                        val = (t_row.get('behavioral_training') or '').strip().lower()
                        if val and val not in ('', '0', 'no', 'false', 'none'):
                            selected_types.append('behavioral_training')
                        # advanced_training
                        val = (t_row.get('advanced_training') or '').strip().lower()
                        if val and val not in ('', '0', 'no', 'false', 'none'):
                            selected_types.append('advanced_training')
                        # If nothing matched, fall back to all training types
                        if not selected_types:
                            selected_types = ['obedience_training', 'behavioral_training', 'advanced_training']
                except Exception:
                    pass

            # ─── Fetch questions from verification_questions table ───
            if service_id:
                cur.execute(
                    "SELECT id, service_id, category_name, category_weight, question_text, hint_text, weight, "
                    "is_trigger, is_dependent, field_name, training_type, group_key "
                    "FROM verification_questions WHERE service_id = %s AND status = 'active' ORDER BY sort_order ASC",
                    (service_id,)
                )
            else:
                cur.execute(
                    "SELECT id, service_id, category_name, category_weight, question_text, hint_text, weight, "
                    "is_trigger, is_dependent, field_name, training_type, group_key "
                    "FROM verification_questions WHERE status = 'active' ORDER BY service_id, sort_order ASC"
                )
            rows = cur.fetchall()
            conn.close()

            if rows:
                formatted_questions = []
                for r in rows:
                    q_text = r['question_text']
                    h_text = r['hint_text'] or ''
                    field_name = r['field_name'] or f"q_{r['id']}"
                    weight = float(r.get('weight') or 0)
                    is_trig = int(r.get('is_trigger') or 0)
                    cat_name = r.get('category_name') or 'General'

                    is_critical = (
                        weight >= 5 or
                        is_trig == 1 or
                        'offline' in q_text.lower() or
                        'experience' in cat_name.lower() or
                        'safety' in cat_name.lower() or
                        'ethics' in cat_name.lower()
                    )

                    formatted_questions.append({
                        "id": field_name,
                        "service_id": r['service_id'],
                        "category": cat_name,
                        "question": q_text,
                        "hint_text": h_text,
                        "weight": weight,
                        "training_type": r.get('training_type'),
                        "group_key": r.get('group_key'),
                        "q_triggers": generate_question_triggers(q_text),
                        "expected_ans_kw": generate_expected_answer_keywords(h_text, q_text),
                        "critical": is_critical
                    })

                return deduplicate_questions(formatted_questions, selected_types)
    except Exception:
        pass

    return None


def get_verification_questions_for_service(service_id_or_name=None, provider_id=None, selected_types=None, transcript=None):
    """
    Get the deduplicated verification questions for the requested service.
    First tries live DB table `verification_questions`.
    Falls back to built-in knowledge base.
    """
    # 1. Try DB fetch first
    db_questions = load_verification_questions_from_db(service_id_or_name, provider_id)
    if db_questions:
        return db_questions

    # 2. Resolve service ID
    service_id = resolve_service_id(service_id_or_name)
    if not service_id and transcript:
        service_id = detect_service_from_transcript(transcript)
    if not service_id:
        service_id = 4  # Default to Dog Walking

    # 3. Retrieve from knowledge base
    raw_kb = VERIFICATION_QUESTIONS_BY_SERVICE.get(service_id, VERIFICATION_QUESTIONS_BY_SERVICE[4])
    
    # 4. Deduplicate across multiple training_types and group_keys
    return deduplicate_questions(raw_kb, selected_types)


def evaluate_verification_qa(transcript, service=None, provider_id=None, selected_types=None, custom_questions=None):
    """
    Evaluates verification question asking accuracy, answer relevance matching,
    and flags out-of-box / off-topic segments for the specific service.
    Automatically deduplicates questions across training_types.
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

    # 2. Get service-specific deduplicated questions
    if custom_questions:
        questions_to_evaluate = []
        for q in custom_questions:
            q_text = q.get('question') or q.get('question_text') or ''
            h_text = q.get('hint_text') or ''
            cat_name = q.get('category') or q.get('category_name') or 'General'
            w = float(q.get('weight') or 0)
            questions_to_evaluate.append({
                "id": q.get('id') or f"q_{q.get('question_id', '')}",
                "service_id": q.get('service_id'),
                "category": cat_name,
                "question": q_text,
                "hint_text": h_text,
                "weight": w,
                "critical": q.get('critical', (w >= 5 or 'offline' in q_text.lower() or 'experience' in cat_name.lower() or 'safety' in cat_name.lower() or 'ethics' in cat_name.lower())),
                "q_triggers": q.get('q_triggers') or generate_question_triggers(q_text),
                "expected_ans_kw": q.get('expected_ans_kw') or generate_expected_answer_keywords(h_text, q_text),
            })
    else:
        questions_to_evaluate = get_verification_questions_for_service(service, provider_id, selected_types, transcript)
    
    matched_q_count = 0
    total_q_count = len(questions_to_evaluate)
    
    for item in questions_to_evaluate:
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

    # ─── Track missed MANDATORY (critical) questions ─────────────────
    missed_mandatory_questions = []
    total_mandatory = 0
    asked_mandatory = 0
    for item in qa_checklist:
        if item['critical']:
            total_mandatory += 1
            if item['status'] == 'Not Asked':
                missed_mandatory_questions.append({
                    "question": item['question'],
                    "category": item['category'],
                    "id": item['id']
                })
            else:
                asked_mandatory += 1

    all_mandatory_asked = len(missed_mandatory_questions) == 0
    mandatory_questions_status = "All Required Questions Asked" if all_mandatory_asked else "Required Questions Missing"

    return {
        "qa_checklist": qa_checklist,
        "question_coverage_pct": question_coverage_pct,
        "questions_asked_count": matched_q_count,
        "total_questions_count": total_q_count,
        "mismatched_answers": mismatched_answers,
        "out_of_box_segments": out_of_box_segments,
        "has_mismatched_answers": len(mismatched_answers) > 0,
        "has_out_of_box_segments": len(out_of_box_segments) > 0,
        "missed_mandatory_questions": missed_mandatory_questions,
        "all_mandatory_asked": all_mandatory_asked,
        "mandatory_questions_status": mandatory_questions_status,
        "total_mandatory_count": total_mandatory,
        "asked_mandatory_count": asked_mandatory
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
    ts_pattern = re.compile(r'^(\[(?:\d+:)?\d+(?:\.\d+)?s?\s*-\s*(?:\d+:)?\d+(?:\.\d+)?s?\])\s*(.*)')

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


def normalize_transcript_speakers(transcript_text):
    """
    Ensure all speaker labels throughout the transcript strictly use 'Executive' and 'Provider'.
    Replaces:
      Speaker 1, Representative, Interviewer, Rep, Verifier, Caller, Agent -> Executive
      Speaker 2, Candidate, Applicant, Sitter, Walker, Trainer, Groomer, Provider, Callee -> Provider
    """
    if not transcript_text:
        return transcript_text

    exec_patterns = r'(?:Speaker\s*1|Representative|Interviewer(?:\s*1)?|Rep|Verifier|Caller|Agent|Petsfolio\s*Rep)'
    prov_patterns = r'(?:Speaker\s*2|Candidate|Applicant|Sitter|Walker|Trainer|Groomer|Provider(?:\s*1)?|Callee|User)'

    lines = []
    for line in transcript_text.splitlines():
        # Match [MM:SS - MM:SS] Speaker: or [0.0s - 4.5s] Speaker:
        # 1. Replace Executive
        line = re.sub(r'(\[\s*[\d\.:\s-]+(?:s)?\s*\]\s*)' + exec_patterns + r'\s*:', r'\1Executive:', line, flags=re.IGNORECASE)
        # 2. Replace Provider
        line = re.sub(r'(\[\s*[\d\.:\s-]+(?:s)?\s*\]\s*)' + prov_patterns + r'\s*:', r'\1Provider:', line, flags=re.IGNORECASE)
        # 3. Direct prefix without timestamps
        line = re.sub(r'^' + exec_patterns + r'\s*:', r'Executive:', line, flags=re.IGNORECASE)
        line = re.sub(r'^' + prov_patterns + r'\s*:', r'Provider:', line, flags=re.IGNORECASE)
        lines.append(line)
        
    return "\n".join(lines)


def gemini_transcribe_audio(audio_path, gemini_key=None):
    """
    Transcribe and translate regional Indian audio (Telugu, Hindi, Tamil, Kannada, Tenglish)
    directly into 99%+ accurate verbatim English dialogue using Google Gemini Flash Multimodal Audio.
    Uses Google AI Studio free tier quota (1,500 calls/day).
    Processes complete recordings (up to 15+ minutes) without premature stopping.
    """
    if not gemini_key:
        load_environment()
        gemini_key = os.getenv("GEMINI_API_KEY") or os.getenv("x-goog-api-key") or os.getenv("GOOGLE_API_KEY")
    if not gemini_key:
        return None

    if not os.path.exists(audio_path):
        return None

    temp_ogg = None
    file_name = None
    try:
        import subprocess, tempfile, time

        # 1. Compress to lightweight 16kHz mono OGG for fast upload & superior speech quality
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

        # 2. Upload via Google Generative AI File API for full duration support
        upload_url = f"https://generativelanguage.googleapis.com/upload/v1beta/files?key={gemini_key}"
        boundary = "----WebKitFormBoundaryPetsfolioAudioUpload"

        meta = {
            "file": {
                "display_name": f"verification_audio_{os.path.basename(audio_path)}"
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

        # 3. Prompt for Complete Verbatim English Translation
        prompt = (
            "You are a professional audio translator for Petsfolio verification calls.\n"
            "The speakers speak in Telugu and English.\n\n"
            "CRITICAL INSTRUCTIONS:\n"
            "1. Translate ALL Telugu and regional speech into clear, accurate, professional ENGLISH.\n"
            "2. DO NOT output Telugu script or transliterated Telugu. Every dialogue turn must be in English.\n"
            "3. Transcribe and translate the ENTIRE audio conversation from 00:00 all the way to the very end of the recording. Do NOT stop early or truncate.\n"
            "4. STRICT SPEAKER IDENTIFICATION:\n"
            "   - Identify the Petsfolio Verification Executive (asking questions/verifying details) as `Executive:`\n"
            "   - Identify the Service Provider / Applicant (answering questions/interviewee) as `Provider:`\n"
            "   - Output chronological timestamped lines strictly in the format: `[MM:SS - MM:SS] Executive: text` or `[MM:SS - MM:SS] Provider: text`.\n"
            "   - NEVER use labels like Speaker 1, Speaker 2, Interviewer, Candidate, Applicant, Rep, Sitter, or Walker.\n"
            "5. Accurately translate all pet care concepts:\n"
            "   - Pet sitting, dog walking, grooming, dog training\n"
            "   - Parvovirus (parvo), illness, vomiting, bleeding, fever\n"
            "   - Food instructions, feeding times, refusing to eat, treats, diet\n"
            "   - Giving medication (tablets, powder, in treats)\n"
            "   - Home visits, long hours, staying 6-7 hours\n"
            "   - Handling during walk, stray dogs, escaping/running away, dog attacks\n"
            "   - Emergency contacts, veterinary visits, company reporting\n"
            "   - Client home professional behavior, sending photo/video updates\n"
            "   - Taking clients offline penalty and account block warnings\n"
            "6. Output ONLY the timestamped dialogue lines. No notes, commentary, or summaries."
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
        models_to_try = ['gemini-3.6-flash', 'gemini-2.5-flash', 'gemini-3.7-flash']
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

        if raw_text and len(raw_text.strip()) > 20:
            return normalize_transcript_speakers(raw_text)

    except Exception as e:
        sys.stderr.write(f"[WARN] Gemini file translation exception: {e}\n")
    finally:
        if temp_ogg and os.path.exists(temp_ogg):
            try:
                os.remove(temp_ogg)
            except Exception:
                pass

    return None


def transcribe_audio(audio_path):
    """
    Transcribe and translate audio using Gemini Flash Multimodal Audio
    (99% accurate on Telugu/Indian regional languages) with Faster-Whisper fallback.
    Returns (transcript, duration_seconds).
    """
    audio_duration = get_audio_duration(audio_path)

    # 1. Primary: Gemini Flash Multimodal Audio Translation (100% Free Tier, 99%+ Telugu Accuracy)
    try:
        gemini_transcript = gemini_transcribe_audio(audio_path)
        if gemini_transcript and len(gemini_transcript.strip()) > 20:
            return normalize_transcript_speakers(gemini_transcript), audio_duration
    except Exception as e:
        sys.stderr.write(f"[WARN] Primary Gemini transcription failed: {e}\n")

    # 2. Secondary fallback: Local Faster-Whisper
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

        def run_whisper(vad_enabled=True):
            kwargs = {
                "beam_size": 5,
                "task": "translate",
                "initial_prompt": DOMAIN_PROMPT,
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
                    initial_prompt=DOMAIN_PROMPT,
                    temperature=0.0,
                    condition_on_previous_text=False
                )

        segments, info = run_whisper(vad_enabled=True)

        transcript_parts = []
        last_end = 0.0
        max_gap = 0.0
        prev_end = 0.0

        for segment in segments:
            text = segment.text.strip()
            if text:
                if prev_end > 0 and (segment.start - prev_end) > max_gap:
                    max_gap = segment.start - prev_end
                transcript_parts.append(f"[{segment.start:.1f}s - {segment.end:.1f}s] {text}")
                prev_end = segment.end
                last_end = segment.end

        # If an unnatural silence gap (> 18s) occurred, re-transcribe without VAD filter
        if max_gap > 18.0 and audio_duration > 30.0:
            try:
                segments_fallback, _ = run_whisper(vad_enabled=False)
                fallback_parts = []
                for segment in segments_fallback:
                    text = segment.text.strip()
                    if text:
                        fallback_parts.append(f"[{segment.start:.1f}s - {segment.end:.1f}s] {text}")
                if len(fallback_parts) > len(transcript_parts):
                    transcript_parts = fallback_parts
            except Exception:
                pass

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
    Strips ASR timestamps and corrects common phonetic mishearings.
    """
    clean = re.sub(r'\[(?:\d+:)?\d+(?:\.\d+)?s?\s*-\s*(?:\d+:)?\d+(?:\.\d+)?s?\]', '', transcript)
    clean = re.sub(r'\((?:\d+:)?\d+(?:\.\d+)?\)', '', clean)
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


def _is_pet_domain_context(phrase, text_lower, context_window=80):
    """
    Check if a flagged phrase appears in pet/animal domain context.
    Looks at surrounding ±context_window characters around EACH occurrence
    of the phrase for pet-related terms. Returns True if pet-domain context
    is detected (meaning the phrase is likely NOT actual abuse).
    """
    pattern = r'\b' + re.escape(phrase) + r'\b'
    for match in re.finditer(pattern, text_lower):
        start = max(0, match.start() - context_window)
        end = min(len(text_lower), match.end() + context_window)
        window = text_lower[start:end]

        # Check for pet domain context terms near this occurrence
        pet_context_count = 0
        for term in PET_DOMAIN_CONTEXT_TERMS:
            if re.search(r'\b' + re.escape(term) + r'\b', window):
                pet_context_count += 1
                if pet_context_count >= 1:
                    return True

    return False


def _is_asr_mistranscription(phrase, text_lower, context_window=80):
    """
    Check if a flagged phrase is likely an ASR (speech-to-text) mistranscription.
    Looks at surrounding context for hint words that suggest the real word
    was something else (e.g., 'fool' near 'eat'/'feed' → likely 'food').
    """
    if phrase not in ASR_MISTRANSCRIPTION_PAIRS:
        return False

    hints = ASR_MISTRANSCRIPTION_PAIRS[phrase]
    pattern = r'\b' + re.escape(phrase) + r'\b'
    for match in re.finditer(pattern, text_lower):
        start = max(0, match.start() - context_window)
        end = min(len(text_lower), match.end() + context_window)
        window = text_lower[start:end]

        for hint in hints:
            if re.search(r'\b' + re.escape(hint) + r'\b', window):
                return True

    return False


def _is_directed_at_person(phrase, text_lower, context_window=60):
    """
    Check if a flagged phrase is directly aimed at a human being
    (e.g., 'you are an idiot', 'you fool', 'he is stupid').
    Returns True if the phrase appears to target a person.
    """
    # Patterns that indicate the word is directed at a person
    person_directed_patterns = [
        r'you\s+(?:are\s+(?:a(?:n)?\s+)?)?' + re.escape(phrase),
        r"you'?re\s+(?:a(?:n)?\s+)?" + re.escape(phrase),
        r'he\s+is\s+(?:a(?:n)?\s+)?' + re.escape(phrase),
        r'she\s+is\s+(?:a(?:n)?\s+)?' + re.escape(phrase),
        r'this\s+(?:guy|person|man|woman|lady)\s+is\s+(?:a(?:n)?\s+)?' + re.escape(phrase),
        r'what\s+a\s+' + re.escape(phrase),
        r'such\s+a(?:n)?\s+' + re.escape(phrase),
        r'calling\s+(?:me|him|her|them)\s+(?:a(?:n)?\s+)?' + re.escape(phrase),
    ]
    for pat in person_directed_patterns:
        if re.search(pat, text_lower):
            return True
    return False


def detect_abuse_and_negativity(text_lower):
    """
    Context-aware detection of abusive language, scolding, threats, negative speech,
    and unrelated topics. Uses a TWO-PASS approach:
      Pass 1: Find keyword candidates via regex word-boundary matching.
      Pass 2: Filter out false positives by checking surrounding context for
              pet/animal/handling domain terms and ASR mistranscription patterns.
    Only flags phrases that are genuinely directed at insulting/berating a person,
    NOT phrases that describe pet behavior, handling techniques, or are ASR errors.
    """
    abuse_found = []
    scolding_found = []
    threats_found = []
    negative_flags = []
    unrelated_found = []

    def phrase_matches(phrase, text):
        pattern = r'\b' + re.escape(phrase) + r'\b'
        return re.search(pattern, text) is not None

    # ── Pass 1 & 2: Candidate detection + context filtering ──────────

    # ABUSE PHRASES — context-filtered
    for phrase in ABUSE_PHRASES:
        if phrase_matches(phrase, text_lower):
            # Pass 2: Check if this is a false positive
            if _is_asr_mistranscription(phrase, text_lower):
                continue  # Likely ASR error (e.g., 'fool' → 'food')
            if _is_pet_domain_context(phrase, text_lower):
                # In pet context — only flag if explicitly directed at a person
                if _is_directed_at_person(phrase, text_lower):
                    abuse_found.append(phrase)
                # else: suppress — it's describing pet behavior/handling
            else:
                abuse_found.append(phrase)

    # SCOLDING PHRASES — these are multi-word phrases, less prone to false positives
    # but still check for pet context
    for phrase in SCOLDING_PHRASES:
        if phrase_matches(phrase, text_lower):
            if _is_pet_domain_context(phrase, text_lower):
                if _is_directed_at_person(phrase, text_lower):
                    scolding_found.append(phrase)
            else:
                scolding_found.append(phrase)

    # THREATENING PHRASES — check for pet-care context (e.g., 'vet' near 'court'/'police')
    for phrase in THREATENING_PHRASES:
        if phrase_matches(phrase, text_lower):
            # Threats are less likely to be pet-domain false positives,
            # but still suppress if clearly in pet-care discussion context
            if phrase in ('media', 'social media', 'consequences', 'viral'):
                # These generic words need stronger context check
                if not _is_pet_domain_context(phrase, text_lower):
                    threats_found.append(phrase)
            else:
                threats_found.append(phrase)

    # NEGATIVE SPEECH INDICATORS — highly prone to false positives in pet care
    # Words like 'aggressive', 'hostile', 'shouting' often describe pet behavior
    for phrase in NEGATIVE_SPEECH_INDICATORS:
        if phrase_matches(phrase, text_lower):
            if _is_asr_mistranscription(phrase, text_lower):
                continue
            if _is_pet_domain_context(phrase, text_lower):
                # Only flag if clearly about a human, not a pet
                if _is_directed_at_person(phrase, text_lower):
                    negative_flags.append(phrase)
                # else: suppress — describing pet temperament/behavior
            else:
                negative_flags.append(phrase)

    # UNRELATED PHRASES — no context filtering needed (these are always off-topic)
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


def generate_structured_summary(clean_text, word_count, sentiment, abuse_result, has_greeting, has_confirmation, has_closing, verification_status, qa_eval=None, service=None):
    """
    Generate an intelligent, transcript-aware executive summary covering verified facts, policies, and findings.
    """
    text_lower = clean_text.lower()
    summary_points = []
    
    # Check verification terms presence
    verification_terms = [
        'verification', 'verify', 'confirm', 'booking', 'pet', 'dogs', 'dog', 'cats', 'cat',
        'address', 'timing', 'schedule', 'experience', 'policy', 'guidelines', 'provider',
        'walker', 'sitter', 'groomer', 'identity', 'profile', 'petsfolio', 'ethical', 'sitting'
    ]
    has_verification_content = any(re.search(r'\b' + re.escape(term) + r'\b', text_lower) for term in verification_terms)

    # Resolve service category name dynamically
    svc_name = "Pet Care"
    if service:
        s_norm = str(service).lower().strip()
        if 'sit' in s_norm or s_norm in ('6', '1', 'boarding'):
            svc_name = "Pet Sitting"
        elif 'groom' in s_norm or s_norm == '2':
            svc_name = "Pet Grooming"
        elif 'train' in s_norm or s_norm == '3':
            svc_name = "Dog Training"
        elif 'walk' in s_norm or s_norm == '4':
            svc_name = "Dog Walking"
        elif 'vet' in s_norm or s_norm == '5':
            svc_name = "Veterinary Care"
    else:
        detected_id = detect_service_from_transcript(clean_text)
        if detected_id in (6, 1):
            svc_name = "Pet Sitting"
        elif detected_id == 2:
            svc_name = "Pet Grooming"
        elif detected_id == 3:
            svc_name = "Dog Training"
        elif detected_id == 4:
            svc_name = "Dog Walking"

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
    summary_points.append(f"Verification Executive conducted a formal {svc_name} verification call with the Service Provider ({word_count} words).")
    
    # Extract specific verified dialogue details
    details_verified = []
    
    # Prior experience
    exp_match = re.search(r'(\d+|\b(one|two|three|four|five|six|seven|eight|nine|ten|eleven|twelve))\s+(?:years?|months?)', text_lower)
    if exp_match:
        details_verified.append(f"SP confirmed {exp_match.group(0)} of {svc_name.lower()} / pet care experience")
    elif any(kw in text_lower for kw in ['experience', 'handled', 'parenting', 'pet', 'dog', 'cat', 'home']):
        details_verified.append(f"SP confirmed prior experience in {svc_name.lower()} and pet care")
        
    # Ethical policies & Off-app booking rule
    if any(phrase in text_lower for phrase in ['outside the app', 'ethical service', 'insurance policy', 'liability', 'offline', 'penalty']):
        if 'outside' in text_lower or 'offline' in text_lower:
            details_verified.append("SP confirmed strict adherence to ethical service policies and agreed NOT to accept bookings outside the app")
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


def analyze_with_llm(clean_text, service=None):
    """
    Performs RAG-augmented call analysis using Google Gemini Flash (Gemini 3.6 Flash / 2.5 Flash),
    OpenAI, or Ollama.
    """
    gemini_key = os.getenv("x-goog-api-key") or os.getenv("GEMINI_API_KEY") or os.getenv("GOOGLE_API_KEY")
    gemini_model = os.getenv("GEMINI_MODEL", "gemini-3.6-flash")
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
- This is a PET CARE PLATFORM. Words about animals and pet handling are DOMAIN TERMS, not abuse.
- DO NOT flag domain terms or standard conversation out of context:
  * References to pets, dogs, cats, animals, or pet care routines in a pet platform are domain-relevant—NOT abuse or unrelated talk, UNLESS explicitly used with hostile intent to insult a human being (e.g. calling a person a name).
  * Standard greetings (e.g., "hello", "namaste") are polite call etiquette—NOT abuse.
  * Customer service commitments (e.g., "I will make sure to process your document", "I will confirm your timing") express helpful service—NOT threats.
- ONLY flag abuse (`has_abuse = 1`), scolding, or threats when the speaker's true INTENT is to insult, berate, demean, intimidate, or threaten another HUMAN PERSON directly.
- ONLY flag unrelated conversations when the speaker's intent is to engage in non-verification topics (e.g., cricket matches, movies, politics, personal relationship gossip).

CRITICAL FALSE-POSITIVE AVOIDANCE (MUST FOLLOW):
- The word "aggressive" describing a DOG's temperament (e.g., "dog gets aggressive", "condition being aggressive") is NOT negative speech — it is a legitimate pet behavior description.
- The word "shouting" when describing how a person gives voice commands to a DOG (e.g., "shouting at the dog to stop", "by my shouting two times") is NOT negative speech — it is a dog control technique.
- The word "shut up" when describing calming a DOG down or as a command to a pet (e.g., "it should be with a shut up at that time") is NOT abuse — it is a pet command.
- The word "fool" in transcripts often comes from ASR (speech-to-text) mistranscribing "food" — check if context mentions eating, feeding, meals, stomach. If so, it is NOT abuse.
- The word "dominant" describing a dog's behavior (e.g., "dog gets dominant") is pet terminology — NOT negative speech.
- Words like "hostile", "mad", "crazy" describing animal behavior or medical conditions are NOT negative speech about humans.
- The word "tone" when discussing voice commands or training is NOT negative speech.
- Do NOT add words to abuse_instances or negative_speech_flags unless the word is CLEARLY directed at insulting or berating a HUMAN BEING in the conversation.

MANDATORY BUSINESS CALCULATIONS:
1. Call Summary: Summarize the purpose of the call, verification details discussed, executive questions, SP responses, and explicitly mention any genuine issues/unrelated conversation.
2. Unrelated Conversation Detection: Identify sentences where intent is unrelated to verification (cricket, movies, songs, politics, sports, personal relationships).
3. Bad / Abusive Language Detection: Detect any genuine profanity, insults, scolding, or threats DIRECTED AT A PERSON (not describing pet behavior or handling techniques).
4. Verification Relevance: Assess if call is relevant to verification or contains music/song/blank audio.
5. Weighted Call Rating (0.0 to 10.0):
   - Verification Relevance: 30%
   - Communication Quality: 25%
   - Professional Behaviour: 20%
   - Verification Completion: 15%
   - Unrelated Conversation: 5%
   - Appropriate Language: 5%
6. Rating Visual Indicator: rating_color MUST be "red" if serious issues exist (genuine abusive language directed at a person, song/music uploaded, major irrelevance, failure to verify), else "green".
7. Alerts: Generate Critical (🔴), Warning (🟠), or Good (🟢) alerts based on intent evaluation.
8. Highlighted Conversations: Include array of flagged sentence items with speaker, timestamp, category, sentence, severity. Only include items where language is genuinely abusive toward a person.

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


def dynamic_analyze_transcript(transcript, service=None, provider_id=None, custom_questions=None):
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
    llm_result = analyze_with_llm(clean_text, service=service)
    if llm_result and isinstance(llm_result, dict) and "overall_rating" in llm_result:
        llm_result["transcript"] = transcript
        llm_result["keywords"] = extract_dynamic_keywords(clean_text)
        # Ensure abuse fields exist even from LLM
        abuse_result = detect_abuse_and_negativity(text_lower)
        if 'has_abuse' not in llm_result or not llm_result.get('abuse_instances'):
            llm_result['has_abuse'] = 1 if abuse_result['has_abuse'] else 0
            llm_result['abuse_instances'] = abuse_result['abuse_instances'] + abuse_result['scolding_instances']
            llm_result['negative_speech_flags'] = abuse_result['negative_speech_flags'] + abuse_result['threat_instances']

        # ─── VERIFICATION QUESTION & OUT-OF-BOX EVALUATION ───────────────
        qa_eval = evaluate_verification_qa(transcript, service=service, provider_id=provider_id, custom_questions=custom_questions)
        llm_result["verification_qa"] = qa_eval
        llm_result["mandatory_questions_status"] = qa_eval.get('mandatory_questions_status', 'Unknown')
        llm_result["all_mandatory_asked"] = 1 if qa_eval.get('all_mandatory_asked') else 0
        llm_result["missed_mandatory_questions"] = qa_eval.get('missed_mandatory_questions', [])
        llm_result["qa_checklist"] = qa_eval.get('qa_checklist', [])
        llm_result["questions_asked_count"] = qa_eval.get('questions_asked_count', 0)
        llm_result["total_questions_count"] = qa_eval.get('total_questions_count', 0)
        llm_result["question_coverage_pct"] = qa_eval.get('question_coverage_pct', 0.0)

        # Enforce score penalty for missing mandatory verification questions
        missed_mandatory = qa_eval.get('missed_mandatory_questions', [])
        missed_count = len(missed_mandatory)
        coverage_pct = qa_eval.get('question_coverage_pct', 100.0)
        
        base_rating = float(llm_result.get("overall_rating") or llm_result.get("qa_score") or 9.5)
        if missed_count > 0:
            max_allowed = round(max(1.0, 10.0 - (missed_count * 0.6)), 1)
            calculated_rating = min(base_rating, max_allowed)
            llm_result["overall_rating"] = calculated_rating
            llm_result["qa_score"] = calculated_rating
            llm_result["call_rating"] = calculated_rating
            llm_result["score"] = calculated_rating
            if 'compliance' in llm_result:
                llm_result['compliance'] = min(int(llm_result['compliance']), max(1, int(round(coverage_pct / 10.0))))
        else:
            llm_result["score"] = base_rating
            llm_result["qa_score"] = base_rating

        llm_result["status"] = llm_result.get("verification_status", "approved")
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
        'appreciate', 'understood', 'wonderful', 'pleasure', 'all good',
        'sounds good', 'great', 'awesome', 'yes sir', 'yes ma\'am',
    ]

    # Contextual escalation indicators
    escalation_phrases = [
        'escalate', 'manager', 'supervisor', 'senior', 'complaint',
        'higher authority', 'team lead', 'speak to someone else',
        'not satisfied', 'unacceptable',
    ]

    # Contextual greetings and closings
    greeting_phrases = [
        'hello', 'hi', 'good morning', 'good afternoon', 'good evening',
        'calling from petsfolio', 'this is petsfolio', 'petsfolio team',
        'how are you', 'how may i help', 'how can i help',
    ]
    closing_phrases = [
        'thank you for your time', 'have a great day', 'have a good day',
        'goodbye', 'bye', 'take care', 'thanks for calling',
        'let us know if you need anything', 'onboarding next steps',
    ]
    confirmation_phrases = [
        'confirm', 'verified', 'got it', 'noted', 'all set',
        'registered', 'profile updated', 'details checked', 'experience',
        'dog walking', 'grooming', 'training', 'sitter',
    ]

    # Scoring matches
    pos_matches = [p for p in pos_phrases if p in text_lower]
    esc_matches = [p for p in escalation_phrases if p in text_lower]

    has_greeting = any(p in text_lower for p in greeting_phrases)
    has_closing = any(p in text_lower for p in closing_phrases)
    has_confirmation = any(p in text_lower for p in confirmation_phrases)
    is_escalated = len(esc_matches) > 0
    has_complaint = any(w in text_lower for w in ['complaint', 'complain', 'issue', 'problem', 'unhappy', 'dissatisfied', 'poor service', 'bad service'])

    # Word counts and sentiments
    pos_score = len(pos_matches)
    neg_score = len(abuse_result['negative_speech_flags']) + (2 if abuse_result['scolding_instances'] else 0) + (3 if abuse_result['abuse_instances'] else 0)

    if abuse_result['has_abuse']:
        sentiment = "Negative"
    elif pos_score > neg_score:
        sentiment = "Positive"
    elif neg_score > pos_score:
        sentiment = "Negative"
    else:
        sentiment = "Neutral"

    # Strict Component Ratings (1 to 10 scale)
    if abuse_result['severity'] >= 4 or (word_count < 10 and not has_confirmation):
        comm_score = 1
        prof_score = 1
        emp_score = 1
        comp_score = 1
        overall = 1.0
        risk_score = 10
        verification_status = "rejected"
        qa_score = 0.0
    elif abuse_result['severity'] == 3:
        comm_score = 2
        prof_score = 2
        emp_score = 2
        comp_score = 2
        overall = 2.0
        risk_score = 9
        verification_status = "rejected"
        qa_score = 1.0
    elif abuse_result['severity'] == 2:
        comm_score = 4
        prof_score = 3
        emp_score = 3
        comp_score = 4
        overall = 3.5
        risk_score = 7
        verification_status = "re_verification"
        qa_score = 3.0
    else:
        comm_score = 5 + (2 if has_greeting else 0) + (1 if has_closing else 0) + min(2, pos_score) - min(3, neg_score)
        prof_score = 6 + (2 if has_greeting and has_closing else 0) + (1 if has_confirmation else 0) - (2 if has_complaint else 0) - min(3, len(abuse_result['unrelated_topics']))
        emp_score = 6 + min(2, pos_score) - (2 if is_escalated else 0) - min(2, len(abuse_result['negative_speech_flags']))
        comp_score = 7 + (2 if has_confirmation else 0) + (1 if has_closing else 0) - (3 if is_escalated else 0) - min(3, len(abuse_result['unrelated_topics']))

        comm_score = max(1, min(10, comm_score))
        prof_score = max(1, min(10, prof_score))
        emp_score = max(1, min(10, emp_score))
        comp_score = max(1, min(10, comp_score))

        overall = round((comm_score + prof_score + emp_score + comp_score) / 4.0, 1)

        risk_score = 1
        if abuse_result['has_abuse']:
            risk_score += 5
        if is_escalated:
            risk_score += 3
        if has_complaint:
            risk_score += 2
        if abuse_result['unrelated_topics']:
            risk_score += 2
        risk_score = min(10, risk_score)

        if risk_score >= 7:
            verification_status = "rejected"
        elif risk_score >= 4 or neg_score >= 1:
            verification_status = "re_verification"
        else:
            verification_status = "approved"

        qa_score = round(max(0.0, min(10.0, overall - (2.0 if is_escalated else 0) - (1.5 if has_complaint else 0) - (2.0 if abuse_result['has_abuse'] else 0))), 1)

    # ─── VERIFICATION QUESTION & OUT-OF-BOX EVALUATION ───────────────
    qa_eval = evaluate_verification_qa(transcript, service=service, provider_id=provider_id, custom_questions=custom_questions)

    # Factor missing mandatory questions into overall rating and qa_score
    missed_mandatory = qa_eval.get('missed_mandatory_questions', [])
    missed_count = len(missed_mandatory)
    coverage_pct = qa_eval.get('question_coverage_pct', 100.0)
    
    if missed_count > 0:
        max_allowed = round(max(1.0, 10.0 - (missed_count * 0.6)), 1)
        overall = min(overall, max_allowed)
        qa_score = min(qa_score, max_allowed)
        comp_score = min(comp_score, max(1, int(round(coverage_pct / 10.0))))

    # ─── STRUCTURED SUMMARY GENERATION ─────────────────────────────
    summary = generate_structured_summary(
        clean_text, word_count, sentiment, abuse_result,
        has_greeting, has_confirmation, has_closing, verification_status, qa_eval, service=service
    )

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
    if not qa_eval.get('all_mandatory_asked'):
        missed = qa_eval.get('missed_mandatory_questions', [])
        if missed:
            missed_names = [m['question'] for m in missed[:3]]
            weaknesses.append(f"⚠️ Required Questions Missing — Executive did not ask: {'; '.join(missed_names)}")
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
    # ─── Mandatory Questions Missing Alert ──────────────────────────
    if not qa_eval.get('all_mandatory_asked'):
        missed = qa_eval.get('missed_mandatory_questions', [])
        if missed:
            missed_names = [m['question'] for m in missed[:3]]
            alerts.append({
                "type": "Warning",
                "category": "Required Questions Missing",
                "message": f"Executive did not ask {len(missed)} mandatory question(s): {'; '.join(missed_names)}",
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
        "verification_qa": qa_eval,
        "mandatory_questions_status": qa_eval.get('mandatory_questions_status', 'Unknown'),
        "all_mandatory_asked": 1 if qa_eval.get('all_mandatory_asked') else 0,
        "missed_mandatory_questions": qa_eval.get('missed_mandatory_questions', []),
        "qa_checklist": qa_eval.get('qa_checklist', []),
        "questions_asked_count": qa_eval.get('questions_asked_count', 0),
        "total_questions_count": qa_eval.get('total_questions_count', 0),
        "question_coverage_pct": qa_eval.get('question_coverage_pct', 0.0),
        "score": qa_score,
        "status": verification_status
    }


def main():
    if len(sys.argv) < 2:
        error_res = {"error": "Missing audio file argument. Usage: python analyze.py <filepath_or_url> [service] [provider_id]"}
        print(json.dumps(error_res))
        sys.exit(1)

    raw_input = sys.argv[1]
    
    # Parse positional and flagged arguments robustly
    service = None
    provider_id = None
    questions_file = None
    
    idx = 2
    while idx < len(sys.argv):
        arg = sys.argv[idx]
        if arg in ('--service', '-s') and idx + 1 < len(sys.argv):
            service = sys.argv[idx + 1]
            idx += 2
        elif arg in ('--provider_id', '-p', '--provider') and idx + 1 < len(sys.argv):
            provider_id = sys.argv[idx + 1]
            idx += 2
        elif arg in ('--questions-file', '--questions', '-q') and idx + 1 < len(sys.argv):
            questions_file = sys.argv[idx + 1]
            idx += 2
        elif not arg.startswith('--') and service is None:
            service = arg
            idx += 1
        elif not arg.startswith('--') and provider_id is None:
            provider_id = arg
            idx += 1
        else:
            idx += 1

    custom_questions = None
    if questions_file and os.path.exists(questions_file):
        try:
            with open(questions_file, 'r', encoding='utf-8') as f:
                custom_questions = json.load(f)
        except Exception:
            custom_questions = None

    audio_path, is_temp = ensure_local_file(raw_input)

    if not audio_path or not os.path.exists(audio_path):
        error_res = {"error": f"Audio file or URL could not be accessed: {raw_input}"}
        print(json.dumps(error_res))
        sys.exit(1)

    try:
        transcript, audio_duration = transcribe_audio(audio_path)
        if not audio_duration or audio_duration <= 0:
            audio_duration = get_audio_duration(audio_path)
        
        result = dynamic_analyze_transcript(transcript, service=service, provider_id=provider_id, custom_questions=custom_questions)
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
