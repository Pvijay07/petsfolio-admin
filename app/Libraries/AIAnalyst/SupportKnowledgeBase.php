<?php

namespace App\Libraries\AIAnalyst;

/**
 * Petsfolio Support AI Knowledge Base & RAG Retrieval Service
 * 
 * Provides ultra-fast, multi-tiered retrieval from dedicated Knowledge Bases:
 * 1. Client Knowledge Base ('client_support_kb.json' & Qdrant 'petsfolio_client_kb')
 *    - Implements the 38-section Petsfolio Client Support AI Integration Specification.
 * 2. Service Provider Knowledge Base ('sp_support_kb.json' & Qdrant 'petsfolio_sp_kb')
 *    - Implements the 25-section Petsfolio Service Provider Support AI Specification.
 * 
 * Features:
 * - Separate KB routing for Client vs Service Provider.
 * - Semantic Vector Search via local Qdrant & Ollama nomic-embed-text (768-dim).
 * - High-speed In-Memory Hybrid / Keyword Search over dedicated JSON KBs (<2ms).
 * - Automatic Category classification and Priority resolution (Low, Medium, High, Critical).
 * - Automatic Human Handover trigger detection ([Connect to Human]).
 */
class SupportKnowledgeBase
{
    private string $qdrantHost;
    private string $ollamaHost;
    private string $clientCollection = 'petsfolio_client_kb';
    private string $spCollection = 'petsfolio_sp_kb';

    private string $clientKbJsonPath;
    private string $spKbJsonPath;
    private string $unifiedKbJsonPath;

    private ?array $loadedClientKb = null;
    private ?array $loadedSpKb = null;

    public function __construct()
    {
        $this->qdrantHost = getenv('QDRANT_HOST') ?: 'http://127.0.0.1:6333';
        $this->ollamaHost = getenv('OLLAMA_HOST') ?: 'http://127.0.0.1:11434';

        $this->clientKbJsonPath  = __DIR__ . '/client_support_kb.json';
        $this->spKbJsonPath      = __DIR__ . '/sp_support_kb.json';
        $this->unifiedKbJsonPath = __DIR__ . '/support_kb.json';
    }

    /**
     * Retrieve relevant knowledge base chunks for a query from the dedicated KB.
     *
     * @param string $query Customer/SP query or ticket context
     * @param string $userType 'client' or 'provider' / 'service_provider'
     * @param int $topK Number of chunks to retrieve
     * @return array List of matched knowledge chunks
     */
    public function search(string $query, string $userType = 'client', int $topK = 3): array
    {
        $cleanQuery = trim($query);
        $normUserType = $this->normalizeUserType($userType);

        if (empty($cleanQuery)) {
            return $this->getDefaultKeyFacts($normUserType);
        }

        // Tier 1: Try Semantic Vector Search in dedicated Qdrant collection
        $vectorResults = $this->searchVectorQdrant($cleanQuery, $normUserType, $topK);
        if (!empty($vectorResults)) {
            return $vectorResults;
        }

        // Tier 2: Instant High-Speed Keyword / Hybrid fallback over dedicated JSON KB
        return $this->searchKeywordFallback($cleanQuery, $normUserType, $topK);
    }

    /**
     * Format retrieved knowledge into a clean prompt context block for Gemini / LLM.
     */
    public function getFormattedContext(string $query, string $userType = 'client', int $topK = 3): string
    {
        $normUserType = $this->normalizeUserType($userType);
        $results = $this->search($query, $normUserType, $topK);
        if (empty($results)) {
            return "";
        }

        $kbLabel = ($normUserType === 'provider')
            ? "OFFICIAL PETSFOLIO SERVICE PROVIDER KNOWLEDGE BASE"
            : "OFFICIAL PETSFOLIO CLIENT KNOWLEDGE BASE";

        $context = "=== {$kbLabel} ===\n";
        foreach ($results as $idx => $r) {
            $num = $idx + 1;
            $title = $r['title'] ?? 'Guideline';
            $category = $r['category'] ?? 'General';
            $priority = $r['priority'] ?? 'Medium';
            $content = trim($r['content'] ?? '');

            $context .= "\n[KB Record #{$num}: {$title} (Category: {$category} | Priority: {$priority})]\n";
            $context .= "{$content}\n";
        }
        $context .= "=================================================\n";

        return $context;
    }

