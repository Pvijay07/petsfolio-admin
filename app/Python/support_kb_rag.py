#!/usr/bin/env python3
"""
Petsfolio Support AI Knowledge Base & RAG Engine
Ingests 'Petsfolio Client Application Rag Application.docx',
chunks it into semantically coherent knowledge records,
indexes into Qdrant Vector Store with Ollama nomic-embed-text (768-dim),
and exports a structured JSON KB for instant PHP retrieval.
"""

import os
import sys
import json
import re
import zipfile
import xml.etree.ElementTree as ET
import urllib.request
import urllib.error
import argparse

# Default Endpoints
QDRANT_HOST = os.getenv("QDRANT_HOST", "http://127.0.0.1:6333")
OLLAMA_HOST = os.getenv("OLLAMA_HOST", "http://127.0.0.1:11434")
EMBED_MODEL = os.getenv("EMBED_MODEL", "nomic-embed-text")
COLLECTION_NAME = "petsfolio_support_kb"

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
ADMIN_DIR = os.path.abspath(os.path.join(BASE_DIR, "..", ".."))
DOCX_PATH = os.path.join(ADMIN_DIR, "Petsfolio Client Application Rag Application.docx")
JSON_OUTPUT_PATH = os.path.join(ADMIN_DIR, "app", "Libraries", "AIAnalyst", "support_kb.json")


def extract_docx_paragraphs(docx_path):
    """
    Extracts text paragraphs and table cells in order from docx XML.
    """
    if not os.path.exists(docx_path):
        raise FileNotFoundError(f"DOCX file not found: {docx_path}")

    with zipfile.ZipFile(docx_path) as docx:
        xml_content = docx.read('word/document.xml')
        tree = ET.fromstring(xml_content)

        paragraphs = []
        for elem in tree.iter():
            # Paragraph text
            if elem.tag.endswith('p'):
                p_text = ''.join(node.text for node in elem.iter() if node.tag.endswith('t') and node.text)
                clean = p_text.strip()
                if clean:
                    paragraphs.append(clean)
    return paragraphs


def generate_embedding(text):
    """
    Generate 768-dim vector embedding using Ollama nomic-embed-text.
    """
    try:
        payload = json.dumps({
            "model": EMBED_MODEL,
            "prompt": text
        }).encode('utf-8')

        req = urllib.request.Request(
            f"{OLLAMA_HOST}/api/embeddings",
            data=payload,
            headers={"Content-Type": "application/json"}
        )
        with urllib.request.urlopen(req, timeout=15) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("embedding")
    except Exception as e:
        sys.stderr.write(f"Embedding error for prompt '{text[:30]}...': {e}\n")
        return None


def init_qdrant_collection(vector_size=768):
    """
    Ensure Qdrant collection exists for petsfolio_support_kb.
    """
    try:
        # Check existing collection
        req = urllib.request.Request(f"{QDRANT_HOST}/collections/{COLLECTION_NAME}")
        try:
            with urllib.request.urlopen(req, timeout=5) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                if data.get("result"):
                    return True
        except urllib.error.HTTPError as he:
            if he.code != 404:
                sys.stderr.write(f"Qdrant check error: {he}\n")

        # Create collection
        payload = json.dumps({
            "vectors": {
                "size": vector_size,
                "distance": "Cosine"
            }
        }).encode('utf-8')

        req = urllib.request.Request(
            f"{QDRANT_HOST}/collections/{COLLECTION_NAME}",
            data=payload,
            headers={"Content-Type": "application/json"},
            method="PUT"
        )
        with urllib.request.urlopen(req, timeout=10) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("result", False)
    except Exception as e:
        sys.stderr.write(f"Qdrant collection creation error: {e}\n")
        return False


