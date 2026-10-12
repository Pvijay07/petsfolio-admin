<?php

namespace App\Libraries\AIAnalyst;

/**
 * Petsfolio AI Support Agent with Knowledge Base & RAG Integration
 * 
 * Powered by Gemini and Petsfolio Support Knowledge Bases:
 * - Client Support: Implements the 38-section Petsfolio Client Support AI Integration Specification.
 * - Service Provider Support: Implements the 25-section Petsfolio Service Provider Support AI Specification.
 * 
 * Embeds all PRD practical scenarios, category classifications, follow-up clarification flows, 
 * automatic [Connect to Human] triggers, and safety guardrails.
 */
class AISupportAgent
{
    private string $apiKey;
    private string $modelUrl;
    protected SupportKnowledgeBase $knowledgeBase;

    public function __construct()
    {
        // 1. Resolve API Key from environment
        $ciEnvKey = function_exists('env') ? env('GEMINI_API_KEY') : null;
        $ciEnvModel = function_exists('env') ? env('GEMINI_MODEL') : null;

        $this->apiKey = getenv('x-goog-api-key')
            ?: getenv('GEMINI_API_KEY')
            ?: $ciEnvKey
            ?: '';

        $model = getenv('GEMINI_MODEL') ?: $ciEnvModel ?: 'gemini-3.1-flash-lite';
        $this->modelUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        // 2. Initialize Support Knowledge Base (Dual Client & SP RAG)
        $this->knowledgeBase = new SupportKnowledgeBase();
    }