    /**
     * Classify query into one of the official support categories based on user type.
     */
    public function classifyCategory(string $query, string $userType = 'client'): string
    {
        $q = strtolower($query);
        $normUserType = $this->normalizeUserType($userType);

        if ($normUserType === 'provider') {
            if (preg_match('/\b(unsafe|threat|emergency|bite|biting|violent|police|danger)\b/', $q)) return 'Complaint';
            if (preg_match('/\b(misbehav|badly|rude|harass|fight|dispute|complaint|argument)\b/', $q)) return 'Complaint';
            if (preg_match('/\b(login|cant log|cannot log|locked|password|otp|resend otp|mobile number)\b/', $q)) return 'Account';
            if (preg_match('/\b(profile|photo|picture|change name|service not showing|service missing)\b/', $q)) return 'Profile';
            if (preg_match('/\b(walk|start walk|end walk|tracking|duration|gps|location|route)\b/', $q)) return 'Walking';
            if (preg_match('/\b(payout|released|delayed|bank|payout date|not credited)\b/', $q)) return 'Payout';
            if (preg_match('/\b(wallet|balance|withdraw|withdrawal|earnings)\b/', $q)) return 'Wallet';
            if (preg_match('/\b(aadhaar|kyc|police verification|insurance|bank verification|verify)\b/', $q)) return 'Verification';
            if (preg_match('/\b(tier|walker tier|level|move to tier|tier 2|tier 3|tier dashboard)\b/', $q)) return 'Tier';
            if (preg_match('/\b(booking|accept|reject|new booking|no show|not arrived|booking missing)\b/', $q)) return 'Booking';
            if (preg_match('/\b(cancel|cancelled|cancellation)\b/', $q)) return 'Cancellation';
            if (preg_match('/\b(crash|not opening|close automatically|update|error|bug)\b/', $q)) return 'Technical';
            if (preg_match('/\b(weather|joke|prime minister|laptop|news|cricket)\b/', $q)) return 'Out of Scope';
            return 'Other';
        }

        // Client Support Categories (Section 33 of Client PRD)
        if (preg_match('/\b(pet safety|pet injured|dog hurt|emergency|injury|bite)\b/', $q)) return 'Pet Safety Issue';
        if (preg_match('/\b(sp late|not arrived|sp cancelled|sp behavior|rude|unprofessional|misconduct|harass)\b/', $q)) return 'SP Complaint';
        if (preg_match('/\b(serious complaint|terrible|fraud|police|dispute)\b/', $q)) return 'Serious Complaint';
        if (preg_match('/\b(hacked|unauthorized|account security|otp fraud)\b/', $q)) return 'Account Security';
        if (preg_match('/\b(otp|resend otp|verify otp)\b/', $q)) return 'OTP';
        if (preg_match('/\b(login|cant log|cannot log|sign in)\b/', $q)) return 'Login';
        if (preg_match('/\b(add pet|pet profile|dog profile|cat profile|breed)\b/', $q)) return 'Pet Profile';
        if (preg_match('/\b(profile|edit profile|update profile)\b/', $q)) return 'Profile';
        if (preg_match('/\b(walk|walking|walker|track walk|walk history)\b/', $q)) return 'Walking';
        if (preg_match('/\b(groom|grooming|bath|haircut|nail clip)\b/', $q)) return 'Grooming';
        if (preg_match('/\b(train|training|puppy training|obedience)\b/', $q)) return 'Training';
        if (preg_match('/\b(sit|sitting|board|boarding|daycare|overnight)\b/', $q)) return 'Sitting';
        if (preg_match('/\b(cancel|cancellation)\b/', $q)) return 'Cancellation';
        if (preg_match('/\b(refund|money back|refund status)\b/', $q)) return 'Refund';
        if (preg_match('/\b(invoice|bill|receipt|download invoice)\b/', $q)) return 'Invoice';
        if (preg_match('/\b(service history|previous service|past booking)\b/', $q)) return 'Service History';
        if (preg_match('/\b(wallet|balance|recharge wallet)\b/', $q)) return 'Wallet';
        if (preg_match('/\b(payment|payment failed|deducted|upi|card)\b/', $q)) return 'Payment';
        if (preg_match('/\b(booking status|check booking|my booking)\b/', $q)) return 'Booking';
        if (preg_match('/\b(cow|punganur)\b/', $q)) return 'Cow Enquiry';
        if (preg_match('/\b(cricket|weather|joke|movie|general knowledge)\b/', $q)) return 'Out of Scope';

        return 'Unknown Issue';
    }