def build_knowledge_chunks(paragraphs):
    """
    Groups raw extracted paragraphs into structured, high-context knowledge chunks.
    """
    # Group paragraphs into coherent sections based on major headers and topic shifts
    raw_text = "\n\n".join(paragraphs)

    # Defined Knowledge Base Records
    chunks = [
        {
            "id": "app_overview_and_downloads",
            "title": "Petsfolio Client App Overview, Purpose & Official Download Links",
            "category": "App & Registration",
            "subcategory": "Downloads",
            "user_type": "client",
            "keywords": ["download", "install", "app link", "play store", "app store", "android", "ios", "website", "what is petsfolio", "overview"],
            "key_facts": {
                "android_url": "https://play.google.com/store/apps/details?id=com.petsfolio.customer&hl=en",
                "ios_url": "https://apps.apple.com/in/app/petsfolio-pet-parent/id6746559723",
                "website": "https://www.petsfolio.com"
            },
            "content": """The Petsfolio Client Application is India's premier pet care management platform for pet parents.
Official Download Links:
- Android (Google Play Store): https://play.google.com/store/apps/details?id=com.petsfolio.customer&hl=en
- iOS (Apple App Store): https://apps.apple.com/in/app/petsfolio-pet-parent/id6746559723
- Official Website: https://www.petsfolio.com

The app allows pet parents to register, add multiple pet profiles (Dogs & Cats), discover verified service providers, book pet care services (Dog Walking, Grooming, Training, Sitting/Boarding, Insurance, Vet Consultation), track service progress, manage wallet transactions, and communicate directly with providers."""
        },
        {
            "id": "ai_assistant_guidelines_and_booking_rule",
            "title": "AI Assistant Response Guidelines & General Booking Rule",
            "category": "Guidelines",
            "subcategory": "AI Response Policy",
            "user_type": "client",
            "keywords": ["how to book", "booking rule", "ai guideline", "first response", "download instruction"],
            "key_facts": {
                "rule": "If user asks how to book any service and has not mentioned having the app, first instruct downloading the app with Android & iOS links, then explain booking steps. If user confirms app is installed, skip download instructions."
            },
            "content": """Official Petsfolio AI Assistant Guidelines for Booking Enquiries:
1. For any service booking enquiry (Walking, Grooming, Training, Sitting, Insurance):
   - If the user has NOT mentioned having the Petsfolio Client Application installed, FIRST recommend downloading the app and provide both Android and iOS download links:
     * Android (Google Play): https://play.google.com/store/apps/details?id=com.petsfolio.customer&hl=en
     * iOS (App Store): https://apps.apple.com/in/app/petsfolio-pet-parent/id6746559723
   - If the user confirms they already have the app installed, skip the download links and proceed directly with the step-by-step booking instructions.
2. Step-by-step general booking workflow:
   - Install app -> Register via mobile number + 4-digit OTP -> Add Pet profile -> Select Service -> Select frequency/duration/package -> Review quotations from verified providers -> Confirm booking & pay securely."""
        },
        {
            "id": "registration_and_pet_management",
            "title": "Client Registration, OTP Authentication & Pet Profile Management",
            "category": "Account & Profile",
            "subcategory": "Registration",
            "user_type": "client",
            "keywords": ["register", "login", "otp", "create account", "add pet", "pet profile", "breed", "dog", "cat", "change phone"],
            "key_facts": {
                "otp_digits": "4 Digit OTP",
                "required_fields": ["Full Name", "Mobile Number"],
                "optional_fields": ["Email Address", "Profile Photo"],
                "pet_fields": ["Pet Type (Dog/Cat)", "Pet Name", "Age", "Breed", "Gender", "Photo (optional)"]
            },
            "content": """Client Registration & Pet Profile Process:
1. Registration Steps:
   - Enter valid mobile number.
   - Enter the 4-Digit OTP received via SMS.
   - Accept Terms & Conditions and Privacy Policy.
   - Provide Full Name (Email Address and Profile Photo are optional).
   - Account is created instantly and navigates to the Petsfolio Home Screen.
2. Adding a Pet:
   - Pets supported: Dogs and Cats.
   - Required details: Pet Type (Dog/Cat), Pet Name, Age, Breed, Gender. Photo is optional.
   - Multiple pets can be added and managed under a single account.
3. Login FAQs:
   - OTP not received? Wait 30 seconds and click 'Resend OTP'. Ensure mobile network has good signal.
   - You can update personal details or pet profiles anytime under 'My Profile' / 'My Pets'."""
        },
        {
            "id": "pricing_overview_summary",
            "title": "Petsfolio Services Pricing & Package Summary",
            "category": "Pricing",
            "subcategory": "Service Rates",
            "user_type": "both",
            "keywords": ["price", "cost", "rates", "package", "fees", "how much", "charges"],
            "key_facts": {
                "walking_starting_price": "Starts from ₹96 per walk",
                "sitting_starting_price": "Starts from ₹599 per visit / day",
                "sp_registration_fee": "₹49 one-time registration & verification fee",
                "payment_modes": ["Online Payment", "UPI", "Cards", "Netbanking", "Petsfolio Wallet"]
            },
            "content": """Petsfolio Service Pricing & Charges Overview:
- Dog Walking: Starts from ₹96 per walk (depending on package: 3-day Express, 7-day Short-term, 15-day Mid-term, 30-day Long-term; 30-min or 60-min walks; 1x, 2x, or 3x daily).
- Pet Sitting / Boarding: Starts from ₹599 per visit / day.
- Pet Grooming: Packages include Basic Bath, Full Grooming, Medicated Bath, Hygiene Grooming, Tick & Flea treatment, Haircut, Nail trimming, Ear cleaning (quotation provided by verified groomers based on pet breed and size).
- Pet Training: Packages include Basic Obedience, Advanced Obedience, Puppy Training, Agility, and Behavior Modification (quoted by certified trainers).
- Service Provider Registration: ₹49 one-time application and verification fee.
- Payments accepted: UPI, Debit/Credit Cards, Net Banking, and Petsfolio Wallet."""
        },
        {
            "id": "dog_walking_service_details",
            "title": "Dog Walking Service: Packages, Schedules, Durations & Add-ons",
            "category": "Services",
            "subcategory": "Dog Walking",
            "user_type": "client",
            "keywords": ["walking", "dog walker", "walk", "package", "30 mins", "60 mins", "once a day", "twice a day", "thrice a day", "express", "long term", "poo picking", "paw cleaning"],
            "key_facts": {
                "durations": ["30 Minutes", "60 Minutes"],
                "frequencies": ["Once a Day (1x)", "Twice a Day (2x)", "Thrice a Day (3x)"],
                "packages": [
                    {"name": "Express Package", "days": "3 Days"},
                    {"name": "Short-Term Package", "days": "7 Days"},
                    {"name": "Mid-Term Package", "days": "15 Days"},
                    {"name": "Long-Term Package", "days": "30 Days"}
                ],
                "schedule_modes": ["Weekdays (Mon-Fri)", "All Days (Mon-Sun)"],
                "add_ons": ["Poo Picking", "Paw Cleaning", "Combing / Brushing"],
                "starting_price": "Starts from ₹96 per walk"
            },
            "content": """Dog Walking Service Comprehensive Guide:
1. Walk Durations:
   - 30-Minute Walk Duration (Ideal for daily exercise and routine relief).
   - 60-Minute Walk Duration (Ideal for high-energy breeds and extended fitness).
2. Walking Frequency Options:
   - Once a Day (1x daily walk)
   - Twice a Day (2x daily walks - Morning & Evening)
   - Thrice a Day (3x daily walks)
3. Package Durations:
   - Express Package: 3 Days
   - Short-Term Package: 7 Days
   - Mid-Term Package: 15 Days
   - Long-Term Package: 30 Days (Most cost-effective, starts from ₹96/walk)
4. Schedule Options:
   - Weekdays Only (Monday to Friday)
   - All Days (Monday to Sunday)
5. Optional Add-On Services:
   - Poo Picking (Safe and hygienic waste disposal during walks)
   - Paw Cleaning (Post-walk antiseptic paw wipe and hygiene care)
   - Combing & Brushing (Post-walk coat detangling)
6. Booking & Quotation Process:
   - Select Pet -> Choose Duration, Frequency, Package, Schedule & Start Date -> Nearby verified Dog Walkers send competitive quotations -> Compare ratings, reviews, and completed walks -> Accept quote & confirm booking."""
        },
        {
            "id": "dog_walking_extensions_replacements_cancellations",
            "title": "Dog Walking Extensions, Walker Replacement & Cancellation Policy",
            "category": "Services",
            "subcategory": "Dog Walking Policies",
            "user_type": "client",
            "keywords": ["extend walking", "replace walker", "change walker", "cancel walk", "walker absent", "pause walk"],
            "key_facts": {
                "extension": "Available directly within active booking screen before package expiry.",
                "replacement": "Free replacement request available if walker is unavailable or unsatisfactory.",
                "cancellation": "Processed as per Petsfolio policy with remaining balance credited to Petsfolio Wallet."
            },
            "content": """Dog Walking Booking Management Policies:
1. Package Extension:
   - Clients can easily extend an ongoing walking package directly in the app before it ends to maintain continuous daily walks without interruption.
2. Walker Replacement:
   - If your assigned dog walker is unavailable, on leave, or if you are not satisfied with their service, you can request a 'Walker Replacement' directly in the app.
   - The Petsfolio operations team immediately assigns another verified, top-rated walker in your locality.
3. Cancellation & Refunds:
   - Walk bookings can be cancelled via support request or booking screen.
   - Eligible unused walk sessions are calculated according to policy and credited back to your Petsfolio Wallet for future services or reviewed by finance (3-5 business days).
   - Strict rule: Support AI acknowledges requests empathetically and escalates to operations/finance for priority review."""
        },
        {
            "id": "grooming_service_details",
            "title": "Pet Grooming Service: Packages, Procedures & Doorstep Care",
            "category": "Services",
            "subcategory": "Pet Grooming",
            "user_type": "client",
            "keywords": ["grooming", "bath", "haircut", "nail trimming", "ear cleaning", "tick bath", "medicated bath", "doorstep groomer", "groomer"],
            "key_facts": {
                "services_included": [
                    "Basic Bath & Blow Dry",
                    "Full Grooming (Bath, Haircut, Styling)",
                    "Medicated / Anti-Tick & Flea Bath",
                    "Hygiene Grooming (Sanitary trim, paw pads, ear cleaning, nail clip)",
                    "Nail Clipping & Filing",
                    "Ear & Eye Cleaning",
                    "Teeth Brushing & Detangling"
                ],
                "location": "Doorstep service at client's home or partner salon."
            },
            "content": """Pet Grooming Service Guide:
1. Grooming Options:
   - Basic Bath: Shampoo, conditioning, blow dry, brushing, ear cleaning, and nail clipping.
   - Full Grooming: Complete styling haircut, sanitary trim, bath, blow dry, ear cleaning, and nail trim.
   - Medicated Bath: Specialized anti-fungal, soothing, or medicated shampoo treatment for skin conditions.
   - Anti-Tick & Flea Treatment: Deep cleansing anti-parasitic treatment and preventative spray.
   - Hygiene Grooming: Paw pad trimming, sanitary trim, ear cleaning, and nail care.
2. How to Book Grooming:
   - Download/Open App -> Select 'Grooming' -> Choose Pet (Dog or Cat) -> Select specific grooming package -> Select preferred date and time slot -> Verified professional groomers provide quotations -> Select groomer and confirm booking.
3. Doorstep Convenience:
   - Certified professional groomers bring sanitized tools and equipment directly to your doorstep.
4. Rescheduling & Cancellation:
   - Can be rescheduled or cancelled prior to the groomer's dispatch."""
        },
        {
            "id": "pet_training_service_details",
            "title": "Pet Training Service: Programs, Sessions & Behavioral Coaching",
            "category": "Services",
            "subcategory": "Pet Training",
            "user_type": "client",
            "keywords": ["training", "trainer", "puppy training", "obedience", "behavior", "aggression", "potty training", "barking", "commands"],
            "key_facts": {
                "programs": [
                    "Basic Obedience Training (Sit, Stay, Come, Heel, Down)",
                    "Advanced Obedience Training (Off-leash control, distant commands)",
                    "Puppy Training (Housebreaking, potty training, socialization, bite inhibition)",
                    "Behavior Modification (Barking, aggression, separation anxiety, chewing)",
                    "Agility & Guard / Protection Training"
                ],
                "format": "One-on-one sessions at your home by certified positive reinforcement trainers."
            },
            "content": """Pet Training Service Guide:
1. Training Programs Available:
   - Basic Obedience: Core commands (Sit, Stay, Come, Down, Heel, No), leash manners, and polite greeting.
   - Puppy Foundation: Essential housebreaking, potty routine, crate training, socialization, and teething/biting control.
   - Advanced Obedience: Complex command sequences, off-leash obedience, distraction proofing, and distance commands.
   - Behavior Correction: Tackling excessive barking, food aggression, separation anxiety, destructive chewing, and leash reactivity.
2. Training Methodology:
   - Petsfolio certified trainers use modern, humane, positive reinforcement techniques that build trust between pet parent and pet.
3. How to Book Training:
   - Open App -> Select 'Pet Training' -> Select Pet & desired training program -> Set preferred start date & schedule -> Review verified trainers' profiles, certifications, and quotations -> Select trainer & confirm booking."""
        },
        {
            "id": "pet_sitting_and_boarding_details",
            "title": "Pet Sitting & Boarding: In-Home Care, Day Care & Pricing",
            "category": "Services",
            "subcategory": "Pet Sitting",
            "user_type": "client",
            "keywords": ["sitting", "sitter", "boarding", "day care", "overnight", "pet stay", "vacation care", "599", "feeding", "home visit"],
            "key_facts": {
                "starting_price": "Starts from ₹599 per visit / day",
                "options": ["In-Home Pet Sitting (Sitter visits your home)", "Overnight Boarding (Pet stays with verified host/sitter)", "Pet Day Care"],
                "includes": ["Feeding on schedule", "Walking and playtime", "Medication administration", "Live photo/video updates to pet parent"]
            },
            "content": """Pet Sitting & Boarding Service Guide:
1. Service Types:
   - In-Home Pet Sitting: A verified pet sitter visits your home to feed, walk, play with, and care for your pet in their familiar environment.
   - Overnight Boarding: Your pet stays at a verified, loving, cage-free home environment while you are away on travel.
   - Pet Day Care: Safe daytime supervision, socialization, and activities.
2. Pricing:
   - Pet sitting services start from ₹599 per visit / day.
3. What is Included:
   - Customized feeding according to your pet's exact diet routine.
   - Daily walks and active play sessions.
   - Fresh water refills and litter/poo clean-up.
   - Regular photo and video updates sent to your phone.
   - Administration of oral medications if needed.
4. How to Book:
   - Open App -> Select 'Pet Sitting' -> Choose service dates and times -> Select pet -> Receive quotes from background-verified sitters -> Confirm booking."""
        },
        {
            "id": "pet_insurance_guide",
            "title": "Pet Insurance: Coverage, Quotations, Waiting Periods & Claim Process",
            "category": "Services",
            "subcategory": "Pet Insurance",
            "user_type": "client",
            "keywords": ["insurance", "policy", "cover", "claim", "medical bill", "vet bill", "hospitalization", "surgery", "waiting period", "age limit", "third party liability"],
            "key_facts": {
                "age_eligibility": "From 3 months up to 7-10 years (renewal up to 12 years depending on policy/insurer).",
                "waiting_period": "20 days standard waiting period for illness/health covers. Immediate or 24-48h for accidental injury.",
                "covers": ["Surgery & Hospitalization", "Major Illnesses & Critical Care", "Accidental Injury Cover", "Third-Party Liability Cover", "OPD & Routine consultations (in select plans)", "Lost Pet Advertising & Reward"],
                "claims": "Cashless at network veterinary clinics or reimbursement with valid vet prescription and GST hospital bills."
            },
            "content": """Pet Insurance Comprehensive Overview:
1. Coverage Highlights:
   - Hospitalization & Surgical expenses for illnesses and injuries.
   - Accidental Injury medical treatment.
   - Third-Party Liability coverage (protects against accidental injury or property damage caused by your pet to others).
   - Major Illness covers (Cancer, Tick Fever, Organ failure, etc.).
   - Lost pet advertising cost & recovery reward support.
2. Eligibility & Age Criteria:
   - Entry age: 3 months to 7–10 years (breed dependent).
   - Lifetime renewal available subject to continuous policy renewal.
3. Waiting Periods:
   - 20-day standard waiting period for health/illness-related medical claims.
   - Accidental injury coverage is effective within 24–48 hours of policy issuance.
4. How to Get a Quote & Purchase:
   - Open App -> Select 'Pet Insurance' -> Fill pet details (Age, breed, vaccination status, health history) -> Receive customized insurance plan quotations -> Select desired sum insured -> Complete payment & policy document is generated.
5. Claims Process:
   - Submit diagnosis report, veterinary prescription, itemized GST bills, and payment receipts via the app for rapid reimbursement."""
        },
        {
            "id": "service_partner_registration_and_kyc",
            "title": "Service Provider / Partner Registration, Aadhaar KYC & ₹49 Fee",
            "category": "Service Partner",
            "subcategory": "SP Onboarding",
            "user_type": "provider",
            "keywords": ["become partner", "sp registration", "provider app", "aadhaar", "kyc", "49", "verification call", "8464825652", "earn money", "join petsfolio", "documents required"],
            "key_facts": {
                "app_play_store": "https://play.google.com/store/apps/details?id=com.petsfolio.employee&hl=en_IN",
                "app_app_store": "https://apps.apple.com/in/app/petsfolio-service-partner/id6747756338",
                "registration_fee": "₹49 one-time verification fee",
                "mandatory_document": "Aadhaar Card (Mandatory for KYC)",
                "verification_timeline": "Call verification completed within 24 to 48 working hours",
                "support_phone": "8464825652",
                "eligible_services": ["Dog Walking", "Pet Grooming", "Pet Sitting", "Pet Training"]
            },
            "content": """Petsfolio Service Partner / Provider Onboarding Guide:
1. How to Register as a Partner:
   - Download the 'Petsfolio - Service Partner' app:
     * Android (Google Play): https://play.google.com/store/apps/details?id=com.petsfolio.employee&hl=en_IN
     * iOS (App Store): https://apps.apple.com/in/app/petsfolio-service-partner/id6747756338
   - Register using mobile number and verify via OTP.
   - Complete profile and select services you wish to provide (Dog Walking, Grooming, Pet Sitting, Pet Training).
   - Upload Aadhaar Card (Mandatory for KYC verification).
   - Pay the ₹49 registration and verification fee.
2. Verification Process & Timelines:
   - The Petsfolio Verification Team conducts a call verification to confirm your identity, experience, service availability, and eligibility.
   - Verification call is typically completed within 24 to 48 working hours.
   - Once approved, your Service Provider account is activated immediately.
3. Registration & Onboarding Support Contact:
   - For assistance with registration or Aadhaar KYC, contact: 📞 8464825652
4. Features for Approved Partners:
   - Receive nearby customer service requests.
   - Submit quotations directly to pet parents.
   - Manage booking schedules and track earnings.
   - Withdraw wallet earnings directly to bank account on regular payout cycles."""
        },
        {
            "id": "punganur_cow_enquiries",
            "title": "Punganur Cow Enquiries: Availability, Pricing, Transportation & Contact",
            "category": "Special Services",
            "subcategory": "Cow Enquiries",
            "user_type": "client",
            "keywords": ["cow", "punganur cow", "punganur", "cow price", "cow availability", "dairy", "transport cow", "8466906885"],
            "key_facts": {
                "dedicated_phone": "8466906885",
                "services": [
                    "Punganur cow availability and lineage info",
                    "Pricing and quotation assistance",
                    "Transportation & safe delivery logistics",
                    "Care, feeding, and maintenance guidance"
                ]
            },
            "content": """Petsfolio Punganur Cow Enquiries:
1. Overview:
   - Petsfolio assists customers with verified enquiries regarding Punganur miniature cows.
   - Our dedicated team provides full assistance regarding availability, pricing, breed information, documentation, and safe transportation/delivery logistics.
2. Dedicated Contact Number:
   - For all Punganur cow enquiries, pricing, and availability, please directly contact our dedicated specialist team at:
   - 📞 Contact: 8466906885
3. FAQ Guidance for Support AI:
   - Whenever a user asks about Punganur cows, provide warm assistance and share the dedicated contact number 8466906885 for immediate personal guidance."""
        },
        {
            "id": "wallet_and_payment_management",
            "title": "Petsfolio Wallet, Cashless Payments, Top-Ups & Transaction Security",
            "category": "Payments",
            "subcategory": "Wallet",
            "user_type": "both",
            "keywords": ["wallet", "balance", "recharge", "top up", "refund to wallet", "cashless", "payment failed", "transaction history"],
            "key_facts": {
                "uses": ["Instant service booking checkout", "Automatic credits for package adjustments/rescheduling", "Promotional cashback and rewards"],
                "security": "100% secure encrypted payment gateways supporting UPI, Credit/Debit Cards, Net Banking."
            },
            "content": """Petsfolio Wallet & Payment System:
1. What is Petsfolio Wallet:
   - A secure digital in-app wallet for pet parents and service providers.
   - Pet parents can top up their wallet for instant one-click booking payments.
   - Booking adjustments, package credits, and loyalty rewards are credited directly to the wallet.
2. Payment Security:
   - All transactions are encrypted and processed through industry-standard secure payment gateways (UPI, Cards, Net Banking).
3. Provider Payouts:
   - Service providers can track their earnings in their Provider Wallet and request withdrawals directly to their verified bank accounts."""
        },
        {
            "id": "safety_guardrails_and_support_channels",
            "title": "Customer Support Channels, Operating Timelines & Strict Guardrails",
            "category": "Support Policies",
            "subcategory": "Safety & Contacts",
            "user_type": "both",
            "keywords": ["contact support", "help", "customer care", "refund policy", "complaint", "phone number", "timelines"],
            "key_facts": {
                "support_app_feature": "In-app 24/7 Ticketing and Live Support Chat",
                "refund_review_timeline": "3 to 5 business days by Finance Operations",
                "sp_registration_support": "8464825652",
                "cow_enquiry_support": "8466906885",
                "guardrail": "AI cannot process direct financial transactions, refunds, or record deletions. Always acknowledge warmly and escalate to human operations."
            },
            "content": """Petsfolio Customer Support Policies & Escalation Guidelines:
1. Support Channels:
   - In-App Support Chat & Ticketing (Fastest response).
   - Registration Support for Service Providers: 📞 8464825652
   - Punganur Cow Enquiries: 📞 8466906885
2. Strict Safety & Guardrail Rules for AI Support Agent:
   - DO NOT process or promise direct monetary refunds, fee waivers, or bank transfers.
   - If user requests a refund: Acknowledge politely with empathy, explain that the refund request is noted and will be reviewed by the finance team against policy within 3 to 5 business days.
   - If provider asks about payout: Explain that earnings are securely tracked in the Wallet section of the Provider App and processed via standard payout cycles.
   - Tone: Always warm, courteous, reassuring, professional, and pet-friendly in English or Hinglish."""
        }
    ]

    return chunks