    /**
     * System instruction enforcing Petsfolio persona, concise replies, empathy, and safety guardrails.
     */
    private function getSystemInstruction(string $userType = 'client'): string
    {
        $isProvider = ($userType === 'provider' || $userType === 'service_provider' || $userType === 'sp');

        if ($isProvider) {
            return <<<EOT
You are "Petsfolio SP Support AI", the official first-level (L1) support assistant for Petsfolio Service Providers (Walkers, Groomers, Trainers, Sitters).

CORE MISSION & OPERATING PRINCIPLES:
1. Provide quick, accurate, step-by-step guidance strictly based on official Petsfolio rules.
2. KEEP REPLIES SHORT & CLEAN: 2 to 3 concise, easily understandable sentences (under 50 words).
3. CLARIFY BEFORE ESCALATING:
   - If the query lacks details, ask a clarifying follow-up question before escalating.
   - Example: If SP says "My payment is not showing", ask: "Could you please clarify whether you are referring to your Wallet balance, Earnings, or Payout?"
   - Example: If SP asks about payout, ask: "Is your payout status showing as Pending, Processing, Failed, or Released?"
4. ESCALATION TO HUMAN ([Connect to Human]):
   - Include "[Connect to Human]" at the end of your reply when:
     * Account is locked or profile not approved.
     * Payout is marked "Released" but not received by SP.
     * Bank verification failed or Aadhaar KYC is stuck.
     * SP reports an aggressive pet, customer misbehavior, or safety threat (Immediate Critical Escalation).
     * SP disputes a customer complaint or requests a supervisor.
     * SP asks an out-of-scope question (Weather, jokes, general knowledge, computer repairs).
     * The issue requires backend/admin action.
5. STRICT BUSINESS & FINANCIAL GUARDRAILS:
   - NEVER approve refunds, modify wallet balances, change payout status, or update bank details directly.
   - NEVER promise specific payment arrival dates unless confirmed by system.
   - NEVER expose customer personal info or internal admin logs.
6. SPECIFIC SCENARIO GUIDELINES:
   - Cannot login / OTP: Verify registered number, retry after 30s. If account locked -> [Connect to Human].
   - Start Walk button missing: Check booking status, location permissions, and internet.
   - GPS/Tracking: Guide to Phone Settings -> Apps -> Petsfolio -> Permissions -> Location.
   - Walker Tiers: Guide to check Tier Dashboard for performance & verification criteria.
   - Onboarding / Aadhaar KYC: Upload Aadhaar, ₹49 fee, verification call in 24-48 hours. Helpline: 📞 8464825652.
7. TONE: Professional, respectful, supportive, crisp, and clear.
EOT;
        }

        // Client / Pet Parent Persona (PRD 38-Sections)
        return <<<EOT
You are "Petsfolio Support AI", the official live support assistant for Petsfolio Clients and Pet Parents.

CORE MISSION & 5 OPERATING RULES (MANDATORY):
1. Rule 1 (Known Question): Answer directly, clearly, and concisely based on Petsfolio's approved information (under 50 words, 2-3 sentences).
2. Rule 2 (Partially Known Question): If general info is available but the client's specific booking/account cannot be verified, provide the general info and guide them to connect with Human Support ([Connect to Human]).
3. Rule 3 (Unknown Question): Never guess. If you do not understand or lack reliable info:
   - 1st attempt: "I’d like to help, but I need more information to understand the issue. Please explain what happened."
   - 2nd attempt / Unresolved: "I’m unable to provide a reliable answer for this request. Please connect with our Human Support Team for further assistance." [Connect to Human]
4. Rule 4 (Sensitive & High Priority Issues):
   - Payment deducted without booking, refund dispute, pet safety concern/injury, SP misconduct/threat, or account security issue -> Immediately respond:
     "This issue requires assistance from our Human Support Team." [Connect to Human]
5. Rule 5 (Out-of-Scope Questions):
   - For cricket, weather, movies, jokes: "I’m here to help with Petsfolio-related support. I’m unable to assist with this question. Please ask me about your Petsfolio services or connect with our Human Support Team."

APPROVED CLIENT RESPONSES REFERENCE:
- OTP Not Received: "Please check that you have entered the correct registered mobile number and ensure that your phone has network connectivity. You can also wait for a short time and request the OTP again. If you still do not receive it, please connect with our Human Support Team." [Connect to Human]
- Unable to Login: "Please verify your registered mobile number and try logging in again using the OTP. If the issue continues, please connect with our Human Support Team." [Connect to Human]
- Update Profile: "You can update your profile information from the Profile section of the Petsfolio app. If you are unable to update your details, please connect with our Human Support Team."
- Add Pet: "Please open the Petsfolio app and go to your Pet Profile section. You can add your pet details from there."
- Book a Walk: "You can select the Walking service from the Petsfolio app and choose the available walk duration (30 or 60 min) and schedule (starts from ₹96/walk)."
- Track Walk: "You can use the Track Walk option available for the active walk in the Petsfolio app."
- Walk Not Started: "I understand your concern. Please check the current walk status in the app. If the walk is scheduled but has not started within the expected time, please connect with our Human Support Team for assistance." [Connect to Human]
- Book Grooming: "You can select the Grooming service in the Petsfolio app and choose the available grooming option according to your pet's requirements."
- Book Training: "Petsfolio provides training services for dogs. You can check the available training options in the app and select the package that suits your requirements."
- Book Sitting: "You can check the Sitting service in the Petsfolio app and select the available overnight sitting option (starts from ₹599/visit)."
- Booking Status: "I’m unable to check the current booking status from here. Please connect with our Human Support Team." [Connect to Human]
- Cancellation: "You can check the cancellation option for your booking in the Petsfolio app. Cancellation eligibility and applicable charges may depend on the booking status and applicable Petsfolio policy. For confirmation of your cancellation and refund eligibility, please connect with our Human Support Team." [Connect to Human]
- Refund Status: "I’m unable to verify your specific refund status. Please connect with our Human Support Team so they can check the transaction details." [Connect to Human]
- SP Not Arrived / Late: "I understand your concern. Please check your booking status and current service details in the app. If the Service Provider has not arrived as expected, please connect with our Human Support Team so they can assist you." [Connect to Human]
- Dissatisfaction / Complaint: "I’m sorry to hear that you had an issue with the service. Please share the details of the problem. If the issue requires further investigation, I can help you connect with our Human Support Team." [Connect to Human]
- Download Invoice: "You can open Service History in the Petsfolio app and use the View Invoice or Download Invoice option for the relevant service."
- Previous Services: "You can view your previous service details from the Service History section of the Petsfolio app."
- Punganur Cow Enquiries: Contact our dedicated specialist team at 📞 8466906885.

STRICT MANDATORY RULE: NEVER guess, invent refund amounts or dates, invent booking status, or promise compensation. Always be warm, professional, and pet-friendly.
EOT;
    }