    /**
     * Detect Priority Level based on PRD Section 22 (Client) & Section 13 (Provider).
     */
    public function detectPriority(string $query, string $userType = 'client'): string
    {
        $q = strtolower($query);

        // Critical: Pet injury, pet safety concern, SP misconduct, violence, threatening behavior, account compromise
        if (preg_match('/\b(pet safety|pet injured|dog hurt|injury|emergency|bite|violent|attack|aggressive|misconduct|threat|harass|hacked|unauthorized)\b/', $q)) {
            return 'Critical';
        }

        // High: Payment deducted but booking not created, refund dispute, duplicate payment, locked account, stuck payout
        if (preg_match('/\b(deducted|not created|refund dispute|duplicate payment|payout|locked|aadhaar stuck|not received money|fraud|dispute|failed payout)\b/', $q)) {
            return 'High';
        }

        // Medium: Routine booking status, walk tracking, general cancellation, wallet balance, SP delays
        if (preg_match('/\b(wallet|balance|earnings|booking|cancel|walk|start walk|gps|duration|reject|not started|sp late)\b/', $q)) {
            return 'Medium';
        }

        // Low: General app info, pricing, downloads, invoices, service history
        return 'Low';
    }

    /**
     * Determine if query immediately requires Human Escalation ([Connect to Human]).
     */
    public function shouldEscalateToHuman(string $query, string $userType = 'client', int $repeatCount = 0): bool
    {
        $q = strtolower($query);

        if ($repeatCount >= 2) {
            return true;
        }

        // Explicit request
        if (preg_match('/\b(human|agent|supervisor|representative|talk to person|connect to human|speak with someone)\b/', $q)) {
            return true;
        }

        // High/Critical triggers requiring human intervention
        if (preg_match('/\b(pet safety|pet injured|dog hurt|bite|violent|misconduct|threat|deducted|refund dispute|not arrived|dispute|locked|profile not approved)\b/', $q)) {
            return true;
        }

        return false;
    }

    /**
     * Query Qdrant vector database using Ollama embeddings in the appropriate collection.
     */
    private function searchVectorQdrant(string $query, string $normUserType, int $topK): array
    {
        $collection = ($normUserType === 'provider') ? $this->spCollection : $this->clientCollection;

        try {
            // 1. Generate query embedding via Ollama
            $embedPayload = json_encode([
                'model'  => 'nomic-embed-text',
                'prompt' => $query
            ]);

            $ch = curl_init("{$this->ollamaHost}/api/embeddings");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $embedPayload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            $embedRes = curl_exec($ch);
            $embedHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($embedHttp !== 200 || !$embedRes) {
                return [];
            }

            $embedData = json_decode($embedRes, true);
            $vector = $embedData['embedding'] ?? null;
            if (!$vector || !is_array($vector)) {
                return [];
            }

            // 2. Query Qdrant Collection
            $searchPayload = [
                'vector'       => $vector,
                'limit'        => $topK,
                'with_payload' => true
            ];

            $ch2 = curl_init("{$this->qdrantHost}/collections/{$collection}/points/search");
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POST, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($searchPayload));
            curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch2, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 2);
            $searchRes = curl_exec($ch2);
            $searchHttp = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
            curl_close($ch2);