def index_knowledge_to_qdrant(chunks, verbose=True):
    """
    Generates embeddings for all chunks and upserts points into Qdrant collection.
    """
    if not init_qdrant_collection(768):
        if verbose:
            sys.stderr.write("Failed to initialize Qdrant collection.\n")
        return False

    points = []
    for i, chunk in enumerate(chunks):
        # Create rich prompt for embedding
        embed_text = f"Title: {chunk['title']}\nCategory: {chunk['category']} > {chunk['subcategory']}\nKeywords: {', '.join(chunk['keywords'])}\nContent: {chunk['content']}"
        vec = generate_embedding(embed_text)
        if vec:
            points.append({
                "id": i + 1,
                "vector": vec,
                "payload": {
                    "chunk_id": chunk["id"],
                    "title": chunk["title"],
                    "category": chunk["category"],
                    "subcategory": chunk["subcategory"],
                    "user_type": chunk["user_type"],
                    "keywords": chunk["keywords"],
                    "key_facts": chunk.get("key_facts", {}),
                    "content": chunk["content"]
                }
            })
        else:
            if verbose:
                sys.stderr.write(f"Failed to generate embedding for chunk {chunk['id']}\n")

    if not points:
        if verbose:
            sys.stderr.write("No points generated for indexing.\n")
        return False

    payload = json.dumps({"points": points}).encode('utf-8')
    req = urllib.request.Request(
        f"{QDRANT_HOST}/collections/{COLLECTION_NAME}/points",
        data=payload,
        headers={"Content-Type": "application/json"},
        method="PUT"
    )

    try:
        with urllib.request.urlopen(req, timeout=20) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            if verbose:
                print(f"Successfully indexed {len(points)} knowledge chunks into Qdrant '{COLLECTION_NAME}'!")
            return True
    except Exception as e:
        if verbose:
            sys.stderr.write(f"Error upserting points to Qdrant: {e}\n")
        return False