    /**
     * Generate an AI support reply based on conversation history, ticket context, and dedicated Knowledge Base RAG.
     *
     * @param string $userType 'client' or 'provider'
     * @param array $ticketInfo Ticket metadata (id, subject, category, name, phone, status)
     * @param array $messages Previous chat messages array
     * @param string|null $customPrompt Optional additional instruction from agent
     * @return array
     */
    public function generateReply(string $userType, array $ticketInfo, array $messages, ?string $customPrompt = null): array
    {
        $normUserType = ($userType === 'provider' || $userType === 'service_provider' || $userType === 'sp') ? 'provider' : 'client';

        if (empty($this->apiKey)) {
            return [
                'status'   => false,
                'message'  => 'Gemini API key is not configured.',
                'reply'    => $this->getKBFallbackReply($normUserType, $ticketInfo['subject'] ?? ($ticketInfo['message'] ?? ''))
            ];
        }

        // 1. Build search query from latest messages & ticket context for RAG retrieval
        $searchQuery = ($ticketInfo['subject'] ?? '') . ' ' . ($ticketInfo['category'] ?? '') . ' ' . ($ticketInfo['message'] ?? '');
        if (!empty($messages)) {
            $recent = array_slice($messages, -3);
            foreach ($recent as $m) {
                $text = is_object($m) ? ($m->message ?? '') : ($m['message'] ?? '');
                $searchQuery .= ' ' . $text;
            }
        }
        if (!empty($customPrompt)) {
            $searchQuery .= ' ' . $customPrompt;
        }

        // 2. Retrieve relevant Knowledge Base Context from dedicated KB via RAG
        $kbContext = $this->knowledgeBase->getFormattedContext($searchQuery, $normUserType, 3);
        $category = $this->knowledgeBase->classifyCategory($searchQuery, $normUserType);
        $priority = $this->knowledgeBase->detectPriority($searchQuery, $normUserType);

        // 3. Build conversation transcript
        $userLabel = ($normUserType === 'provider') ? 'Service Provider' : 'Pet Parent';
        $conversationText = "Ticket Context:\n";
        $conversationText .= "- User: {$userLabel} (" . ($ticketInfo['name'] ?? 'User') . ")\n";
        $conversationText .= "- Detected Category: {$category}\n";
        $conversationText .= "- Priority Level: {$priority}\n\n";

        if (!empty($kbContext)) {
            $conversationText .= "{$kbContext}\n\n";
        }

        $conversationText .= "Conversation Transcript:\n";
        if (!empty($messages)) {
            foreach ($messages as $msg) {
                $sender = is_object($msg) ? ($msg->sender_type ?? 'user') : ($msg['sender_type'] ?? 'user');
                $text = is_object($msg) ? ($msg->message ?? '') : ($msg['message'] ?? '');
                $senderLabel = ($sender === 'support') ? 'Support Agent' : $userLabel;
                $conversationText .= "[{$senderLabel}]: {$text}\n";
            }
        } else {
            $conversationText .= "[{$userLabel}]: " . ($ticketInfo['message'] ?? 'Hi') . "\n";
        }

        if (!empty($customPrompt)) {
            $conversationText .= "\nAgent note/instruction: {$customPrompt}\n";
        }

        $conversationText .= "\nWrite a short, clean, approved response (2-3 sentences max). If human support is required or the issue cannot be resolved automatically, conclude with [Connect to Human].";

        $systemInstruction = [
            'parts' => [
                ['text' => $this->getSystemInstruction($normUserType)]
            ]
        ];

        $payload = [
            'systemInstruction' => $systemInstruction,
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $conversationText]]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.0,
                'maxOutputTokens' => 250,
            ]
        ];

        $url = $this->modelUrl . '?key=' . $this->apiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300 && $res) {
            $data = json_decode($res, true);
            $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            if (!empty($replyText)) {
                return [
                    'status'   => true,
                    'reply'    => trim($replyText),
                    'category' => $category,
                    'priority' => $priority
                ];
            }
        }

        // Fallback reply if API fails
        return [
            'status'   => false,
            'message'  => 'Unable to generate AI reply currently.',
            'reply'    => $this->getKBFallbackReply($normUserType, $searchQuery),
            'category' => $category,
            'priority' => $priority
        ];
    }

    /**
     * Generate dynamic, context-aware quick reply suggestion chips using dedicated Knowledge Base.
     */
    public function generateSuggestions(string $userType, array $ticketInfo, array $messages): array
    {
        $normUserType = ($userType === 'provider' || $userType === 'service_provider' || $userType === 'sp') ? 'provider' : 'client';

        if (empty($this->apiKey)) {
            return $this->getDefaultSuggestions($normUserType);
        }

        $latestMsg = '';
        if (!empty($messages)) {
            $last = end($messages);
            $latestMsg = is_object($last) ? ($last->message ?? '') : ($last['message'] ?? '');
        } else {
            $latestMsg = $ticketInfo['subject'] ?? ($ticketInfo['message'] ?? '');
        }

        $kbContext = $this->knowledgeBase->getFormattedContext($latestMsg, $normUserType, 2);

        $prompt = "Based on the inquiry: \"{$latestMsg}\" and the Knowledge Base Context below:\n";
        if (!empty($kbContext)) {
            $prompt .= "{$kbContext}\n";
        }
        $prompt .= "Provide 3 to 4 concise, context-specific quick reply options (under 10 words each) for our Petsfolio support team.\n";
        $prompt .= "Return ONLY a valid JSON array of strings, for example: [\"Option 1\", \"Option 2\", \"Option 3\", \"Option 4\"]. No markdown or extra text.";

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $this->getSystemInstruction($normUserType)]
                ]
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $prompt]]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.0,
                'maxOutputTokens' => 200,
            ]
        ];

        $url = $this->modelUrl . '?key=' . $this->apiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

        if ($res) {
            $data = json_decode($res, true);
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $cleaned = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
            $cleaned = preg_replace('/\s*```$/', '', $cleaned);
            $arr = json_decode($cleaned, true);
            if (is_array($arr) && !empty($arr)) {
                return array_slice($arr, 0, 4);
            }
        }

        return $this->getDefaultSuggestions($normUserType);
    }

    /**
     * Rewrite raw human agent text into polished, empathetic customer/SP support language.
     */
    public function rewriteDraft(string $draftText, string $userType = 'client'): string
    {
        if (empty(trim($draftText))) {
            return $draftText;
        }

        $normUserType = ($userType === 'provider' || $userType === 'service_provider' || $userType === 'sp') ? 'provider' : 'client';
        $userLabel = ($normUserType === 'provider') ? 'Service Provider partner' : 'pet parent';

        $prompt = "Please rewrite the following support draft for a Petsfolio {$userLabel} into a warm, polite, reassuring, and professional support message:\n\n\"{$draftText}\"\n\nOutput only the rewritten message without quotes or commentary.";

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => $this->getSystemInstruction($normUserType)]
                ]
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $prompt]]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.0,
                'maxOutputTokens' => 400,
            ]
        ];

        $url = $this->modelUrl . '?key=' . $this->apiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

        if ($res) {
            $data = json_decode($res, true);
            $polished = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            if (!empty($polished)) {
                return trim($polished);
            }
        }

        return $draftText;
    }

    /**
     * Fallback reply generated from Knowledge Base if API call is temporarily unavailable.
     */
    private function getKBFallbackReply(string $normUserType, string $query): string
    {
        $qLower = strtolower($query);

        if ($normUserType === 'provider') {
            if (str_contains($qLower, 'safety') || str_contains($qLower, 'unsafe') || str_contains($qLower, 'bite') || str_contains($qLower, 'aggressive')) {
                return "Your safety is important. Move to a safe location when possible and contact Human Support immediately.\n[Connect to Human]";
            }
            if (str_contains($qLower, 'payout') || str_contains($qLower, 'withdraw') || str_contains($qLower, 'wallet')) {
                return "Please check whether your payout status is Pending, Processing, Failed, or Released in your Provider Wallet. If it remains unresolved, please select [Connect to Human].";
            }
            if (str_contains($qLower, 'aadhaar') || str_contains($qLower, 'kyc') || str_contains($qLower, 'verification')) {
                return "Aadhaar KYC verification calls are completed within 24 to 48 working hours. For registration help, you can also contact our onboarding team at 📞 8464825652.";
            }
            if (str_contains($qLower, 'start walk') || str_contains($qLower, 'walk button') || str_contains($qLower, 'gps')) {
                return "Please check your GPS and Location permissions in phone settings (Settings -> Apps -> Petsfolio -> Permissions). If the button is still unavailable, please connect with Human Support.";
            }
            if (str_contains($qLower, 'tier')) {
                return "You can check your current tier, requirements, and benefits directly from your Walker Dashboard in the app.";
            }
            if (str_contains($qLower, 'weather') || str_contains($qLower, 'joke') || str_contains($qLower, 'prime minister')) {
                return "I can help with Petsfolio Service Provider-related questions. For other topics, please connect with a support agent.\n[Connect to Human]";
            }

            return "Thank you for contacting Petsfolio Service Provider Support. How can I assist you with your booking, walk, wallet, or account today?";
        }

        // Client Approved Fallbacks (PRD 38-Sections)
        if (str_contains($qLower, 'safety') || str_contains($qLower, 'injured') || str_contains($qLower, 'hurt') || str_contains($qLower, 'misconduct')) {
            return "This issue requires assistance from our Human Support Team.\n[Connect to Human]";
        }
        if (str_contains($qLower, 'otp')) {
            return "Please check that you have entered the correct registered mobile number and ensure that your phone has network connectivity. You can also wait for a short time and request the OTP again. If you still do not receive it, please connect with our Human Support Team.\n[Connect to Human]";
        }
        if (str_contains($qLower, 'login') || str_contains($qLower, 'log in')) {
            return "Please verify your registered mobile number and try logging in again using the OTP. If the issue continues, please connect with our Human Support Team.\n[Connect to Human]";
        }
        if (str_contains($qLower, 'profile')) {
            return "You can update your profile information from the Profile section of the Petsfolio app. If you are unable to update your details, please connect with our Human Support Team.";
        }
        if (str_contains($qLower, 'pet')) {
            return "Please open the Petsfolio app and go to your Pet Profile section to add or manage your pet details.";
        }
        if (str_contains($qLower, 'walk not started') || str_contains($qLower, 'not arrived') || str_contains($qLower, 'late')) {
            return "I understand your concern. Please check your booking status and current service details in the app. If the Service Provider has not arrived as expected, please connect with our Human Support Team so they can assist you.\n[Connect to Human]";
        }
        if (str_contains($qLower, 'track walk') || str_contains($qLower, 'track')) {
            return "You can use the Track Walk option available for the active walk in the Petsfolio app.";
        }
        if (str_contains($qLower, 'cow') || str_contains($qLower, 'punganur')) {
            return "Hello! For all Punganur cow enquiries, pricing, availability, and transportation assistance, please contact our dedicated specialist team directly at 📞 8466906885.";
        }
        if (str_contains($qLower, 'refund')) {
            return "I’m unable to verify your specific refund status. Please connect with our Human Support Team so they can check the transaction details.\n[Connect to Human]";
        }
        if (str_contains($qLower, 'cancel')) {
            return "You can check the cancellation option for your booking in the Petsfolio app. Cancellation eligibility and applicable charges may depend on the booking status and applicable Petsfolio policy. For confirmation, please connect with our Human Support Team.\n[Connect to Human]";
        }
        if (str_contains($qLower, 'invoice') || str_contains($qLower, 'bill')) {
            return "You can open Service History in the Petsfolio app and use the View Invoice or Download Invoice option for the relevant service.";
        }
        if (str_contains($qLower, 'walk')) {
            return "You can select the Walking service from the Petsfolio app and choose the available walk duration (30 or 60 min) and schedule (starts from ₹96/walk).";
        }
        if (str_contains($qLower, 'groom')) {
            return "You can select the Grooming service in the Petsfolio app and choose the available grooming option according to your pet's requirements.";
        }
        if (str_contains($qLower, 'train')) {
            return "Petsfolio provides training services for dogs. You can check the available training options in the app and select the package that suits your requirements.";
        }
        if (str_contains($qLower, 'sit') || str_contains($qLower, 'board')) {
            return "You can check the Sitting service in the Petsfolio app and select the available overnight sitting option (starts from ₹599/visit).";
        }
        if (str_contains($qLower, 'cricket') || str_contains($qLower, 'joke') || str_contains($qLower, 'movie') || str_contains($qLower, 'weather')) {
            return "I’m here to help with Petsfolio-related support. I’m unable to assist with this question. Please ask me about your Petsfolio services or connect with our Human Support Team.";
        }

        return "Thank you for reaching out to Petsfolio Support. How can we assist you and your pet today?";
    }

    /**
     * Fallback suggestions if API call is unavailable.
     */
    private function getDefaultSuggestions(string $normUserType): array
    {
        if ($normUserType === 'provider') {
            return [
                "Check Payout & Wallet Status",
                "Start Walk / GPS Troubleshooting",
                "Aadhaar KYC Verification Status",
                "Connect with Human Support"
            ];
        }

        return [
            "How do I track my active dog walk?",
            "Book Dog Walking (starts ₹96/walk)",
            "Where can I download my invoice?",
            "Connect with Human Support"
        ];
    }
}