            if ($searchHttp >= 200 && $searchHttp < 300 && $searchRes) {
                $searchData = json_decode($searchRes, true);
                $points = $searchData['result'] ?? [];
                $matches = [];
                foreach ($points as $p) {
                    $payload = $p['payload'] ?? [];
                    $score = $p['score'] ?? 0;
                    if ($score >= 0.38) { // Relevance threshold
                        $matches[] = [
                            'title'       => $payload['title'] ?? '',
                            'category'    => $payload['category'] ?? '',
                            'subcategory' => $payload['subcategory'] ?? '',
                            'priority'    => $payload['priority'] ?? 'Medium',
                            'content'     => $payload['content'] ?? '',
                            'key_facts'   => $payload['key_facts'] ?? [],
                            'score'       => $score
                        ];
                    }
                }
                return $matches;
            }
        } catch (\Throwable $e) {
            // Silently fallback to high-speed keyword index
        }

        return [];
    }

    /**
     * Fast in-memory keyword & token scoring search over dedicated JSON KB.
     */
    private function searchKeywordFallback(string $query, string $normUserType, int $topK): array
    {
        $kb = $this->loadKbJson($normUserType);
        if (empty($kb['chunks'])) {
            return [];
        }

        $queryLower = strtolower($query);
        $tokens = preg_split('/\s+/', preg_replace('/[^\w\s]/', ' ', $queryLower));
        $tokens = array_filter($tokens, fn($t) => strlen($t) > 2);

        $scoredChunks = [];
        foreach ($kb['chunks'] as $chunk) {
            $score = 0;
            $titleLower   = strtolower($chunk['title'] ?? '');
            $contentLower = strtolower($chunk['content'] ?? '');
            $keywords     = array_map('strtolower', $chunk['keywords'] ?? []);

            // Exact keyword phrase matches (highest signal)
            foreach ($keywords as $kw) {
                if (str_contains($queryLower, $kw)) {
                    $score += 20;
                }
            }

            // Word token matches
            foreach ($tokens as $token) {
                if (str_contains($titleLower, $token)) {
                    $score += 6;
                }
                if (str_contains($contentLower, $token)) {
                    $score += 2;
                }
            }

            if ($score > 0) {
                $scoredChunks[] = [
                    'title'       => $chunk['title'] ?? '',
                    'category'    => $chunk['category'] ?? '',
                    'subcategory' => $chunk['subcategory'] ?? '',
                    'priority'    => $chunk['priority'] ?? 'Medium',
                    'content'     => $chunk['content'] ?? '',
                    'key_facts'   => $chunk['key_facts'] ?? [],
                    'score'       => $score
                ];
            }
        }

        usort($scoredChunks, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($scoredChunks, 0, $topK);
    }

    /**
     * Load dedicated JSON KB file with in-memory caching.
     */
    private function loadKbJson(string $normUserType): array
    {
        if ($normUserType === 'provider') {
            if ($this->loadedSpKb !== null) {
                return $this->loadedSpKb;
            }
            if (file_exists($this->spKbJsonPath)) {
                $this->loadedSpKb = json_decode(file_get_contents($this->spKbJsonPath), true) ?: [];
            } elseif (file_exists($this->unifiedKbJsonPath)) {
                $this->loadedSpKb = json_decode(file_get_contents($this->unifiedKbJsonPath), true) ?: [];
            } else {
                $this->loadedSpKb = [];
            }
            return $this->loadedSpKb;
        }

        // Client KB
        if ($this->loadedClientKb !== null) {
            return $this->loadedClientKb;
        }
        if (file_exists($this->clientKbJsonPath)) {
            $this->loadedClientKb = json_decode(file_get_contents($this->clientKbJsonPath), true) ?: [];
        } elseif (file_exists($this->unifiedKbJsonPath)) {
            $this->loadedClientKb = json_decode(file_get_contents($this->unifiedKbJsonPath), true) ?: [];
        } else {
            $this->loadedClientKb = [];
        }
        return $this->loadedClientKb;
    }

    /**
     * Normalize user type parameter into 'client' or 'provider'.
     */
    private function normalizeUserType(string $userType): string
    {
        $u = strtolower(trim($userType));
        if ($u === 'provider' || $u === 'service_provider' || $u === 'sp' || $u === 'walker' || $u === 'groomer') {
            return 'provider';
        }
        return 'client';
    }

    /**
     * Default key facts if query is blank or on fresh greeting.
     */
    private function getDefaultKeyFacts(string $normUserType): array
    {
        if ($normUserType === 'provider') {
            return [
                [
                    'title'       => 'Service Provider AI Support Guidelines',
                    'category'    => 'Account',
                    'subcategory' => 'General',
                    'priority'    => 'Medium',
                    'content'     => "Petsfolio SP Support: 24/7 First-level support for Walkers, Groomers, Trainers & Sitters. Aadhaar KYC takes 24-48h. For Onboarding help: 8464825652."
                ]
            ];
        }

        return [
            [
                'title'       => 'Petsfolio Client Services Overview',
                'category'    => 'Walking',
                'subcategory' => 'Downloads',
                'priority'    => 'Low',
                'content'     => "Petsfolio Client App: Book Dog Walking (starts ₹96/walk), Grooming, Training, Sitting (starts ₹599/visit), Insurance. Android: https://play.google.com/store/apps/details?id=com.petsfolio.customer&hl=en | iOS: https://apps.apple.com/in/app/petsfolio-pet-parent/id6746559723"
            ]
        ];
    }

    /**
     * Official App Links for Client and Service Provider.
     */
    public static function getAppLinks(string $userType = 'client'): array
    {
        $norm = strtolower(trim($userType));
        if ($norm === 'provider' || $norm === 'service_provider' || $norm === 'sp') {
            return [
                'android' => 'https://play.google.com/store/apps/details?id=com.petsfolio.employee&hl=en_IN',
                'ios'     => 'https://apps.apple.com/in/app/petsfolio-service-partner/id6747756338',
                'support' => '8464825652'
            ];
        }

        return [
            'android' => 'https://play.google.com/store/apps/details?id=com.petsfolio.customer&hl=en',
            'ios'     => 'https://apps.apple.com/in/app/petsfolio-pet-parent/id6746559723',
            'cow'     => '8466906885',
            'website' => 'https://www.petsfolio.com'
        ];
    }
}