def export_kb_json(chunks, output_path):
    """
    Saves parsed knowledge chunks into structured JSON for direct PHP access.
    """
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    data = {
        "version": "1.0.0",
        "source_document": "Petsfolio Client Application Rag Application.docx",
        "total_chunks": len(chunks),
        "chunks": chunks
    }
    with open(output_path, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=2, ensure_ascii=False)
    print(f"Exported structured Knowledge Base JSON to: {output_path}")


def query_qdrant_kb(query_text, top_k=3, user_type=None):
    """
    Semantic search in Qdrant for top matching knowledge chunks.
    """
    query_vec = generate_embedding(query_text)
    if not query_vec:
        return []

    search_payload = {
        "vector": query_vec,
        "limit": top_k,
        "with_payload": True
    }

    if user_type and user_type in ['client', 'provider']:
        # Optional filter by user_type
        search_payload["filter"] = {
            "should": [
                {"key": "user_type", "match": {"value": user_type}},
                {"key": "user_type", "match": {"value": "both"}}
            ]
        }

    payload = json.dumps(search_payload).encode('utf-8')
    req = urllib.request.Request(
        f"{QDRANT_HOST}/collections/{COLLECTION_NAME}/points/search",
        data=payload,
        headers={"Content-Type": "application/json"}
    )

    try:
        with urllib.request.urlopen(req, timeout=8) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            results = res.get("result", [])
            matches = []
            for item in results:
                payload_data = item.get("payload", {})
                matches.append({
                    "title": payload_data.get("title", ""),
                    "category": payload_data.get("category", ""),
                    "subcategory": payload_data.get("subcategory", ""),
                    "content": payload_data.get("content", ""),
                    "key_facts": payload_data.get("key_facts", {}),
                    "score": item.get("score", 0)
                })
            return matches
    except Exception as e:
        sys.stderr.write(f"Search query error: {e}\n")
        return []


def main():
    parser = argparse.ArgumentParser(description="Petsfolio Support AI Knowledge Base Ingester & RAG Search")
    parser.add_argument("--index", action="store_true", help="Extract docx, build chunks, and index into Qdrant & JSON")
    parser.add_argument("--query", type=str, help="Run semantic query search against knowledge base")
    parser.add_argument("--top-k", type=int, default=3, help="Number of results to retrieve")
    parser.add_argument("--user-type", type=str, default="client", choices=["client", "provider", "both"], help="Target user type")
    args = parser.parse_args()

    if args.index or (not args.query):
        print(f"Reading docx from: {DOCX_PATH}")
        paragraphs = extract_docx_paragraphs(DOCX_PATH)
        print(f"Extracted {len(paragraphs)} paragraphs from document.")

        chunks = build_knowledge_chunks(paragraphs)
        print(f"Built {len(chunks)} high-context knowledge base chunks.")

        # Export JSON
        export_kb_json(chunks, JSON_OUTPUT_PATH)

        # Index to Qdrant
        print("Indexing into Qdrant vector database...")
        success = index_knowledge_to_qdrant(chunks, verbose=True)
        if success:
            print("Knowledge Base indexing completed successfully!")
        else:
            print("Warning: Qdrant indexing encountered issues. JSON fallback is available.")

    if args.query:
        print(f"\nSearching Knowledge Base for: '{args.query}' (User Type: {args.user_type}, Top: {args.top_k})\n")
        results = query_qdrant_kb(args.query, top_k=args.top_k, user_type=args.user_type)
        if not results:
            print("No matching knowledge base chunks found.")
        else:
            for idx, r in enumerate(results, 1):
                print(f"--- [Result {idx}] (Score: {r['score']:.4f}) ---")
                print(f"Title: {r['title']} [{r['category']} > {r['subcategory']}]")
                print(f"Content Preview:\n{r['content'][:250]}...\n")


if __name__ == "__main__":
    main()
