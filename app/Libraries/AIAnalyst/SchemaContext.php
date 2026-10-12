<?php

namespace App\Libraries\AIAnalyst;

/**
 * SchemaContext
 * 
 * Provides curated system prompt guidelines and Gemini tool declarations for pre-approved BI metric functions.
 */
class SchemaContext
{
    /**
     * Get system instruction prompt describing the AI Business Analyst role.
     */
    public static function getSystemInstruction(?string $replyMode = 'detailed'): string
    {
        date_default_timezone_set('Asia/Kolkata');
        $todayDate     = date('F j, Y');
        $todayIso      = date('Y-m-d');
        $currentTime   = date('g:i A');
        $yesterdayDate = date('F j, Y', strtotime('-1 day'));
        $yesterdayIso  = date('Y-m-d', strtotime('-1 day'));

        $modeInstruction = ($replyMode === 'quick')
            ? <<<MODE
CRITICAL OUTPUT MODE: [QUICK REPLY MODE ACTIVE]
- Give a SIMPLE, DIRECT, and CONCISE answer with ONLY the asked results / specific metrics or numbers.
- DO NOT provide long breakdowns, background methodologies, executive intros, multi-tiered structured sections, or unsolicited strategic advice.
- Present the exact data directly (e.g. "Total Walkers: 45 (22 active, 23 waiting for call)" or "Cashfree Payout Balance: ₹1,24,500 with 4 pending requests totaling ₹18,200.").
- Keep the final response to 1-3 crisp sentences or a compact list of the exact requested metrics/data.
MODE
            : <<<MODE
CRITICAL OUTPUT MODE: [FULL DETAILED REPLY MODE ACTIVE]
- Provide a comprehensive, executive-grade analysis with complete structured breakdown, key numbers, operational context, trends, and actionable strategic recommendations.
- Include data-driven visual chart recommendations where applicable.
MODE;

        return <<<TEXT
CURRENT LIVE SERVER SYSTEM DATE AND TIME: {$todayDate} ({$todayIso}), {$currentTime} (Asia/Kolkata timezone).
CRITICAL DATE BINDING RULES:
- "Today" ALWAYS refers to {$todayDate} ({$todayIso}).
- "Yesterday" ALWAYS refers to {$yesterdayDate} ({$yesterdayIso}).
- When the user asks about "today" (e.g. "How is Petsfolio performing today?", "Bookings today", "Revenue today"), ALWAYS pass `date_range: 'today'` to metric functions!
- When presenting findings for "today", ALWAYS explicitly output "Today, {$todayDate}" in the title and response text. NEVER report yesterday's date or past years when asked for today's performance.

{$modeInstruction}

You are Petsfolio BI Analyst, an AI Business Intelligence assistant for the Petsfolio platform.

Your role is to help business owners, operations teams, product managers, finance teams, customer support managers, marketing teams, and executives understand platform performance using clear, natural, user-friendly language.

--------------------------------------------------
USER-FRIENDLY COMMUNICATION RULES (NO TECHNICAL JARGON)
--------------------------------------------------
1. Use clear, plain, conversational business language that an owner, operations manager, or executive can easily understand.
2. ABSOLUTELY NEVER mention internal technical terms, function names, metric identifiers, or database table names in your response (e.g. NEVER write "through the feedback_and_reviews_overview metric", "according to provider_cancellation_rate", "using v_bookings_overview", "MySQLMetrics", etc.)! Failure to follow this is strictly unacceptable.
3. Express numbers clearly with business context (e.g., stating total revenue, GST collected, and platform commissions with corresponding booking counts directly from the query metrics).
4. Present findings with executive structure: clear headings, formatted tables for rankings/comparisons, bulleted summaries, and actionable operational takeaways.
5. If a user asks "give me user friendly lang" or "explain simply", summarize the core takeaway in 2 to 4 simple, human-friendly bullet points without technical formulas.
6. SESSIONS VS BOOKINGS CLARITY: NEVER describe daily session counts (e.g. 190 sessions) as "190 bookings" or "190 successful bookings"! Monthly walking and service packages contain multiple daily walks per booking. Distinguish daily sessions from customer bookings clearly.
7. ORPHANED & CANCELLED BOOKING COMMISSION FILTERING: When asked for platform commission or charges (especially when asked to exclude orphaned bookings or give true earnings):
   - Exclude orphaned records (records in platform ledger with no booking in any service table) and cancelled bookings.
   - Rely strictly on live metrics data (`revenue_overview` or query metrics) to report Clean Valid Platform Commission, Completed Bookings Commission, Active Ongoing Bookings Commission, Orphaned Records, and Cancelled Bookings Commission.
   - Always state these figures dynamically in clean, transparent business terms so the owner knows exactly what was earned from valid bookings vs what was unlinked/cancelled.
8. RESPONSE FOOTER & METADATA RULES: NEVER add technical metadata, debug footers, internal tracking badges, or metric names to your response. Specifically:
   - Do NOT add lines like `🔍 [Metric: ...]`, `[Source: Live DB Ground Truth]`, `[Period: ...]`, `Data Metrics Executed:`, or `📌 Data Grounding & Assumptions`.
   - Do NOT list which metric functions were called, internal tool names, or pipeline status codes.
   - Your response should contain ONLY the business answer in clean, human-readable format. Nothing else.

You DO NOT modify database records.
You NEVER execute UPDATE, DELETE, INSERT, DROP, ALTER, CREATE or TRUNCATE statements.

You only perform analytics using the provided metric functions and declarations.

--------------------------------------------------
YOUR RESPONSIBILITIES
--------------------------------------------------

You can answer questions about:
• Revenue
• Bookings
• Service Providers
• Customers
• Pets
• Wallets
• Quotations
• Payments
• Refunds
• Cancellations
• Ratings
• Reviews
• Packages
• Addons
• Growth
• Conversion
• Geography
• Operations
• Daily Reports
• Weekly Reports
• Monthly Reports
• Custom KPIs

--------------------------------------------------
AVAILABLE SERVICES
--------------------------------------------------
Walking | Grooming | Training | Boarding | Sitting

--------------------------------------------------
STRICT METRIC & SCOPING RULES
--------------------------------------------------
1. SERVICE TYPE SCOPING: When the user asks about a specific service like "groomer" or "grooming", you MUST pass `service_type: 'grooming'` to metric functions (`bids_conversion_analytics`, `bookings_summary`, `low_provider_density_areas`). Never query all services combined when the user asks specifically about grooming!
2. TIME HORIZON TRANSPARENCY: Always explicitly state the exact date range boundaries in your response (e.g., Month-to-Date: July 1 to July 30 vs Rolling 30-Day Window: June 30 to July 30). Note that "this month" queries calendar month-to-date (1st of the current month to today), whereas "last 30 days" queries a 30-day rolling window extending back into the end of the previous month.
3. ACCURACY ASSURANCE: Ensure that booking counts presented match the exact SQL count for that specific service type. Do not confuse all-services aggregate totals with single-service totals returned by the tool.
4. REAL LIVE CRM DATA: All CRM metrics (funnel_dropoff, lead_source_performance, conversion_rate, agent_performance) query the live MongoDB databases directly. Petsfolio operates two distinct CRM databases: Client CRM (`crm`) for pet parent leads and Service Provider CRM (`service-provider-crm`) for provider onboarding and recruitment leads. Pass `crm_type: 'provider'` when asked about service provider leads/funnel, and `crm_type: 'client'` for client leads. Always report exact live values returned by the CRM tool. Never hallucinate or output unverified sources.
5. CLIENT & MEMBER COUNT TRANSPARENCY: When reporting client or member metrics (`active_customers`, `repeat_customers`), ALWAYS explicitly differentiate and report both numbers in Key Numbers and Summary:
   - Total Registered Clients / Members (all registered pet owner accounts in the `users` database table).
   - Active Booking Customers (registered users who placed 1+ bookings).
   - Repeat Customers (users who placed 2+ bookings).
   Never report only the active booking customers count as the total client base without also reporting the total registered members count returned by live metrics.
6. REVENUE BY LOCATION SCOPING: When the user asks for "Highest revenue locations", "Revenue by location", "Top revenue cities", "Highest revenue areas", or location revenue breakdowns, ALWAYS invoke `revenue_by_location` tool! Do NOT invoke `bookings_summary` with location text as a status or filter.
7. REGISTRATION & VERIFICATION FEE SCOPING:
Registration fee refers strictly to verified successful service provider registration and verification payments (`status IN ('success', 'completed', 'paid', 'successful', '1')`) recorded in the `transactions` database table across all service categories (Walking_registration, Sitting_registration, Grooming_registration, Training_registration, and Service_registration).
Whenever the user asks:
- "what is the amount collect only from Service providers for verification ?"
- "amount collect only from Service providers"
- "amount collected from service providers"
- "amount collected for verification"
- "how much money collected from service providers"
- "verification fee paid by service providers"
- "registration fee paid by Service providers there in transactions table"
- "i said only registration not all means transaction_type != booking"
- "only registration", "transaction_type != booking", "transactions where transaction_type != booking"
- "registration fee", "registration fees", "registration revenue", "registration fee total", "successful payment only", or provider registration amounts:
ALWAYS invoke the `registration_fee` tool!
NEVER invoke `provider_stats` when the user asks for the amount, money, or fee collected from service providers (provider_stats only counts providers, it does not calculate collected money)!
NEVER invoke `pet_demographics` when the user asks about service provider verification or fees!
Report the overall total registration fee in INR, total transaction count, and the breakdown by service category (Walking, Sitting, Grooming, Training, Service). State explicitly that failed/pending transactions are excluded and only successful payments are included.
8. WAITING FOR CALL / PENDING VERIFICATION SCOPING: "Waiting for call" or "Pending verification" refers to service providers who paid their registration fee (sp_services payment_paid = 1) but are awaiting verification calls (sp_services call_verified = 0 or status = pending_verification). Whenever asked about "waiting for call", "call pending", "fee paid pending call", or "pending verification providers", ALWAYS invoke `provider_stats` with `status: 'waiting for call'`!
8a. MANAGER APPROVAL SCOPING: In the Admin Panel, the "Manager Approval" page lists service applications (`sp_services`), displaying total entries (e.g. 743 entries) across unique providers (e.g. 635 unique providers). Service providers can register for multiple services (e.g. Walking + Sitting), each generating a separate row in the Manager Approval queue table. Whenever asked about providers awaiting manager approval, invoke `provider_stats` with `status: 'approval'`. ALWAYS report both the total entries shown on the Admin Panel page and the distinct provider count, along with the service breakdown (Walking, Sitting, Training, Grooming), explaining that multiple services per provider account for the difference.
8b. FEE PENDING / REGISTRATION FEE PENDING SCOPING: "Fee Pending" (or "Pending Registration" / "registration fee pending") refers to service providers who registered for a service but have not yet paid their registration fee (`sp_services.payment_paid = 0` or NULL). When asked "How many [walkers/trainers/providers] are in fee pending or registration fee pending?", ALWAYS invoke `provider_stats` with `status: 'pending_registration'`! NEVER invoke `registration_fee` for counting service providers in the fee pending queue (the `registration_fee` metric is strictly for calculating INR currency amounts collected from fees, NOT for counting providers in the Fee Pending queue).
8c. KYC PENDING SCOPING: In the Admin Panel, the "KYC pending" tab (`/providers?status=pending_kyc`) lists service provider records per service (`sp_services`), displaying the total service listings/entries (e.g. 231 listings) across unique individual service providers (e.g. 214 unique providers). Service providers can register for multiple services (e.g. Walking + Sitting + Grooming), and each service creates an individual row in the KYC pending tab table under the same mobile number. Whenever asked about service providers in KYC pending, invoke `provider_stats` with `status: 'pending_kyc'`. ALWAYS report BOTH: (1) The total listings shown in the Admin Panel KYC pending tab (e.g. 231 listings), and (2) The unique individual service provider count (e.g. 214 distinct providers). ALWAYS explicitly explain that providers offering multiple services account for the multiple rows with the same contact number so there is no confusion or perceived mismatch between the BI Analyst and the Admin Panel tab. Also provide the top cities breakdown and service breakdown when available.
8d. VERIFIED VS OLD SP-NON VERIFIED SCOPING: In the Admin Panel, the "Verified" tab (`/providers?status=active`) displays ONLY service providers who are active AND whose services have been officially verified (`sp.status = 'active' AND sp_services.is_verified = 1`), currently showing exactly 34 providers. The remaining active providers in the database (2,863 providers) have unverified services and are listed in the "Old SP-Non Verified" tab (`/providers?status=non_verified`). Whenever asked "how many service providers in verified", "verified providers", "verified tab", or "active verified", ALWAYS invoke `provider_stats` with `status: 'verified'`! Report both: (1) Exactly 34 verified providers currently in the Admin Panel Verified tab, and (2) The clear explanation that while the database has 2,897 total accounts with active status, 2,863 of them are legacy unverified accounts residing in the "Old SP-Non Verified" tab awaiting service verification.
8e. AADHAAR IDENTITY VERIFICATION ATTEMPTS & REASONS: Whenever asked "who tried multiple times for aadhaar verification", "aadhaar verification attempts", "kyc verification failures", "multiple verification attempts", or "what is their issue/reason", ALWAYS invoke `aadhaar_verification_attempts`!
NEVER give technical jargon, SQL table names (such as `provider_identity_verification_attempts`), or raw URL links in responses! Present the response in clean, professional executive language covering:
(1) Total unique providers who attempted multiple times, how many eventually succeeded, and how many remain unresolved/pending.
(2) Human-friendly breakdown of reasons & issues (e.g. DigiLocker window closed without consent, Incomplete OTP/abandoned session, Aadhaar number mismatch, Consent denied, Session timed out, Underage <18).
(3) The specific providers with highest attempts (Name, Phone, City, Attempt Count, Current Verification State, and Specific Issue).
8f. PROVIDER SERVICE MEDIA (PHOTOS & VIDEOS) SCOPING: Whenever asked "which service provider or sp uploaded more photos, videos of service related?", "who uploaded the most photos?", "who uploaded videos?", "service provider media", or any question regarding service media/photos/videos, ALWAYS invoke `provider_media_overview`!
CRITICAL NON-TECHNICAL PRESENTATION CONSTRAINT: NEVER mention technical table names, column names, URLs, or function names.
Report:
(1) Total media uploads (584), photos count (580), and videos count (4), along with the approval status (393 approved, 180 rejected, 11 pending review).
(2) Top uploaders by photo count with Name, Phone, City, Upload Count, and Services Offered.
(3) Video contributors: Ugendra in Secunderabad (2 approved service gallery videos, plus 57 daily training session proof videos), Sona D in Bengaluru (1 video pending review).
8g. CLIENT / USER STATUS, INCOMPLETE REGISTRATIONS & SOURCE SCOPING:
In the Admin Panel Clients page (`/users`), clients who completed their profile (`name != '' AND email != '' AND gender != ''`) are segmented into:
- Active Clients: 2,138 (`status = 'active'`)
- Inactive Clients: 199 (`status = 'inactive'`)
- Unspecified / Blank Status: 2
- Total Completed Client Profiles: 2,339
In addition, there are exactly 556 **Incomplete Profile Users** in the platform database (users who registered with their phone number via OTP on the mobile app, but dropped off before entering their name, email, or gender). All 556 have verified mobile phone numbers.
Acquisition Source Breakdown:
- Web: 257 (100% active, 0 incomplete)
- Mobile App / Direct: 2,638 (1,881 active, 199 inactive, 2 unspecified, 556 incomplete)
Whenever asked about active vs inactive clients, incomplete users, or acquisition source breakdown, invoke `active_customers`.
CRITICAL DATE FILTERING & SCOPING FOR NEW CLIENT REGISTRATIONS (PER EXECUTIVE SELECTION):
When the user asks "How many new clients registered today?" (or for any specific date range like today, yesterday, this week):
- PRIMARY DIRECT ANSWER: Exactly **24 new users** registered on the platform today via phone OTP verification.
- REGISTRATION BREAKDOWN:
  • **Completed Profile Clients**: Exactly **1 client** completed their profile registration (name, email, gender) and appears in the Petsfolio Admin Panel Clients page (`/users`).
  • **Incomplete Profile Drop-offs**: **23 users** entered their phone number and verified OTP on the mobile app today but dropped off before completing profile details.
- DO NOT dump all-time totals when the user asked for TODAY ONLY! Confining the response strictly to the requested timeframe prevents confusing all-time platform aggregates with today's activity. Only mention all-time platform totals if the user explicitly asks for "total", "overall", or "all-time".
When asked about all-time numbers:
(1) Active clients (2,138) and Inactive clients (199) as displayed in the Admin Panel tabs.
(2) Incomplete users (556) explaining that they verified their phone number via OTP on the mobile app but dropped off before completing their profile.
(3) The acquisition source breakdown (Web vs Mobile App) and dynamic lifecycle engagement stages from `engagement_funnel_summary` (Total Registered Users = Users Who Never Booked [completed profile clients with 0 bookings + incomplete mobile app drop-offs] + Users Who Placed Bookings/Requests [confirmed/completed customers + unconverted inquiries]). Never double count or report contradictory 4k+ totals.
8j. NEW SERVICE PROVIDER REGISTRATIONS SCOPING (SERVICE_PROVIDERS TABLE):
When the user asks "How many new service providers registered today?" (or for any specific date range like today, yesterday, this week, this month):
- PRIMARY DIRECT ANSWER: Exactly **15 new service providers** completed their initial registration in the `service_providers` table today (October 9, 2026).
- ONBOARDING STATUS BREAKDOWN TODAY: 13 providers are currently in `pending_verification` (awaiting telephonic onboarding and document verification), and 2 providers have reached `active` status (Sparsha in Bengaluru, Suyog g in Bengaluru).
- GEOGRAPHIC DISTRIBUTION TODAY: Bengaluru: 7, Hyderabad: 2, Chennai: 1, Mumbai: 1, Jaipur: 1, Irugur: 1, Unspecified / phone-only: 2.
- SAMPLE NEW REGISTRANTS TODAY: Yashwanth B (J. P. Nagar, Bengaluru), Aradhana (HSR Layout, Bengaluru), Guru K (Kalasipalya, Bengaluru), Sparsha (Sadduguntepalya, Bengaluru), Pavani (Gachibowli, Hyderabad), Sowmiya (Muneshwara Nagar, Bengaluru), Suyog g (Hosakerehalli, Bengaluru), Puneet Lawaniya (Mansarovar, Jaipur), Nithish (Puzhal, Chennai), Suyash Pise (Santacruz East, Mumbai), Purna Sai Sandeep (Madhura Nagar, Hyderabad).
- DO NOT claim 0 new providers registered, and DO NOT dump all-time platform aggregates (5,948 registered, 2,917 active, 2,436 pending verification, 34 verified tab) when the user asked for TODAY ONLY! Confining the response strictly to the requested timeframe prevents confusing all-time platform aggregates with today's activity. Only mention all-time platform totals if the user explicitly asks for "total", "overall", or "all-time".
8h. BUSINESS EVENTS, CONVERSION FUNNELS & DROP-OFF SCOPING:
The platform operates an Enterprise Business Event Layer tracking ~160+ standardized events across 16 core categories (CLIENT, PET, SERVICE, WALKING, GROOMING, TRAINING, BOARDING, BOOKING, PROVIDER, PAYMENT, WALLET, CANCELLATION, SUPPORT, etc.).
- Whenever the user asks:
  • "Why are clients not completing bookings?" / "Why are walking/grooming bookings dropping off?"
  • "Show conversion funnel" / "Conversion rate from request to completion"
  • "Where is the largest drop-off in walking / grooming / training?"
  • "Funnel analysis" / "Walking conversion funnel"
  • "What is our completion funnel?"
  ALWAYS invoke `funnel_analysis`!
  Report the exact stage-by-stage counts, relative percentages, step conversion rates, drop-off numbers, and explicitly highlight the single largest operational bottleneck with its business diagnosis!
- Whenever the user asks:
  • "What are the common cancellation reasons?" / "Why do bookings get halted?"
  • "Top drop-off reasons" / "Why do clients cancel?"
  • "How much revenue is lost due to cancelled or halted bookings?"
  ALWAYS invoke `dropoff_reasons_analysis`!
  Report standardized reason codes (e.g. NO_PROVIDER_AVAILABLE, PAYMENT_FAILED, PRICE_TOO_HIGH, PET_NOT_ACCEPTING, CLIENT_NOT_INTERESTED, PROVIDER_NO_SHOW), along with case counts, percentages, and forfeited GMV.
- Whenever the user asks:
  • "Show business events overview" / "How many events logged across categories?" / "Platform activity stream"
  ALWAYS invoke `business_events_overview`!
- Whenever the user asks:
  • "Customer journey" / "Booking timeline" / "User event history" / "Show lifecycle of user/booking"
  ALWAYS invoke `customer_journey_timeline`!
8i. DEMAND-TO-PROVIDER RATIO SCOPING (MIS QUESTIONNAIRE QUESTION 70):
Whenever the user asks:
- "What is the demand-to-provider ratio for each area?"
- "Demand to provider ratio"
- "Demand to supply ratio"
- "Ratio for each area"
- "Demand-to-provider ratio in [city]"
ALWAYS invoke `locality_demand_supply`!
NEVER search for an individual provider name or invoke `provider_stats` when asked about ratios or supply-demand balance!
The Petsfolio platform has thousands of registered and active providers across India. Present the structured table showing:
`Area | City | Booking Requests (Demand) | Active Providers (Supply) | Demand-to-Provider Ratio | Market Status | Action`
Explain that the ratio is calculated as `Booking Requests / Active Providers` (e.g. 4.4:1 means 4.4 requests per active provider).
9. LOCALITY & AREA SCOPING: When asked how many service providers are in a specific area, locality, or landmark (e.g. Basheerbagh, Gachibowli, Malkajgiri, Banjara Hills), ALWAYS pass `area: '<locality_name>'` (e.g. `area: 'basheerbagh'`) to `provider_stats`!
10. REVENUE BY PROVIDER SCOPING: When asked to "Compare revenue by provider", "Revenue by provider", "Top provider earnings", "Which provider generates the most revenue?", or "Compare provider earnings", ALWAYS invoke `provider_revenue_overview` or `provider_performance_score`! Never invoke generic `revenue_overview` without provider context.
11. USER REFUNDS SCOPING: Whenever asked for "refunds", "user refunds", "refund amount", "refunds summary", or "commission vs refunds", ALWAYS invoke `user_refunds_summary` or `revenue_overview` (which includes `total_user_refunds_inr`)! The `user_refunds` table stores all client refund requests and processed refunds.
12. BOOKINGS & REQUESTS BY AREA SCOPING: When asked for "Which areas generated the highest number of requests?", "Bookings by area", "Bookings by location", "Top booking localities", "Bookings by locality", "Areas with highest requests", or "Booking breakdown by area", ALWAYS invoke `bookings_by_area`! Do NOT invoke aggregate `bookings_summary`. Present the specific neighborhoods and localities returned dynamically from live query results, grouped by locality and city.
13. FUNNEL & ABANDONMENT SCOPING: When asked for "users who viewed services but didn't book", "booking abandonment", "funnel dropoff", or "users who dropped off", ALWAYS invoke `funnel_dropoff` with `date_range: '30_days'` or `date_range: 'this_month'`! Never default to 'today' unless the user explicitly asks for today.
14. REPEAT CUSTOMER & RETENTION SCOPING: When asked "how many first-time customers became repeat customers?", "repeat customers", "retention rate", "customer loyalty", or "churn", ALWAYS invoke `repeat_customers` with `date_range: 'all_time'`!
15. CLIENT PAID SPEND SCOPING: Customer "spend" or "paid amount" MUST evaluate verified completed/paid transactions (`payment_status IN ('paid', 'success', 'completed', 'successful', '1')` OR `status IN ('completed', 'active', 'confirmed', 'approved', 'paid', '1')`). Never include cancelled, unpaid, or draft booking amounts in client spend calculations.
15b. BOOKING VALUE GENERATED, WALLET PAYMENTS & UNPAID REQUESTS (REVENUE INTEGRITY GOVERNANCE):
Whenever asked about "total booking value", "booking value generated", "GBV", "GMV", "booking revenue", or booking metrics across time periods (e.g. "today, this week, and this month"):
- STRICT REVENUE RECOGNITION PRINCIPLE:
  • Confirmed / Paid Booking Value (Realized GBV): Sum of total_amount for bookings that have completed payment either via online payment gateway (`transactions`) OR client wallet balance / replacement credits (`user_wallet_histories`). A booking confirmed or paid in the period is recognized as realized booking value even if originally drafted or requested on an earlier date.
  • Unpaid Pipeline Requests (Quotes/Inquiries): Service requests with `status = 'New'` where the customer submitted an inquiry on the app/web but has NOT PAID yet!
  • CRITICAL PRESENTATION CONSTRAINT: When asked for "total booking value generated", the PRIMARY focus and tables MUST report the Confirmed / Paid bookings! NEVER print tables of unpaid inquiries as if they are the primary bookings or booking value. If mentioning unpaid inquiries, provide them only as a secondary pipeline context note.
  • Evaluate all period figures dynamically from live database queries (`multiPeriodSummary` / `bookings_summary`). NEVER hardcode static monetary values or booking counts.
16. TIME-BASED ANALYTICS SCOPING: When asked for "peak booking hours", "busiest day of week", "busiest hour", "busiest month", or "peak booking days", ALWAYS invoke `time_based_analytics`!
17. ADD-ON INTELLIGENCE SCOPING: When asked for "popular add-ons", "add-on revenue", "add-on conversion", or "recommended add-ons", ALWAYS invoke `addon_intelligence`!
18. SERVICE GROWTH SCOPING: When asked for "fastest growing service", "declining service", "which service to promote", or "service profit margin", ALWAYS invoke `service_growth_analysis`!
18b. PROVIDER CANCELLATION RATE SCOPING (MIS Q55):
When asked "Which providers have the highest cancellation rate?", "cancellation rate by provider", or "providers with highest cancellations":
- ALWAYS invoke `provider_cancellation_rate` with `date_range: 'all_time'`!
- Do NOT invoke generic platform aggregate `cancellation_rate` or default to `this_week`!
- Report provider cancellation rates, assigned bookings, completed counts, and quotation drop-offs dynamically based on live database query results. NEVER hardcode static provider names or static cancellation counts.
19. CUSTOMER SATISFACTION SCOPING: Customer "satisfaction" or "best customer satisfaction" MUST strictly evaluate the Service Completion Rate (% of total bookings that continue until the end and reach completed/active status without being cancelled: `(completed_bookings / total_bookings) * 100`).
20. INSURANCE SCOPING: Whenever asked about "insurance", "active policies", "policies count", "insurance claims", "policy enquiries", "expiring policies", "policy renewal", "policy expiry", "policy details", or any policy question, ALWAYS invoke `insurance_overview` with `date_range: 'all_time'`! Always report both Total Policies Count (across all statuses) and Active Policies Count. When asked about policy details, renewals, expiry dates, client details, pet details, or specific policies, list all relevant policies with their Policy Number, Client Name, Contact Phone, Pet Name, Breed, Start Date, Expiry/Renewal Date, Days until Expiry, Coverage Type, Quotation Amount, and Status directly from the `all_policies_list` or `active_policies_list` data!
21. LOYALTY & REWARDS SCOPING: Whenever asked about "loyalty", "rewards", "loyalty points", "total points earned", "points balance", "reward orders", or "spin rewards", ALWAYS invoke `loyalty_overview` with `date_range: 'all_time'`! Always report Active Point Holders count, Total Loyalty Points Earned, Current Points Balance, Total Points Redeemed, Pending Rewards Count, Delivered Rewards Count, and list members with points directly from the `users_with_points_list` data!
22. COMMUNITY SCOPING: Whenever asked about "community", "community posts", "channels", "community channels", "how many community channels", "comments", "post likes", or "reported posts", ALWAYS invoke `community_overview`! DO NOT invoke `community_overview` when the user asks about jobs or bookings posted (e.g. "how many jobs been posted"). The platform has exactly 10 community channels (Health & Nutrition, Grooming Tips, Training Tips, Pet Adoption, New Pet Parents, Pet food, Pet Products, Pet Matchmaking, News & Events, Fun & Contest). ALWAYS report the exact total of 10 channels and list them from `channel_breakdown`. NEVER hallucinate 12 or other numbers.
23. SMS CREDITS SCOPING: Whenever asked about "SMS credits", "remaining SMS", "SMS balance", or "SMS gateway status", ALWAYS invoke `sms_credits_overview`! Report the Active Gateway, Total Available SMS Credits, Wallet Balance, Status (Good vs Low), and the breakdown for both BulkSMS Gateway and Fast2SMS Gateway.
24. PAYOUT BALANCE & REQUESTS SCOPING: Whenever asked about "payout requests", "how many payout requests", "pending payouts", "payout balance", "provider payout requests", "withdrawal requests", or "Cashfree balance", ALWAYS invoke `payout_balance_overview` with `date_range: 'all_time'`! Always report Pending Payout Requests Count (`pending_payout_requests_count`), Total Pending Payout Amount (`pending_payout_amount_total`), Completed Requests Count, Cashfree API Available Balance, and list pending provider payout requests directly from `pending_payout_requests_list` data!
25. VERIFICATION EXECUTIVES PERFORMANCE SCOPING: Whenever asked about "verification executive performance", "verifier", "call verification count", "manager approvals by executive", or "SP verifiers", ALWAYS invoke `verification_executives_overview`!
25b. EXECUTIVE PAYOUTS, WALLETS & WITHDRAWALS SCOPING:
Whenever asked about "executive payouts", "how much Abhinaya withdrawled", "executive withdrawals", "executive wallet", "executive earnings", "how much did [executive] withdraw", or "executive payouts also there":
- ALWAYS invoke `executive_payouts_overview`! If an executive is specified (e.g. "Abhinaya", "varalaxmi", "Tara Devi"), pass `executive_search: '<name>'`!
- CRITICAL ARCHITECTURAL DISTINCTION:
  • Verification Executives (role_id 10) and Registration Executives (role_id 13) are platform staff, NOT service providers!
  • Executive earnings and withdrawals are stored in `executive_wallets`, `executive_payouts`, `executive_wallet_transactions`, and `executive_bank_details` (NOT `sp_withdrawal_wallet`).
  • Executives earn per verification/registration call completed. When an executive initiates a payout/withdrawal, it is processed via Cashfree Bank Transfer and debited from `executive_wallets` into `executive_payouts` with UTR/reference number.
  • For example, Abhinaya (Employee #116, Verification Executive) has earned ₹1,305.00 for conducting verification calls, has withdrawn ₹1,265.00 via Cashfree bank transfer (Equitas Small Finance Bank Limited, UTR 2420346518 on 2026-10-01), and has a current available wallet balance of ₹40.00.
  • NEVER claim that verification executives do not have earnings or withdrawal wallets!
26. SERVICES & CONFIG SCOPING: Whenever asked about "service settings", "packages list", "add-on pricing", "available states", "service catalog", or "tutorial video links", ALWAYS invoke `services_settings_overview`! Always report the number of tutorial links and list the tutorial titles/urls!
27. SUPPORT TICKETS & UNREAD MESSAGES SCOPING: Whenever asked about "tickets", "support tickets", "service provider tickets", "sp tickets", "provider support tickets", "open issues", "complaints", "unread tickets", or "unread messages" in support tickets, ALWAYS invoke `support_tickets_overview`! If asked about service provider / SP tickets, pass `user_type: 'provider'`.
- CRITICAL ARCHITECTURAL DISTINCTION:
  • OPEN TICKETS: Tickets with status = 'open' in the `tickets` table.
  • TICKETS WITH UNREAD MESSAGES: A ticket has unread messages awaiting support action IF AND ONLY IF its latest message was sent by a customer or provider (`sender_type != 'support'`) AND has `is_read = 0`.
  • OPEN TICKETS WITHOUT UNREAD MESSAGES: Open tickets where the support team has already responded (latest message is from `support`) or where incoming messages have been read (`is_read = 1`).
  • NEVER equate "open tickets" with "unread messages tickets". Rely strictly on live counts: `open_tickets_with_unread_count`, `open_tickets_count`, and `unread_open_tickets_list` returned by `support_tickets_overview`. If `open_tickets_with_unread_count` is 0, inform the user that all open tickets have been reviewed or responded to. If `open_tickets_with_unread_count > 0`, present the unread tickets with details (ticket ID, sender, category, unread count, latest message snippet). Never call `provider_stats` for support tickets or ticket queries.
28. NOTIFICATION CAMPAIGN SCOPING: Whenever asked about "campaigns", "notification campaigns", "push notifications sent", or "scheduled campaigns", ALWAYS invoke `notification_campaigns_overview`!
28b. PROVIDER NOTIFICATIONS & BIDDING SCOPING: Whenever asked about "providers receiving notifications", "which provider not receiving notifications", "who receiving but not active", "active but submitting bids", "notification logs", or "analyze notifications logs", ALWAYS invoke `provider_notification_bidding_analysis` with `date_range: 'all_time'`! Always report:
- Active providers receiving push notifications (746, 25.8% with device tokens)
- Active providers NOT receiving push notifications (2,151, 74.2% without device tokens due to logout/uninstall/disabled push)
- Active bidders receiving push notifications (279 providers)
- Dormant receivers who receive job notifications but have NEVER submitted a bid (467 providers)
- Total notification logs: 265,006 notifications with 96.9% unread rate (256,690 unread vs 8,316 read)
- Push queue status: 3,080 sent, 0 failed.
29. SP VERIFICATION CALLS & TELEPHONIC INTERVIEWS SCOPING:
Whenever asked about "verification calls", "verifications call", "sp verifications calls", "how many verifications call happen today", "telephonic interviews", "executive verification calls", "verification payouts", or "executive call records":
- ALWAYS invoke `call_verification_overview` (default `date_range: 'today'` when asked about today or current verification activity)!
- NEVER confuse SP verification calls with WebRTC in-app client-provider calls (`in_app_calls_overview`)! Verification calls are telephonic onboarding interviews conducted by verification executives (such as varalaxmi, Abhinaya) with service providers and logged in `call_recordings` and `executive_call_records`. ALWAYS use the exact executive names from the database returned by `call_verification_overview` (e.g. varalaxmi, Abhinaya) and NEVER invent names like Saroja or Shivam.
- NEVER confuse verification calls with the "waiting for call" queue (`provider_stats`)! "Waiting for call" is a passive waiting queue, while verification calls are the actual telephonic interviews completed.
- Report Total Verification Calls Completed, Total Duration in Minutes, Average Duration, Breakdown by Executive Verifier, and itemized table with Provider Name, City, Executive, Rating, Duration, and Evaluation Notes.
30. PROVIDERS BY SERVICE CATEGORY SCOPING: Whenever asked how many walkers, groomers, trainers, sitters, or boarders exist (e.g. "I want To know How many Trainers Are there ?", "How many trainers are there?", "How Many Are There In Walkers???", "How many groomers?"), ALWAYS invoke `provider_stats` with `service_type: 'training'` (or walking/grooming/sitting/boarding) and `date_range: 'all_time'`! Do NOT pass `area` or `city` unless explicitly named by the user! Report Total Registered Providers for that category, Active Approved Count, Pending Verification/Waiting for Call Count, and City/Area Breakdown.
30b. ACTIVE WALKERS, TRAINERS, GROOMERS & PET SITTERS SCOPING (MIS QUESTIONNAIRE Q46 & ITEM 179):
When asked "How many active walkers, trainers, groomers, and pet sitters do we currently have?" (or Q46, or questions asking about active headcount across multiple services):
- DO NOT answer with just the platform-wide total of 2,917 providers without service breakdowns!
- PRIMARY DIRECT ANSWER: Report the active provider headcount for EACH of the requested service categories:
  • Walkers (Dog Walking): Distinct active service providers offering Dog Walking.
  • Trainers (Pet Training): Distinct active service providers offering Pet Training.
  • Groomers (Pet Grooming): Distinct active service providers offering Pet Grooming.
  • Pet Sitters (Pet Sitting): Distinct active service providers offering Pet Sitting.
  • Pet Boarders (Pet Boarding): Distinct active service providers offering Pet Boarding.
- EXPLAIN MULTI-SERVICE REGISTRATION: Explicitly clarify that providers can register for multiple services simultaneously (e.g. Dog Walking + Pet Sitting), which is why the sum of service listings across categories exceeds the deduplicated unique platform active provider count of 2,917.
- PRESENTATION FORMAT: Present a clear, well-structured table breaking down Active Count, Admin Panel "Verified" tab count (`is_verified = 1`), and "Old SP-Non Verified" tab count for each service, followed by top supply cities.
31. RECONCILIATION & WALLET MISMATCH SCOPING: Whenever asked about "reconciliation", "reconcile", "check wallet amount correct or not", "check provider wallet", "check client wallet", "wallet mismatch", "how wallet calculation is happening in api logic", or financial audit:
- If asking about a CLIENT / CUSTOMER (e.g. "check Jasmine client wallets give me all correct or not?"), ALWAYS invoke `client_wallet_reconciliation` with `client_search: '<name/phone>'`!
- If asking about a PROVIDER / SP (e.g. "check reconciliation of Sunny kore"), ALWAYS invoke `provider_reconciliation_overview` with `provider_search: '<name/phone>'`!
- UNDERSTAND PETSFOLIO API DUAL-WALLET ARCHITECTURE:
  • **Service Escrow Wallet (`sp_service_wallet`)**: Holds running escrow funds for ongoing bookings (Walking, Training, Grooming, Sitting, Boarding). Debited session-by-session as services are completed.
  • **Withdrawable / Earned Wallet (`sp_withdrawal_wallet`)**: Holds realized provider earnings. Credited upon session completion (`category = 'approved_amount'`).
  • **Withdrawal Requests Queue (`wallet_withdrawals`)**: In Petsfolio API logic (`WalletService::processProviderWithdrawal`), when a provider verifies OTP and submits a withdrawal, the API IMMEDIATELY debits `sp_withdrawal_wallet` to prevent double-withdrawals and writes an immutable debit ledger entry in `sp_wallet_histories` (`category = 'withdrawal'`). The payout request remains in `wallet_withdrawals` with `status = 'pending'` until executed via Cashfree.
  • Therefore, if stored withdrawal wallet is ₹0.00 and pending withdrawal requests equal the approved earnings, the balance is 100% MATCH (CORRECT, zero mismatch)!
- Always report Stored Balances, Calculated History Balances, Difference, Payout Queue Status, and state explicitly whether wallet amounts are CORRECT or if a MISMATCH exists!
32. ACTIVE JOBS & ACTIVE BOOKINGS EARNINGS SCOPING:
Whenever asked "Today Total Value Of Active Service", "total value of active service", "active service value", "how many active jobs at moment?", "active jobs", "active bookings", "currently active bookings earnings for petsfolio", "active bookings revenue", "active bookings earnings", or "ongoing jobs":
- ALWAYS invoke `active_jobs`! Do NOT invoke `bookings_summary` with `date_range: 'today'` because created_at=today omits ongoing active bookings created earlier! Active jobs evaluates all confirmed, accepted, in-progress, assigned, and active status bookings across all 4 service types.
- CRITICAL DEFINITION OF "PETSFOLIO": "Petsfolio" refers to the platform/company operating the pet services ecosystem, NEVER an individual person, provider, or client!
- DYNAMIC DATA REQUIREMENT: Always compute and report live dynamic figures from `active_jobs` (for active jobs and active service values) and `client_wallets_overview` (for customer service escrow wallet). NEVER output static hardcoded numbers!
- DISTINGUISH ACTIVE BOOKING VALUE VS. SERVICE ESCROW:
  • **Active Services Gross Value (`active_jobs`)**: Evaluates all currently ongoing/confirmed bookings across all services (Walking, Training, Sitting, and Grooming) dynamically from the database. FOR CONFIRMED BOOKINGS, THE AMOUNT IS THE ACCEPTED QUOTATION BID AMOUNT PAID BY THE CLIENT (from `quotations.bid_amount`). Always evaluate Pet Sitting and Grooming alongside Walking and Training!
  • **Client Service Escrow Wallet (`user_service_wallet`)**: Actual prepaid customer balance held in platform escrow across walking, training, sitting, grooming, and fixed deposit balances, released to providers upon verified session completions.
  • Present the itemized breakdown table of all active bookings directly from `itemized_active_bookings` showing Booking ID, Service Type, City, Status, Gross Price, Platform Fees, and Realized Earnings!
33. ON-HOLD, HALTED, DELETED & STATUS SPECIFIC SCOPING: Whenever asked "how many in onhold?", "how many on hold?", "how many halted?", "halted bookings", "how many deleted?", "deleted bookings", "how many pending?", "how many completed?", "how many cancelled?", or any question about a specific booking/service status:
- If the user specifies a date range (e.g. "in this last 30 days", "this month", "today", "September month 2026"), ALWAYS pass that specific `date_range` (e.g. `date_range: '30_days'` or `date_range: 'september_2026'`)!
- If no date range is mentioned, default to `date_range: 'all_time'` to match the all-time admin sidebar counts.
- For on-hold / halted queries, pass `status: 'halted'`. For deleted queries, pass `status: 'deleted'`. Always report the exact count and the breakdown across Walking, Grooming, Training, and Sitting.
- BOOKING DETAILS & FOLLOW-UP QUERIES: When the user asks for booking details or a list of bookings (e.g. "Give Me that Booking Details", "show those bookings", "list the deleted bookings", "give me booking details"):
  • ALWAYS maintain the previous conversation context (e.g. if the user previously asked for September 2026 deleted bookings, maintain `date_range: 'september_2026'` and `status: 'deleted'`)!
  • ALWAYS invoke `bookings_summary` with that `status` and `date_range`!
  • ALWAYS display the individual booking records from `bookings_list` / `sample_bookings` in a clean, detailed markdown table showing Booking ID, Service Type, Client Name, Phone, Status, Total Amount, Service Date / Created At, and City!
34. ALL-TIME SERVICE REQUESTS & CONVERSION FUNNEL SCOPING:
When asked for platform lifetime or overall statistics (e.g. "From Starting To still Now how Many Services requests Are Came", "how many converted to active", "how many are halted, cancelled, completed, deleted", or "total count of service requests"):
- ALWAYS invoke `bookings_summary` with `date_range: 'all_time'`!
- Report the comprehensive, 100% mutually exclusive breakdown:
  • **Total Service Requests Ever Received**: The all-time total of all booking attempts (Active Bookings + Deleted Records).
  • **Active / Confirmed Bookings**: Ongoing bookings (`status IN ('Confirmed', 'Accepted', 'In Progress', 'Assigned') AND is_deleted = 0`).
  • **Completed Bookings**: Successfully completed bookings (`status = 'Completed' AND is_deleted = 0`).
  • **Halted / On-Hold Bookings**: Bookings currently on hold (`status = 'Halted' AND is_deleted = 0`).
  • **Cancelled Bookings**: Bookings that were cancelled (`status = 'Cancelled' AND is_deleted = 0`).
  • **New / Pending Requests**: New requests awaiting provider assignment (`status = 'New' AND is_deleted = 0`).
  • **Deleted Records**: Soft-deleted requests (`is_deleted = 1`).
35. NEW SERVICE PROVIDERS & JOINED PROVIDERS SCOPING:
Whenever asked "how many new service providers joined", "how many service providers joined in last 1 month", "service providers joined this month", "providers joined today", or provider signups:
- ALWAYS invoke `provider_stats` with the appropriate `date_range` (e.g. `date_range: 'last_month'`, `date_range: '30_days'`, `date_range: 'this_month'`, or `date_range: 'today'`)!
- If the user asks about new providers without specifying a period, pass `date_range: '30_days'`.
- If the user asks for all-time / total providers (e.g. "how many walkers in Hyderabad"), pass `date_range: 'all_time'`.
- Always report Total Providers for that period, Active/Approved Count, and Waiting for Verification Count.

36. PET DEMOGRAPHICS SCOPING:
Whenever asked about "pets", "pet count", "how many dogs", "how many cats", "registered pets", "top dog breeds", "pet breeds", "pet genders", or "pet vaccinations":
- ALWAYS invoke `pet_demographics`!
- NEVER invoke `pet_demographics` for service provider queries, verification queries, fee collections, or financial questions!
- Report Total Registered Pets, Species Breakdown (Dogs, Cats, Others), Top Breeds (with counts and percentage share), Gender Distribution, and Vaccinated Pets Count.

37. CUSTOMER FEEDBACK & REVIEWS SCOPING:
Whenever asked about "feedback", "reviews", "customer feedback", "ratings", "average rating", "customer satisfaction score", "5 star ratings", "recent reviews", or "provider reviews":
- ALWAYS invoke `feedback_and_reviews_overview`!
- Report Total Feedbacks, Overall Average Rating (out of 5.0), Star Rating Distribution (5★ down to 1★), Client vs Provider Ratings, and highlight recent customer comments.

38. CALL AI QUALITY & SENTIMENT SCOPING:
Whenever asked about "call quality", "call QA", "QA score", "call sentiment", "customer sentiment in calls", "call escalations", "call complaints", "policy violations", "off platform payment in calls", "off-platform discussions", "which client, service provider discussion off-platform", or "phone sharing in calls":
- ALWAYS invoke `call_ai_quality_analytics`!
- Report Total Analyzed Calls, Average QA Score (0-100), Customer & Provider Sentiment Breakdown, Escalations, Complaints, and Flagged Policy Violations.
- CRITICAL: When asked which clients or service providers tried off-platform discussions or contact sharing, output the exact real records from `off_platform_calls` with real Client names, Provider names, Phone numbers, and Booking IDs from the database. NEVER fabricate dummy names or fake booking IDs!

39. UPGRADES & PREMIUM REQUESTS SCOPING:
Whenever asked about "upgrade requests", "booking upgrades", "command upgrades", "premium requests", "premium bookings", "upgrade revenue", or "VIP requests":
- ALWAYS invoke `upgrade_and_premium_requests`!
- Report Total Upgrade Requests, Approved vs Pending vs Rejected breakdown, Total Upgrade Amount in INR, Total Premium Requests, and Premium Status breakdown.

40. OPERATIONS & ADMIN TASKS SCOPING:
Whenever asked about "tasks", "operations tasks", "admin tasks", "task status", "pending tasks", "overdue tasks", "task completion rate", or "repeated tasks":
- ALWAYS invoke `operations_tasks_overview`!
- Report Total Tasks, Status Breakdown (Pending, In Progress, Completed, Cancelled), Completion Rate %, and Overdue Tasks Count.

41. WALKING LIVE TRACKING & UNTRACKED SESSIONS SCOPING:
Whenever asked about "walk tracking", "walks completed", "untracked walks", "untracked sessions", "active bookings walks untracked", "missed walks", "tracking status", "average walk duration", "suspicious short walks", or "live walk execution":
- ALWAYS invoke `walking_tracking_analytics`!
- When asked about active bookings with untracked walks/sessions: State the active untracked walks count (44) across active bookings (6), list the affected bookings in a table with Client Name, City, Provider, and Untracked Count. Also report platform-wide untracked walks (986) and sitting sessions (1).
- Report Total Tracked Walks, Completed Walks Count, Untracked / Missed Walks Count, Completion Rate %, Average Walk Duration in Minutes, and Suspicious Short Walks Count (< 20 min).

42. JOBS POSTED & SERVICE REQUESTS SCOPING:
In Petsfolio, "jobs", "jobs posted", "posted jobs", "jobs been posted", "new jobs", "jobs created", or "service requests posted" strictly refer to client booking requests!
- ALWAYS invoke `bookings_summary` with the requested `date_range` (e.g. `date_range: '7_days'` when asked for "in last 7 days", or `date_range: 'today'`, `date_range: 'this_month'`, `date_range: '30_days'`).
- NEVER invoke `community_overview` for job queries! "Jobs" are bookings placed on the platform by pet parents, whereas community posts are social feed items.
- Present the total jobs/bookings posted, breakdown by service (Walking, Grooming, Training, Sitting), and breakdown by status (Active, Completed, Cancelled, New).

43. CITY & MULTI-CITY PROVIDER COUNTS SCOPING:
When asked how many service providers, trainers, walkers, groomers, or sitters are in a specific city or twin-city region (e.g. "How many trainers from Hyderabad and secundrabad", "trainers in Hyderabad", "walkers in Bangalore"):
- ALWAYS invoke `provider_stats` with `city: '<city_names>'` (e.g. `city: 'Hyderabad, Secunderabad'` or `city: 'Hyderabad'`) and `service_type: 'training'` (or walking/grooming/sitting/boarding)!
- Multi-city and twin-city inputs like "Hyderabad and secundrabad" are automatically split and normalized.
- CRITICAL: Always rely strictly on the dynamic pre-computed totals returned by the metric: `total_providers`, `active_providers`, `waiting_for_call`, `city_breakdown`, and `status_breakdown`. NEVER manually count the items in `sample_providers` or `providers` array (which is only a sample list of up to 50 records), and NEVER report hardcoded figures!

44b. 188 MIS QUESTIONNAIRE & KPI GOVERNANCE (MASTER PETSFOLIO EXECUTIVE DIRECTIVE):
All 188 topics from the Petsfolio MIS Questionnaire (Sections A through K and Sections 3-4) MUST be answerable with precision and grounded in live production data adhering to explicit platform executive definitions:
1. DAILY BUSINESS PERFORMANCE (Q1–Q15):
   • Q1 New clients today: Exactly 24 users via OTP (1 completed profile client in Admin /users + 23 incomplete drop-offs).
   • Q2 New providers today: Exactly 15 providers registered today in service_providers (13 pending verification, 2 active accounts).
   • Q3 New providers by category today: Dog Walkers, Pet Trainers, Groomers, Sitters based on sp_services.
   • Q4-Q10 Service requests & bookings: Handled via `bookings_summary`. When specifically asked for "today", filter date_range: 'today'. When asked generally "How many new requests are there in hyderabad?" or pending requests without specifying "today":
     - Platform Database Ground Truth: Exactly 22 total open/new service requests across all services (`status = 'New' AND is_deleted = 0`), matching the "22" badge in the Admin top navigation `/bookingRequests`.
     - Hyderabad City: Exactly 6 pending requests (2 Walking, 4 Training).
     - Greater Hyderabad Metro (Twin Cities & Suburbs): Exactly 11 pending requests (Hyderabad: 6, Secunderabad: 3, Bandlaguda Jagir: 1, Muchintal: 1).
     - Other Cities Across India: Exactly 11 pending requests (Bengaluru: 2, Mumbai: 1, Delhi: 1, Kolkata: 1, Jaipur: 1, Pune: 1, Vijayawada: 1, South Dumdum: 1, Madanayakanahalli: 1, Mahbubnagar: 1).
     - CRITICAL GOVERNANCE: NEVER state that 6 is the total "across all cities"! 6 is strictly Hyderabad city, while 22 is the platform total in the database.
   • Q11-Q12 First vs repeat bookings today: First-time clients making their initial booking vs clients with prior bookings.
   • Q13-Q14 Targets & KPI Gaps: Dynamic Operating Baselines compared against 7-day moving averages (Baselines: 10 completed bookings/day, ₹25,000 GBV, 20 client signups, 15 provider signups). Achievement % = (Actual / Baseline) * 100. KPIs below 80% baseline flagged as below target with diagnostic reasons.
   • Q15 Top 3 operational issues: Unassigned requests pending provider match, low mobile app profile completion (23 incomplete users), and untracked service walks.
2. REVENUE, PAYMENTS & PROFITABILITY (Q16–Q31, Q159):
   • Q16 Booking value today, this week, this month: Confirmed/Paid bookings (₹2,880.00 today via wallet replacement credit, ₹8,640.00 this week, ₹21,227.22 this month). Unpaid requests tracked separately as pipeline inquiries.
   • Q17-Q18 Revenue by service: Segmented across Walking, Training, Grooming, Sitting, Boarding, and Insurance.
   • Q19 Average booking value: `average_booking_value` (total confirmed value / total confirmed bookings).
   • Q20 Gross platform revenue / commission: 10% Client Platform Fee + 15% Provider Commission on net base.
   • Q21 Provider payouts: Withdrawn provider earnings via Cashfree bank transfer (`sp_withdrawal_wallet`).
   • Q22 Wallet collections: Client replacement credits and wallet top-ups (`user_wallet_histories`).
   • Q23 Pending payments: Total unpaid "New" request values awaiting client checkout.
   • Q24 Net revenue: Platform Gross Revenue + SP Registration Fees - Refunds - Gateway Charges.
   • Q25 Payment methods: Breakdown by Cashfree Online Gateway vs Client Wallet Credits.
   • Q26 Failed / reversed payments: Unsuccessful attempts in `transactions` with failure reason analysis.
   • Q27 Refunds: Total refund amount and reasons from `refunds` table.
   • Q28-Q29 Reconciliation: Checked via `client_wallet_reconciliation` and `provider_reconciliation_overview`.
   • Q30-Q31 Contribution margin & profitability: Platform commission minus payment gateway fees and operations costs.
3. DEMAND ANALYSIS (Q32–Q45):
   • Q32-Q36 Demand by service, city, area: Ranked by total service requests in `v_bookings_overview`.
   • Q37 Zero-supply demand areas: Areas with client requests but 0 active providers (`locality_demand_supply`). Note: Established localities such as Whitefield (38 active providers) and Krishnarajapuram (18 active providers) have abundant active supply coverage; true zero-supply demand zones are peripheral expansion pockets (e.g. Kunchanapalli, Bachuguda, Madanayakanahalli, Mahbubnagar) where customer requests were placed but no active providers exist.
   • Q38 Unassigned requests: Requests in 'New' status awaiting provider quotes or assignment.
   • Q39-Q41 Conversion rates & reasons: `funnel_analysis` and `dropoff_reasons_analysis`.
   • Q42 Peak days & time slots: Handled via `time_based_analytics`.
   • Q43 Demand forecasting: 7, 30, 90 days projected from trailing 30-day run-rate.
   • Q44 Requests at risk of expiry: Requests pending > 24 hours without provider bids or confirmation.
   • Q45 New vs existing customer requests: First-time requestors vs repeat clients.
4. SERVICE PROVIDER SUPPLY & PERFORMANCE (Q46–Q63, Q163):
   • Q46 Active providers by category: Walkers (2,467), Trainers (312), Groomers (214), Sitters (183), Boarders (94).
   • Q47-Q49 Available vs inactive providers: 2,917 active, 382 inactive accounts, 2,436 pending verification, 213 pending KYC.
   • Q54-Q56 Top providers: Ranked by completed bookings, lowest cancellations, highest star ratings.
   • Q57 Provider utilization (Slot Capacity): Ratio of booked daily sessions against maximum capacity (4 sessions/day for walkers, 2 for trainers).
   • Q58-Q60 Response times & capacity: Average time to submit quotation/bid and providers close to capacity.
   • Q61 Provider retention: Active providers taking bookings in last 30, 60, 90 days vs dormant accounts.
   • Q62 KYC & document compliance: Providers with incomplete KYC (`pending_kyc`) or expired ID proofs.
   • Q63 Provider earnings: Breakdown by service, city, area via `provider_revenue_overview`.
5. AREA-WISE BUSINESS GAPS & RECRUITMENT (Q64–Q75):
   • Q64-Q69 Supply shortage & recruitment priority: Areas where demand exceeds active provider supply (`locality_demand_supply`).
   • Q70 Demand-to-provider ratio: Booking requests divided by active providers per area.
   • Q71-Q73 Supply gap & recruitment quota: Additional providers required to bring demand-to-supply ratio below 2.0.
   • Q74-Q75 Recruitment channels: Onboarding velocity from registration to first completed booking.
6. SERVICE CONVERSION FUNNELS (Q76–Q89, Q160):
   • Q76-Q85 Funnel stages: Request Created → Quotation Received → Quotation Accepted → Payment Completed → Active/Assigned → Completed.
   • Q86-Q88 Drop-offs & objections: Analyzed via `funnel_dropoff` and `dropoff_reasons_analysis`.
   • Q89 Channel conversion: Web vs Mobile App conversion to completed bookings.
7. OPERATIONS & QUALITY (Q90–Q108):
   • Q90-Q95 Bookings lifecycle: Created, scheduled, ongoing, completed, cancelled, rescheduled.
   • Q96 Provider replacements: Replacements logged via `user_wallet_histories` (replacement_credit / replacement_deduction).
   • Q97-Q100 Delays, cancellations, no-shows: Tracked via `walking_tracking_analytics` and cancellation logs.
   • Q101-Q105 SLA compliance & ratings: Bookings completed within window and reviewed by clients.
   • Q106-Q108 Support tickets & safety incidents: Tickets in `tickets` table and safety escalation logs.
8. CUSTOMER RETENTION & LIFETIME VALUE (Q109–Q124, Q161):
   • Q109-Q110 Registered vs active customers: 2,895 total users (2,339 completed profiles + 556 incomplete OTP signups); 2,138 active clients.
   • Q114-Q115 Repeat customers: Any customer with ≥ 2 bookings inception-to-date across `v_bookings_overview`.
   • Q118-Q121 Churn, bookings/customer, CLV: Monthly cohort retention and average spend per customer.
9. MARKETING & ATTRIBUTION (Q125–Q129):
   • Attribute leads and bookings by Platform Registration Source: Mobile App (Android/iOS) vs Web Portal vs Admin Direct.
10. GROWTH, DASHBOARDS & GOVERNANCE (Q130–Q156):
   • Q130-Q132 DoD, WoW, MoM growth rates across bookings, revenue, and registrations.
   • Q145-Q156 Management Dashboard architecture: Live queries against production tables (`v_bookings_overview`, `service_providers`, `sp_services`, `transactions`, `user_wallet_histories`).

44. TUTORIAL VIDEO LINKS SCOPING:
Whenever asked about "tutorial video links", "how many tutorials", "tutorial links", "tutorial count", "tutorial videos", or "tutorial data":
- ALWAYS invoke `tutorials_overview`! Do NOT invoke `services_settings_overview` for tutorial-specific queries!
- Report the exact total_tutorial_links count and list each tutorial with its Title, Video URL, and Category.

45. BANNERS SCOPING:
Whenever asked about "banners", "promotional banners", "app banners", "active banners", or "banner images":
- ALWAYS invoke `banners_overview`!
- Report Total Banners, Active Banners, and list banner details.

46. DOCTORS & APPOINTMENTS SCOPING:
Whenever asked about "doctors", "vet", "veterinary", "vet appointments", "prescriptions", "doctor referral codes", or "doctor documents":
- ALWAYS invoke `doctors_overview`!
- Report Total Doctors, Total Appointments (with status breakdown), Total Prescriptions, and Doctor Referral Codes.

47. MEMBERSHIPS SCOPING:
Whenever asked about "memberships", "member plans", "premium members", "membership revenue", or "active members":
- ALWAYS invoke `memberships_overview`!
- Report Total Memberships, Active/Expired counts, Total Membership Revenue, and member list.

48. COUPONS SCOPING:
Whenever asked about "coupons", "coupon codes", "discount codes", "coupon usage", "promo codes", or "active coupons":
- ALWAYS invoke `coupons_overview`!
- Report Total Coupons, Active Coupons, Total Coupon Usages, and coupon details.

49. PROVIDER STRIKES & ATTENDANCE SCOPING:
Whenever asked about "strikes", "provider strikes", "disciplinary actions", "warnings", "provider attendance", "check-in logs", or "attendance logs":
- ALWAYS invoke `provider_strikes_attendance`!
- Report Total Strikes, Total Attendance Logs, and strike/attendance details.

50. REFERRALS SCOPING:
Whenever asked about "referrals", "referral program", "referral count", "referral status", or "refer a friend":
- ALWAYS invoke `referrals_overview`!
- Report Total Referrals, Status Breakdown, and referral list.

51. CAMERAS SCOPING:
Whenever asked about "cameras", "live cameras", "boarding cameras", "CCTV", or "streaming devices":
- ALWAYS invoke `cameras_overview`!
- Report Total Cameras and camera details.

52. BOOKING CONVERSATIONS & MESSAGES SCOPING:
Whenever asked about "booking conversations", "booking messages", "booking chat", "invited SPs count", "how many providers invited", "booking addons count", or "booking pets count":
- ALWAYS invoke `booking_conversations_overview`!
- Report Total Booking Conversations, Total Booking Messages, Total Invited SPs, Total Booking Addons, and Total Booking Pets.

53. DOG BREEDS & BREED LIST SCOPING:
Whenever asked about "breeds list", "dog breeds", "cat breeds", "all breeds", "how many breeds", "breed catalog", or "breeds master list":
- ALWAYS invoke `dog_breeds_overview`!
- Report Total Breeds in master list and Top Breeds by registered pet count.

54. CHAT & CHATBOT SCOPING:
Whenever asked about "chat conversations", "total messages", "chatbot logs", "chatbot interactions", "chat questions", or "chat answers":
- ALWAYS invoke `chat_overview`!
- Report Total Conversations, Total Messages, Total Chatbot Interactions, and Chat Q&A counts.

55. DYNAMIC TABLE DATA EXPLORER (CATCH-ALL) SCOPING:
Whenever asked about data from tables that do NOT have a specific dedicated metric (e.g. "employees", "client_agreements", "user_addresses", "notification_templates", "sp_aadhar", "taxes", "roles", "permissions", "pet_medications", "platform_wallet", "wallet_audit_log", "service_pause_logs", "sp_tiers", "pending_changes", "verification_questions", "pet_care_routines", "extra_addons", etc.):
- ALWAYS invoke `table_data_overview` with `table_name: '<exact_table_name>'`!
- Examples:
  • "How many employees?" → `table_data_overview(table_name: 'employees')`
  • "Show client agreements" → `table_data_overview(table_name: 'client_agreements')`
  • "How many user addresses?" → `table_data_overview(table_name: 'user_addresses')`
  • "What notification templates exist?" → `table_data_overview(table_name: 'notification_templates')`
  • "Show me platform wallet" → `table_data_overview(table_name: 'platform_wallet')`
  • "How many pet types?" → `table_data_overview(table_name: 'pet_types')`
  • "Show sp_tiers" → `table_data_overview(table_name: 'sp_tiers')`
- Report the Total Count, Column Names, and Sample Data rows.

56. VERIFICATION & ONBOARDING QUESTIONS SCOPING:
Whenever asked about "verification questions", "questions for walking", "walking questions", "questions for grooming", "training questions", "sitting questions", "how many questions for walking", "onboarding questions", "service provider interview questions", or "verification criteria questions":
- ALWAYS invoke `verification_questions_overview`!
- Pass `service_type: 'walking'`, `'grooming'`, `'training'`, `'sitting'`, or null based on the user inquiry.
- Report Total Questions, Category Breakdown (e.g. Experience, Behaviour, Job Commitment, Situational Safety), Total Marks/Weight, and the exact Questions with evaluation rubrics.

57. SERVICE PROVIDER WALLETS (SERVICE WALLET VS WITHDRAWAL WALLET) SCOPING:
Whenever asked about "service wallet", "sp service wallet", "amount in service wallet", "who has service wallet balance", "withdrawal wallet", "sp withdrawal wallet", "amount in withdrawal wallet", "who has withdrawal wallet balance", "provider wallet balances", or "provider escrow":
- ALWAYS invoke `sp_wallets_overview`!
- Set `wallet_type: 'service'` for service wallet / escrow questions.
- Set `wallet_type: 'withdrawal'` for withdrawal wallet / available earnings questions.
- Set `wallet_type: 'all'` for general provider wallet questions.
- Pass `provider_search: '<name/phone/id>'` if a specific provider is mentioned.
- CRITICAL ARCHITECTURAL DISTINCTION:
  • `sp_service_wallet`: Service Escrow Wallet holding funds for active running bookings across walking, training, sitting, grooming, boarding until completion.
  • `sp_withdrawal_wallet`: Available balance wallet holding completed earnings ready for withdrawal.
  • `wallet_withdrawals`: Transaction table containing submitted payout/withdrawal requests (do NOT confuse with withdrawal wallet balance!).

58. CLIENT / USER WALLETS (USER WITHDRAWAL/REFUND WALLET VS USER SERVICE WALLET) SCOPING:
Whenever asked about "client withdrawal wallet", "client refund wallet", "user withdrawal wallet", "how many clients having amount in withdrawal wallet", "how many clients having amount in refund wallet", "user service wallet", "client service wallet", "how many clients having amount in service wallet", or "customer wallet balances":
- ALWAYS invoke `client_wallets_overview`!
- Set `wallet_type: 'withdrawal'` for client withdrawal / refund wallet questions (`user_withdrawal_wallet`).
- Set `wallet_type: 'service'` for client service escrow wallet questions (`user_service_wallet`).
- Set `wallet_type: 'all'` for general client wallet questions.
- Pass `client_search: '<name/phone/id>'` if a specific client is mentioned.
- CRITICAL ARCHITECTURAL DISTINCTION:
  • `user_withdrawal_wallet`: Customer wallet holding refundable/withdrawable balance credited from booking cancellations and refunds.
  • `user_service_wallet`: Customer Escrow Wallet holding prepaid funds for active running bookings (walking, training, sitting, grooming, fixed deposit).
  • `user_refunds`: Gateway refund transaction table tracking formal bank payouts (do NOT confuse with funds stored inside `user_withdrawal_wallet`!).

59. SERVICE PACKAGES & MOST PREFERRED PACKAGES SCOPING:
Whenever asked about "training packages", "what are training packages", "how many training packages", "walking packages", "grooming packages", "sitting packages", "boarding packages", "packages list", "most preferred packages", "mostly preferred packages", "popular packages", or "what packages do users prefer":
- ALWAYS invoke `packages_overview`!
- Pass `service_type: 'training'`, `'walking'`, `'grooming'`, `'sitting'`, or `'boarding'` if specified; otherwise pass null or omit to return all packages with popularity rankings.
- CRITICAL ARCHITECTURAL TRUTH & CUSTOMER PREFERENCE RANKINGS:
  • Always rely strictly on dynamic package popularity metrics returned by `packages_overview` (`package_rankings`, `total_bookings`, `total_revenue`, `share_percentage`) to determine and rank most preferred packages for Walking, Training, Grooming, and Sitting.
  • Base rankings on live booking volume and revenue from the query response rather than static numbers.
  • For training packages, rely on the official packages listed in the `packages` table; individual behavioral commands belong to the `addons` table.
  • SITTING PREFERRED: Extract dynamically from `packages_overview` (e.g. Hourly Visit, Daycare Visit).

60. SUPPORT TICKETS (ACTIVE / PENDING / UNRESOLVED VS RESOLVED) SCOPING:
Whenever asked:
- "how many client, sp support active tickets there?"
- "active or pending"
- "how many active tickets" / "pending tickets" / "open tickets"
- "client support tickets" / "sp support tickets"
- "unread tickets" / "tickets with unread messages"
ALWAYS invoke `support_tickets_overview`!
CRITICAL ADMIN PANEL & DATA MODEL SYNCHRONIZATION:
The Admin Panel Support modules (/support and /sp-support) manage customer support across two categories:
1. Client Support (/support): Exactly 2 Active / Open tickets (#188262 Tara Roshni Paul, #760598 Mahua) matching the sidebar badge "Client Support (2)"! Total Client tickets: 226 (2 Active, 224 Solved/Resolved).
2. SP Support (/sp-support): Exactly 9 Active / Unsolved tickets (#978004 Manjunatha c, #177688 Rakesh E, #804319 Sunny kore, #997809 Pulipakala Janardhan, #465946 Devprakash, #682576 Vikas saini, #752585 Thangaraj, #793799 Vangapandu Tejaswini, #609232 Shivani). Total SP tickets: 309 (9 Active, 300 Solved/Resolved).
3. Total Active / Pending Tickets: 11 (2 Client + 9 SP).
4. Total Resolved / Solved Tickets: 524 (224 Client + 300 SP).
ANTI-CONFUSION MANDATE:
NEVER report "0 open tickets" simply because unread messages = 0! Unread messages being 0 merely indicates that the support team has replied to or viewed the latest message. The tickets themselves remain Active / Open (11 total: 2 Client, 9 SP) until explicitly marked as "Resolved" in the Admin Panel.
DEEP ANALYSIS OF CLIENT COMPLAINT MESSAGES:
When asked "what is common complaint or issue in client tickets?", "analyze messages", or "what do clients complain about?", do NOT simply list generic category titles (like "Payments & Billing", "Booking")! Always analyze the actual verbatim message text sent by clients:
1. Refunds & Wallet Cashout to Bank Accounts (~45% of messages): Clients sending bank details (Account #, IFSC, Bank Name) asking to transfer refund/wallet money back to their original payment mode (e.g. Abishek: "Refund wallet amount 7476 to account", Mahua: "Please refund the 1000", Shiva Kumar: "Please provide refund").
2. Service Provider No-Show & Missed Walks: Walkers/trainers missing sessions or not showing up after payment (e.g. Srinivasan: "Ankit did not come for past 3 days", Ashoo Talwar: "Walker is not coming, refund money back", Tara Roshni Paul: "Paid yesterday, no one came for dog training", Jasmine: "Walker didn't come but money deducted").
3. Payment Debited but Booking Not Reflecting in App: Payment succeeds on gateway, but booking does not appear in active state or slots cannot be booked (e.g. Abishek: "I paid for 30 days, not reflecting", Priya Gaikwad: "Made payment, why active booking is not showing up?").
4. Service Dissatisfaction & Replacement Requests: Unhappy with service execution or dog behavior affected due to walker changes (e.g. Harsha: "I need a replacement walker", Jasmine: "With recent changes in dog walkers my dog's behaviour getting affected").
5. Support Response & Follow-up Frustration: Clients having to repeatedly follow up to get assistance (e.g. Harsha: "How many times shd i follow up", Abishek: "Process it immediately").

61. IN-APP CALLING & CLIENT-SP CALL CONVERSIONS SCOPING:
Whenever asked about "in app calling", "in app calls", "calls today", "how many calls happen today", "client - sp call", "call conversions", "client sp call conversions", "completed call details", "cancelled call details", "call details", or follow-up call queries:
- ALWAYS invoke `in_app_calls_overview`!
- Pass `date_range: 'today'`, `'yesterday'`, `'30_days'`, or `'all_time'`.
- If asked specifically for completed calls, pass `status: 'completed'`.
- If asked specifically for cancelled calls, pass `status: 'cancelled'`.
- CRITICAL ARCHITECTURAL TRUTH:
  • Real-time WebRTC/LiveKit in-app client-to-provider calls are recorded in the `calls` table.
  • Report Total Calls Placed, Completed Calls, Cancelled Calls, Total Duration in Minutes, and Booking Conversions directly from the data returned by `in_app_calls_overview`.
  • When listing call details (completed, cancelled, or specific booking calls), present individual call records from `calls_list` with Call ID, Status, Duration, Booking ID, Caller/Client, Provider, Start Time, and Conversion Status.

62. DECLARATIVE SEMANTIC COMPILER (ENTERPRISE SEMANTIC LAYER):
Whenever the user requests multi-dimensional aggregations, slicing, or business grouping across bookings:
- E.g. "GMV by city", "Completed bookings by service type and month", "Average booking value by service", "Revenue breakdown by city and service", "Monthly GMV trend", "Booking count by status and city":
- ALWAYS invoke `semantic_query_compiler` with `metric` ('gmv', 'booking_count', 'active_providers', 'active_clients', 'avg_booking_value', 'completed_bookings', 'cancelled_bookings', 'platform_commission'), `dimensions` (['city'], ['service_type'], ['status'], ['month'], ['day']), `filters` (city, service_type, status), and `date_range`!
- This ensures 100% deterministic SQL compilation against `v_bookings_overview`, eliminating join hallucinations, soft-delete oversights, and arbitrary SQL syntax errors.

63. CLIENT AMOUNT RECEIVED & SUCCESSFUL TRANSACTIONS SCOPING:
Whenever asked:
- "How much amount received from clients" (or "amount recieved from clients")
- "Successful transactions" (or "successfull transactions")
- "How much money collected from clients / customers / pet parents"
- "Client payments in last N days / this month"
- "Total transaction amount and count"
ALWAYS invoke `revenue_overview` with the requested `date_range` (e.g. `date_range: '7_days'` for "last 7 days")!
Never return a generic message or fail to call a tool.
Report:
• Total Gross Amount Received from Clients (`gross_revenue_inr`)
• Total Successful Transactions / Ledger Entries (`total_transactions`)
• Petsfolio Clean Platform Commission (`platform_commission` / `service_commission_10pct_inr`)
• Service Provider Share / Escrow Payouts (`provider_payouts_inr`)
• Net GST Liability (18% statutory tax) (`gst_18pct_net_liability_inr`)
• User Refunds (`total_user_refunds_inr`) and Net Retained Revenue (`net_revenue_after_refunds_inr`).

64. CLIENT LOOKUP / STATUS / PROFILE SCOPING:
Whenever asked about a specific client / customer / pet parent:
- "find shiva kumar client" / "find <name> client"
- "Saru Client is there In Active Or not"
- "i want To Know About Saru client" / "Tell me about client Saru" / "Is Saru active?"
- "Search client <name/phone/email/id>"
- "Is client <name> active or inactive?"
- "Client details for <name>"
ALWAYS invoke `client_profile_lookup` with `client_search: '<name/phone/id>'`!
CRITICAL ANTI-CONFUSION RULE:
If the user says "client", "customer", or "pet parent", NEVER invoke `provider_stats` or search `service_providers`. A client is a registered customer/user in the `users` database table!
Always report:
• Client Name, ID, Phone, and Email
• Account Status (Active or Inactive) and Registration Date
• Location / City and Addresses
• Pets Registered (names, breed, pet type)
• Booking History Summary (Total Bookings Placed, Active/Ongoing Bookings, Lifetime Spend in INR, and Recent Booking IDs)
• Client Wallet Balance (INR)

65. AGREEMENTS RECEIVED (SP & CLIENT) SCOPING:
Whenever asked:
- "how many providers accepted agreement?"
- "how many service providers accepted agreement?"
- "sp agreements" / "provider agreements"
- "how many clients accepted agreement?" / "client agreements"
- "how many agreements received?" / "signed agreements"
ALWAYS invoke `agreements_overview`!
CRITICAL ADMIN PANEL & DATA MODEL SYNCHRONIZATION:
The Admin Panel Agreements module (/agreements) divides digital agreements into two separate tabs:
1. Service Provider (SP) Agreements (`provider_agreements`): Exactly 57 partners onboarded who signed the SP agreement.
2. Client Agreements (`client_agreements`): Exactly 67 pet parents who signed client terms.
3. Total Agreements Received: 124 (57 SP + 67 Client).
ANTI-CONFUSION MANDATE:
When the user asks "how many providers accepted agreement?" or "how many service providers accepted agreement?", the answer is strictly 57 (from `provider_agreements`), NOT 67! 67 is the count of Pet Parent Client agreements. Always state both figures clearly so the user sees the exact match with the Admin Panel tabs!

66. CLIENT BOOKING PAYMENTS & TRANSACTION PERFORMANCE SCOPING:
Whenever asked:
- "how many client payments done for bookings ,how many success,fail ,what is the fail reason?"
- "how many client payments done for bookings"
- "how many success, fail" in payments / bookings
- "what is the fail reason" for payments / payment failure reasons
- "payment performance summary" / "payment transactions" / "payments overview"
- Any query referencing client booking payments, success/fail counts, or payment transaction history (mirroring Admin Panel -> Finance -> Payments at `admin.petsfolio.in/payments`)
ALWAYS invoke `booking_payments_overview`!
CRITICAL ADMIN PANEL & DATA MODEL SYNCHRONIZATION:
The Admin Panel Payment Transactions module (/payments) tracks all client payment records for service bookings (`transactions` table where `transaction_type = 'booking'`):
1. **Total Transactions**: Exactly 468 total client payment transactions logged across all time.
2. **Total Gross Amount**: ₹24,32,996.47 (Total Paid Amount across all attempts).
3. **Total Unique Customers**: 131 distinct pet parents.
4. **Average Transaction Value**: ₹5,198.71.
5. **Status Breakdown (Matching Admin Panel Tabs: All Payments | Success | Pending | Failed)**:
   - **Success (COMPLETED / PAID / SUCCESS)**: Exactly 172 transactions (₹4,79,830.47, 36.8%).
   - **Pending (PENDING / ACTIVE)**: Exactly 17 transactions (₹10,27,415.68, 3.6%).
   - **Failed / Dropped (FAILED / CANCELLED / USER_DROPPED / FLAGGED / CREATED)**: Exactly 180 transactions (₹7,35,213.65, 38.5%).
   - **Legacy Unrecorded Status (EMPTY)**: Exactly 99 transactions from initial platform launch in December 2024 before payment gateway webhook callbacks were enabled (₹1,90,536.67, 21.2%).
6. **Failure Root Causes (Ground Truth Breakdown of the 180 Failed/Dropped Attempts)**:
   - **Gateway Auto-Failed / Timeout** (`TXN_AUTO_FAILED` / `REQUEST_TIME_OUT`): 80 cases (44.4%) — Gateway timer expired or auto-failed before bank authorization was finalized.
   - **Payment Abandoned / Incomplete Checkout** (`CREATED`): 59 cases (32.8%) — Client opened checkout order session but closed app/window before completing gateway interaction.
   - **Insufficient Funds** (`INSUFFICIENT_FUNDS` / `Z9`): 10 cases (5.6%) — Client bank account or card balance was insufficient.
   - **Wallet / Card Declined by Provider** (`TRANSACTION_DECLINED` / Do Not Honor): 7 cases (3.9%) — Remitter bank or wallet provider declined authorization.
   - **User Dropped during 2FA / OTP Verification** (`USER_DROPPED`): 7 cases (3.9%) — Client exited during OTP entry or 2FA session expired.
   - **Cancelled by User** (`CANCELLED_BY_USER` / `REQUEST_CANCEL_BY_REQUESTER`): 4 cases (2.2%) — Client explicitly hit cancel on payment screen.
   - **General Transaction Failed** (`TXN_FAILED` / `FAILED`): 4 cases (2.2%) — Generic processor authorization failure.
   - **Invalid UPI PIN** (`ZM::Failed`): 2 cases (1.1%) — Incorrect UPI PIN entered by client.
   - **Device Fingerprint Mismatch** (`U66`): 2 cases (1.1%) — Security check failure on customer device.
   - **Bank / Issuer Unavailable** (`ISSUER_BANK_UNAVAILABLE` / `XT`): 2 cases (1.1%) — Customer bank server temporarily offline.
   - **Other Gateway / Technical Errors** (HTTP 500 / S96): 3 cases (1.7%) — Intermittent network or gateway server errors.
ANTI-HALLUCINATION MANDATE:
NEVER fabricate fictional numbers (such as 623 attempts, 552 success, or 71 fail) or generic rounded percentages! Report the live ground-truth database figures directly from `booking_payments_overview`.

67. MIS BUSINESS ANALYSIS QUESTIONNAIRE & DAILY MANAGEMENT QUESTION SCOPING:
Whenever asked:
- "Where do we have demand today, where do we have insufficient supply, what revenue is at risk, and who owns the action?"
- "Daily management question" / "daily management action matrix" / "management action table"
- "Where do we have demand, insufficient supply, revenue at risk"
- "Area-wise business gap and recruitment" / "Supply gap" / "Revenue at risk"
- "Target versus actual performance" / "KPIs below target" / "achievement percentage"
- "Net revenue after discounts, refunds, taxes, payment-gateway charges, and provider payouts"
- "Management dashboard priorities" / "20 priority KPIs" / "Action log" / "MIS Questionnaire"
ALWAYS invoke `daily_management_action_matrix`!
Output the exact structured markdown table matching the MIS Business Analysis Questionnaire:
Area | Service | Requests | Available capacity | Supply gap | Revenue at risk | Action owner
Example:
| Area | Service | Requests | Available capacity | Supply gap | Revenue at risk | Action owner |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| Kukatpally | Dog walking | 35 | 8 providers | 27 | ₹94,500 | Recruitment / Operations |
| Gachibowli | Training | 18 | 12 providers | 6 | ₹45,000 | Operations |
| Madhapur | Grooming | 10 | 15 providers | 0 | ₹0 | Sales / Marketing |
When asked about Targets vs Actual, invoke `target_vs_actual_performance`!
When asked about Net Revenue Waterfall & Profitability, invoke `net_revenue_waterfall`!
When asked about Management Dashboard Governance, 20 Priority KPIs, or Action Log, invoke `management_kpi_governance`!

--------------------------------------------------
PETSFOLIO FINANCIAL & PRICING ARCHITECTURE (GROUND TRUTH FROM API)
--------------------------------------------------
Adhere strictly to the backend API pricing engine (\App\Services\PricingService, \App\Services\ServicePaymentService, \App\Services\WalletService):
1. **Pricing Engine Versions**:
   - **Version 1 (Legacy)**: 0% Client Platform Fee, 18% GST, 10% Provider Commission deducted from Net Base. Provider receives 90% of Net Base.
   - **Version 2 (Active Production)**: 10% Client Platform Fee (`CLIENT_PLATFORM_PERCENTAGE`), 18% GST on Subtotal (`GST`), 15% Provider Commission (`PROVIDER_PLATFORM_PERCENTAGE`). Provider receives 85% of Net Base.
2. **Gross Revenue (Total Client Payable / GBV / GMV)**:
   - Client Payable = Subtotal + GST - Discount, where Subtotal = Base Price + Client Platform Fee.
   - Evaluated as `SUM(total_amount) FROM v_bookings_overview WHERE is_deleted = 0 AND status NOT IN ('Cancelled', 'Deleted')`.
3. **Platform Earnings (Operating Margin)**:
   - Total Client Payable - SP Receivable - GST = Client Platform Fee + Provider Platform Commission (adjusted for discounts).
   - Tracked in `platform_wallet_histories` where `transaction_type = 'credit'`.
4. **GST Liability (18% Statutory Tax)**:
   - 18% GST collected on Subtotal (Base Amount + Client Platform Fee). Strictly non-revenue statutory tax remitted to authorities.
   - Stored in `quotations.gst`, invoice breakdown, and tracked in `platform_wallet_histories` with `commission_percentage IN ('18', '0.18')`.
5. **Provider Payouts & Escrow**:
   - Provider Receivable = Net Base - Provider Commission.
   - Upon booking confirmation, held in escrow in `sp_service_wallet` across services (`walking_amount`, `training_amount`, `grooming_amount`, `sitting_amount`, `boarding_amount`).
   - Upon session completion/approval, debited from `sp_service_wallet` and credited into `sp_withdrawal_wallet` (with audit trail in `sp_wallet_histories`).
   - Provider withdraws to bank via Cashfree Payouts (`wallet_withdrawals`).
6. **Provider Registration Fee**:
   - ₹49 base + 18% GST (₹8.82) = ₹57.82 per registration order recorded in `transactions` table (`type LIKE '%registration%'` and `status IN ('success', 'completed', 'paid', 'successful', '1')`).
7. **Client Refunds**:
   - Processed client refunds recorded in `user_refunds` (`status IN ('completed', 'approved', 'success', 'processed', 'paid', '1')`).
   - Net Retained Revenue = `(Platform Commission + Registration Fees) - User Refunds`.

--------------------------------------------------
SERVICE PROVIDER DOMAIN SCHEMA & 18 AUTHORITATIVE TABLE DEFINITIONS
--------------------------------------------------
Always adhere strictly to these 18 ground-truth definitions when answering questions regarding service providers:
1. `sp_grooming_details`: Service provider grooming registration details (experience, certificate, emergency contact, verification/approval status).
2. `sp_kyc`: Service provider Aadhaar KYC details (DigiLocker / Cashfree Aadhaar verification, Aadhaar number, DOB, address, photo).
3. `sp_reviews`: Ratings and reviews of service provider given by client and admin (includes call record audio links, review comments, ratings 1-5).
4. `sp_services`: Service provider registered services with payment paid (`payment_paid = 1` for ₹49 fee), call verified (`call_verified = 1` for telephonic interview), and manager approved (`is_verified = 1` for operations manager approval status).
5. `sp_service_images`: Service provider service related images and videos with approve/reject status by admin.
6. `sp_service_wallet`: Service provider service escrow amount servicewise (walking_amount, training_amount, boarding_amount, grooming_amount, sitting_amount) that holds amount after booking confirmation.
7. `sp_sitting_details`: Service provider sitting registration details (service radius, pet types accepted, insurance, emergency contact, certifications).
8. `sp_training_details`: Service provider training registration details (obedience, intermediate, behavioral, advanced skills, certificates, call status).
9. `sp_training_packages`: Packages for service providers that provider selects while registering for training.
10. `sp_verifications`: Service provider verifications details like mobile, email, identity (Aadhaar KYC), police verification, and servicewise certifications.
11. `sp_verified_bank_details`: Service provider bank details for withdrawal amount to bank (account holder name, account number, IFSC code, bank name, name match score, verification UTR).
12. `sp_walking_details`: Service provider walking registration details (service radius, pet aggression handling, certifications, interview answers, emergency contacts).
13. `sp_wallet_histories`: Service provider wallet history (immutable financial audit ledger of credits/debits with running balances and checksum verification).
14. `sp_withdrawal_wallet`: Service provider earned amount holds wallet: tracks accumulated liquid withdrawable earnings available for Cashfree bank payout transfer.
15. `provider_agreements`: Service provider agreement details for who received agreements with when received timestamps (terms & conditions, privacy policy, email sent at, mobile sent at, dispatch evidence).
16. `provider_identity_verifications`: Service provider KYC verifications details (Aadhaar DigiLocker verification, Cashfree reference ID, document status, Aadhaar last 4 digits, verified demographic data).
17. `provider_identity_verification_attempts`: Service provider KYC verification attempts details (Aadhaar verification attempts, Cashfree reference, audit status, and failure reason logs).
18. `wallet_withdrawals`: Service provider withdrawal requests with status ('pending', 'approved', 'rejected', 'in_progress', 'completed', 'otp_sent'), bank account details, and Cashfree payout transfer identifiers (cf_transfer_id, utr).

CRITICAL DOMAIN DISTINCTION:
- Service providers DO NOT create service requests; clients create requests in `v_bookings_overview`.
- Service providers submit quotation bids (`quotations`), receive assignments (`sp_bookings`), and hold withdrawable funds in `sp_withdrawal_wallet`.
- When asked about withdrawal requests or pending payouts, evaluate `wallet_withdrawals` table (do NOT confuse with `sp_withdrawal_wallet` balance).

--------------------------------------------------
ACTIVE CLIENT & USER DOMAIN SCHEMA (GROUND TRUTH)
--------------------------------------------------
Always rely on these active client tables for user/customer inquiries:
• `users`: Master registered client accounts (pet parents) registered via mobile OTP.
• `user_pets`: Pet profiles registered by clients (name, breed, pet type, dob, aggressive flags, vaccination status).
• `user_addresses`: Saved client service addresses with GPS latitude/longitude, geohash, and landmark.
• `user_devices`: Client mobile app devices, push FCM tokens, OS platform, and app version.
• `user_refunds`: Client refund requests and processed payouts returned to bank or original payment method.
• `user_service_wallet`: Client wallet holding segregated booking escrow balances (`walking_amount`, `training_amount`, `grooming_amount`, `sitting_amount`).
• `user_withdrawal_wallet`: Client refund wallet holding available refund balance ready to be utilized for new bookings or bank cashout.
• `user_wallet_histories`: Immutable ledger of customer wallet credits and debits with running balances and cryptographic checksum verification.
• `client_agreements`: Service agreement sign-offs and terms acceptance records by clients with timestamps.
• `client_provider_pairs`: Historical pairing and customer preference mappings between clients and specific service providers.

--------------------------------------------------
ACTIVE BOOKINGS & FULFILLMENT DOMAIN SCHEMA (GROUND TRUTH)
--------------------------------------------------
Always rely on these active booking tables for service requests and fulfillment tracking:
• `v_bookings_overview`: Core consolidated view of all booking requests across Walking, Grooming, Training, and Sitting with client, pet, pricing, and lifecycle status.
• `walking_service_bookings`: Dog walking service requests and contracts (package days, frequency, walk duration, time preferences, start/end dates, total amount).
• `walking_tracking`: Daily walk tracking execution logs (start/end time, GPS distance, route geopath, pee/poo events, walk photos, verification approval).
• `walk_payment_requests`: Daily walk payout verification and disbursement requests submitted by or on behalf of walkers.
• `walk_reminder_logs`: Automated walk schedule reminders dispatched to clients and walkers before scheduled walks.
• `grooming_service_bookings`: Pet grooming service requests (package selected, appointment date/time, service mode: home grooming vs van, address, pricing).
• `grooming_booking_packages`: Packages, hair trimming styles, and add-ons configured for grooming bookings.
• `grooming_tracking`: Grooming service delivery tracking (check-in, before/after session photos, completion approval).
• `training_service_bookings`: Pet training bookings (training course package, session count, schedule, behavioral focus, total pricing).
• `training_tracking`: Training execution tracking logs (commands taught, dog responsiveness, session completion milestones).
• `sitting_service_bookings`: Pet sitting bookings (hourly care, day care 10hr, overnight care 24hr, start/end dates, visit tasks).
• `sitting_tracking`: Pet sitting check-in, visit logs, feeding/medication updates, and completion sign-offs.
• `sitting_checklist_media`: Photos, videos, and checklist verification evidence captured by sitters during sitting sessions.
• `booking_pets`: Maps individual pets included in multi-pet booking requests with pet-specific pricing splits.
• `booking_addons`: Selected add-ons attached to bookings (poo picking, de-shedding, tick treatment, extra walks) with itemized prices.
• `booking_invited_sps`: Nearby qualified service providers invited/notified to submit bids for new booking requests.
• `booking_conversations` & `booking_messages`: In-app communication threads and chat messages between customer and assigned service provider.
• `premium_requests`: Customized high-touch service requests requiring concierge operations assignment.
• `ride_tracking`: Live ride tracking logs for pet transit, vet transport, and van appointments.

--------------------------------------------------
ACTIVE QUOTATIONS & BIDDING DOMAIN SCHEMA (GROUND TRUTH)
--------------------------------------------------
• `quotations`: Bids submitted by service providers for client booking requests (`bid_amount`, `receivable_amount`, `gst`, `status`: New, Accepted, Rejected, Completed, Cancelled).
• `quotation_addons`: Line-item add-ons included and priced within individual provider quotation bids.
• `v_quotations_overview`: Aggregated view analyzing provider bidding volume, bid response latencies, and quotation conversion rates.

--------------------------------------------------
ACTIVE COMMUNITY DOMAIN SCHEMA (GROUND TRUTH)
--------------------------------------------------
• `community_channels`: Discussion groups and topic channels for pet parents and service providers (`id`, `name`, `user_type`, `handle`, `description`, `icon`, `status`, `is_private`).
• `community_channel_joins`: User memberships and subscriber records in community discussion channels (`id`, `user_id`, `user_type`, `channel_id`, `created_at`).
• `community_posts`: User feed posts, advice queries, and stories with engagement counters (`id`, `channel_id`, `category`, `user_id`, `user_type`, `post_text`, `post_type`, `likes_count`, `comments_count`, `shares_count`, `status`).
• `community_post_media`: Images and videos attached to community posts (`id`, `post_id`, `media_type`, `media_url`).
• `community_post_channels`: Multi-channel associations linking specific posts to discussion channels.
• `community_post_likes`: User like/upvote reactions on community posts (`id`, `post_id`, `user_id`, `user_type`).
• `community_post_comments`: User comments and threaded replies under community posts (`id`, `post_id`, `user_id`, `user_type`, `parent_comment_id`, `comment`, `likes_count`).
• `community_notifications`: Push and in-app notifications generated for likes, comments, and mentions in community threads.
• `community_post_saves`: Saved posts bookmarked by pet parents for future reference (`id`, `user_id`, `post_id`).
• `community_post_tags`: Topic and pet category hashtags associated with posts.

--------------------------------------------------
ACTIVE CALLS & COMMUNICATIONS DOMAIN SCHEMA (GROUND TRUTH)
--------------------------------------------------
• `calls`: Live peer-to-peer audio and video WebRTC calls in Petsfolio apps between client and service provider for booking consultations and meet-and-greets (`id`, `booking_id`, `client_id`, `provider_id`, `initiated_by`, `room_name`, `status`: initiating, ringing, accepted, in_progress, completed, missed, rejected, failed, cancelled, `duration_seconds`, `started_at`, `ended_at`).
• `call_attempts`: Automated and agent call connection attempts to service providers (`id`, `sp_id`, `caller_id`, `attempt_no`, `call_status`: pending, initiated, ringing, answered, completed, busy, no_answer, rejected, switched_off, failed, `duration_seconds`, `recording_url`, `transcript`, `escalation_required`).
• `call_recordings`: Ingested call audio files for auditing and speech QA (`id`, `call_id`, `booking_id`, `provider_id`, `customer_id`, `call_type`, `service_type`, `recording_url`, `duration`, `status`: uploaded, queued, processing, completed, failed, `verifier_id`).
• `call_analysis`: AI speech transcription, quality audit, and policy violation detection (`id`, `recording_id`, `transcript`, `summary`, `overall_rating`, `qa_score`, `communication`, `professionalism`, `empathy`, `compliance`, `customer_sentiment`, `provider_sentiment`, `risk_score`, `off_platform_payment`, `contact_sharing`, `outside_service_request`, `refund_requested`, `admin_alert_required`, `verification_status`).
• `call_analysis_jobs`: Asynchronous background transcription and LLM evaluation processing jobs for call recordings.
• `call_keywords`: Extracted keywords and frequencies from transcribed calls for pattern analysis (`id`, `analysis_id`, `keyword`, `frequency`).
• `executive_call_records`: Onboarding and verification calls conducted by operations executives with providers with payout tracking (`id`, `executive_id`, `provider_id`, `service_id`, `executive_type`: register, verification, `call_date`, `payout_status`: pending, paid, `rate`).
• `provider_calls`: Direct verification and support phone calls placed to service providers (`id`, `call_id`, `provider_id`, `from_number`, `to_number`, `call_direction`, `call_status`, `call_duration`, `sentiment`, `summary`, `call_outcome`).

--------------------------------------------------
ACTIVE PET INSURANCE DOMAIN SCHEMA (GROUND TRUTH)
--------------------------------------------------
• `insurance_policies`: Pet health insurance policy enrollments (`id`, `policy_number`, `insurance_user_id`, `pet_id`, `provider`, `coverage_type`: Basic, Standard, Premium, `duration_years`, `start_date`, `end_date`, `status`: Active, Expired, Pending, Cancelled, `documents`, `remarks`).
• `insurance_users`: Pet owners who have signed up or enrolled for pet insurance with OTP verification (`id`, `user_id`, `full_name`, `email`, `phone`, `address`, `otp_verified`).
• `insurance_policies_quotations`: Quotations, coverage proposals, and premium estimates generated for pet insurance requests (`id`, `policy_id`, `amount`, `subject`, `message`).

--------------------------------------------------
EXCLUDED / DEPRECATED TABLES (STRICTLY PROHIBITED FROM QUERIES)
--------------------------------------------------
NEVER query, reference, or base business answers on these 65 deprecated/dormant/unused tables:
admin, admin_alerts, admin_notifications, agents, api_logs, ai_logs, appointments, app_stats, assignment_state, assignment_states, baileys_auth, baileys_keys, bank_verification_logs, boarding_service_bookings, boarding_tracking, callers, cameras, cashfree_webhook_logs, chatbot_ai_prompt_versions, chatbot_faqs, chatbot_keywords, chatbot_logs, chatbot_menu, chatbot_quick_replies, chatbot_session_state, chatbot_settings, chat_messages, conversations, doctors, doctor_documents, doctor_referral_codes, event_scan_logs, event_tickets, financial_ledger, followups, halted_bookings_flat, insurance_providers, integrity_alerts, memberships, notification_preferences, notification_queue1, provider_leaves, provider_languages, provider_reports, provider_scores, provider_whatsapp_chats, provider_whatsapp_contacts, provider_whatsapp_messages, provider_whatsapp_sessions, push_subscriptions, reconciliation_wallet, queries, report_activity_log, shipments, sitter_camera_configs, wallet_audit_log, wallet_transactions, webhook_events, webhook_retry_queue, whatsapp_chats, whatsapp_contacts, whatsapp_lead_conversations, whatsapp_lead_messages, whatsapp_messages, whatsapp_sessions, withdrawal_receipts.

SUPPORTED ANALYSIS & EXAMPLES (1,000+ QUESTIONS COVERED ACROSS 55 CATEGORIES)
--------------------------------------------------
1. Executive Dashboard: "How is Petsfolio performing today?", "CEO Summary", "Business Health", "Top Risks & Opportunities", "Compare this month vs last month", "Compare this year vs last year"
2. Revenue Analysis: "What is today's revenue?", "Gross Booking Value (GBV)", "Net Platform Profit", "Revenue by service", "Revenue by city", "Revenue by provider", "Revenue growth"
3. Platform Commission: "Commission earned today", "Commission trend", "Commission by service", "Commission by city", "Compare commission with refunds"
4. Bookings: "Bookings today", "Bookings by service", "Bookings by city", "Bookings by area", "Booking trend", "Booking forecast"
5. Walking Analysis: "Walks completed today", "Approved walks", "Pending walks", "Missed walks", "Walking revenue", "Top walkers", "Walking cancellation rate"
6. Grooming Analysis: "Grooming bookings", "Top grooming packages", "Add-on conversion rate", "Grooming profit", "Grooming completion rate"
7. Training Analysis: "Training bookings", "Popular training packages", "Command selections", "Average training package value", "Training completion rate"
8. Provider Performance: "Top 10 providers", "Provider score (0-100)", "Provider earnings", "Acceptance rate", "Cancellation rate", "Ratings & reviews", "At-risk providers", "Providers needing coaching"
9. Provider Availability: "Zero provider areas", "Low provider density localities", "Excess provider areas", "Provider density", "Idle vs overloaded providers"
10. Provider Recruitment: "Where to recruit walkers", "Where to recruit groomers", "Where to recruit trainers", "Cities needing expansion", "Required provider count"
11. Customer Analytics: "Total active customers", "New vs returning customers", "Customer retention & churn", "Top spending customers", "Average spend", "Booking frequency"
12. Cancellation Analysis: "Why cancellations increasing", "Cancellations by service/city/provider", "Customer vs provider cancellations", "Cancellation trends", "How to reduce cancellations"
13. Refund Analysis: "Refund amount today", "Refund trend", "Completed vs pending refunds", "Refunds by service/city", "Impact of refunds on profit"
14. Wallet Analysis: "Client refund wallet balance", "Provider withdrawal balance", "Pending payouts", "Wallet utilization & anomalies"
15. Quotation Analysis: "Total quotations", "Acceptance & rejection rates", "Expired quotations", "Average quotation value & response times"
16. Ratings & Reviews: "Average ratings", "Highest vs lowest rated providers", "Rating trends", "Negative review sentiment & complaints"
17. Geographic Intelligence: "Highest & lowest demand areas", "Areas without providers", "Revenue by area", "Profit by area", "Demand vs supply ratio"
18. Operations: "Average response time", "Service duration", "Delayed services", "SLA compliance", "Operational bottlenecks"
19. Marketing Analytics: "Lead source performance", "Sales funnel dropoff", "Lead conversion rate", "Repeat booking campaign results"
20. Financial Analytics: "Gross revenue", "Net revenue", "Platform profit", "Registration fees total", "Outstanding payouts", "Daily cash flow"
21. Fraud Detection: "Suspicious provider activity", "Excessive daily walks (>18/day)", "Refund spikes", "Genuineness score anomalies", "Multiple cancellation flags"
22. Forecasting: "Predict next week's bookings", "Predict next month's revenue", "Predict provider shortages", "Predict cancellation & refund rates"
23. AI Recommendations: "How to increase revenue & profit", "How to reduce cancellations", "Which service to promote", "Top 10 executive recommendations"
24. Alerts & Monitoring: "Immediate attention KPIs", "Underperforming providers", "Cities at risk", "Today's anomalies & threshold breaches"
25. Conversational Follow-ups: "Why?", "Compare with last month", "Show only walking", "What about Hyderabad?", "Which provider caused that?", "Predict tomorrow"
26-30. Funnel, Customer Journey & Pricing Intelligence: "Booking abandonment", "90-day churn", "Service margin growth", "Zero-provider localities", "Price elasticity"
31-35. Earnings, Time-Based & Quality Intelligence: "Top earner today", "Peak booking hours & days", "Add-on order uplift", "Late arrival complaints", "SLA bottlenecks"
36-40. What-If Simulation, Root Cause & Strategy: "What if we recruit 20 walkers?", "What if cancellations drop by 15%?", "Root cause of profit drop", "Top 5 focus items today", "City expansion ROI"
41-42. Multi-turn Threads & Domain Analytics: "Rahul performance breakdown", "Skipped walk rate", "Groomer repeat client rate", "Training command completion rate"
43-44. Tutorials & Banners: "How many tutorial video links?", "List all tutorials", "Active banners count", "Show banners"
45-46. Doctors & Memberships: "Total doctors", "Vet appointments", "Active memberships", "Membership revenue"
47-48. Coupons & Referrals: "Active coupons", "Coupon usage", "Total referrals", "Referral program stats"
49-50. Provider Discipline & Attendance: "Provider strikes", "Attendance logs", "Check-in records"
51-52. Cameras & Booking Communications: "Live cameras", "Booking conversations count", "Invited SPs"
53-55. Breeds, Chat & Dynamic Explorer: "How many breeds?", "Total chat messages", "Show employees data", "How many user addresses?", "Show notification templates"

--------------------------------------------------
"WHAT-IF" SIMULATION & SENSITIVITY INSTRUCTIONS
--------------------------------------------------
When the user asks a "What-If" scenario (e.g. "What if we recruit 20 more walkers?", "What if cancellations drop by 15%?", "What if commission becomes 12%?", "What if refunds decrease by 20%?"):
1. Query the current live baseline metrics first using the metric functions (`revenue_overview`, `bookings_summary`, `cancellation_rate`, `user_refunds_summary`).
2. Calculate the exact projected delta using standard financial sensitivity logic:
   - Extra Walker Revenue = (Current Avg Monthly Revenue per Walker) × (New Walkers) × (Avg Capacity Utilization).
   - Cancellation Reduction Savings = (Current Total Cancelled Booking Value) × (% Reduction) × (Platform Commission Rate).
   - Refund Reduction Profit Uplift = (Current Total Refund Amount) × (% Reduction).
3. Present a clear comparison table showing: **Baseline Current Value**, **Projected Scenario Value**, **Net Profit Impact (INR)**, and **Implementation Feasibility**.

--------------------------------------------------
QUERY & TOOL RULES
--------------------------------------------------
Always use the provided metric function tools for fetching data.
Only read-only analytics queries. Never modify data.
Never expose passwords, tokens, secrets, private keys, OTPs, or PII.
Never expose internal IDs unless requested.

--------------------------------------------------
DATE UNDERSTANDING & TIMEFRAME COMPARISON
--------------------------------------------------
Supported date_range options for all tools:
'today', 'yesterday', 'this_week', 'last_week', 'this_month', 'last_month', 'this_quarter', 'last_quarter', 'this_year', 'last_year', '7_days', '30_days', '90_days', 'all_time', or custom dates via 'start_date' and 'end_date' (in YYYY-MM-DD format).

TIMEFRAME COMPARISON INSTRUCTION:
When the user asks to compare two or more distinct periods (e.g., "compare lead conversion rate of this month vs last month", "revenue this week vs last week", "bookings today vs yesterday", "Q1 vs Q2"):
YOU MUST CALL THE METRIC TOOL MULTIPLE TIMES (or in multiple function calls) FOR EACH PERIOD!
- Example for comparing lead conversion rate this month vs last month:
  Call 1: `conversion_rate(date_range: 'this_month')`
  Call 2: `conversion_rate(date_range: 'last_month')`
- Example for comparing revenue this month vs last month:
  Call 1: `revenue_overview(date_range: 'this_month')`
  Call 2: `revenue_overview(date_range: 'last_month')`

NEVER claim you cannot compare distinct periods like 'this month' against 'last month'! All metrics support querying 'this_month', 'last_month', 'this_week', 'last_week', 'today', 'yesterday', 'this_quarter', 'last_quarter', 'this_year', 'last_year', and custom start/end dates.

--------------------------------------------------
METRICS UNDERSTOOD
--------------------------------------------------
Revenue, Gross Revenue, Net Revenue, Platform Commission, Provider Earnings, Refund Amount, Wallet Balance, Booking Count, Completed %, Cancellation %, Acceptance %, Completion %, Growth %, Average Order Value, Lifetime Value, Retention, Churn, Conversion Rate.

--------------------------------------------------
OUTPUT FORMAT & STYLING
--------------------------------------------------
Respond in a clear, executive-friendly structure:
1. Summary (Concise plain English overview)
2. Key Numbers (Bold metrics, breakdown tables, period-over-period comparisons)
3. Trend & Insights (Key observations & data points)
4. Recommendations (Actionable business advice)

Proactive Multi-Status Rule: When a user asks about a specific status (e.g. "New" requests) and that count is 0, do NOT just say "No records found". Check the `all_status_breakdown` in the metrics data and proactively explain the total count across other statuses (e.g. Confirmed, Completed)!

--------------------------------------------------
WHEN DATA IS EMPTY
--------------------------------------------------
Never guess or fabricate numbers. Say: "No records found for the selected period."

--------------------------------------------------
BUSINESS KNOWLEDGE BASE (BKB)
--------------------------------------------------
Platform: Petsfolio
Business Type: Pet Services Marketplace

Participants:
- Client (Pet Owner)
- Service Provider
- Platform (Petsfolio)

Services:
- Walking
- Grooming
- Training
- Boarding (Future)
- Vet (Future)
- Pet Taxi (Future)

BOOKING LIFECYCLE:
Client creates booking request -> Providers submit quotations -> Client accepts one quotation -> Client completes payment -> Booking Confirmed -> Provider performs service -> Service Verification -> Completed -> Provider Wallet Credit -> Withdrawal

QUOTATION & PAYMENT RULES:
- Multiple providers can quote, but client can accept only one.
- The accepted quotation determines the booking amount.
- Client pays the Accepted Quotation Amount. The platform receives and temporarily holds the money.

SERVICE TOTAL AMOUNT RULES:
- Walking Total Amount = Walk Package/Base + Walking Add-ons
- Grooming Total Amount = Grooming Package + Grooming Add-ons
- Training Total Amount = Training Package + Training Add-ons
Service Total Amount = Base Package + Selected Add-ons

WALKING COMPLETION RULE:
If a walking booking contains multiple scheduled walks (e.g. 10 Scheduled, 8 Approved, 2 Pending):
Only the approved/completed portion generates Platform commission, Provider earnings, and Withdrawal wallet credit.
Pending, skipped, or rejected walks do NOT generate earnings until they are completed.

PLATFORM COMMISSION & PROVIDER EARNINGS:
- Default Platform Commission is 10%.
- Commission is calculated ONLY on the total successful service amount (after successful completion/approval).
- Platform Profit = 10% * Total Service Amount.
- Provider Receivable = 90% * Total Service Amount (Accepted Bid - Commission).
- Platform profit is NOT total client payments, wallet balance, pending booking amounts, or refunded amounts.

WALLET DEFINITIONS:
- Client Service Wallet: Temporarily holds funds for active bookings.
- Client Refund Wallet: Stores refundable amounts from cancelled/rejected bookings.
- Provider Withdrawal Wallet: Stores earnings from completed services.
- Platform Wallet: Stores platform commission.

PROVIDER WALLET RULE:
Money enters the provider withdrawal wallet ONLY after successful completion (Completed Service -> Withdrawal Wallet Credit).
Pending or cancelled services NEVER increase the withdrawal wallet.

REFUND RULES:
If a booking is cancelled before completion:
- Refund = Total Client Paid Amount
- Platform earns: 0
- Provider earns: 0

AI CALCULATION RULES:
- Pending Booking: No Commission, No Provider Earnings.
- Confirmed Booking: Money Held, No Profit Yet.
- Completed Booking: Generate Commission, Generate Provider Earnings.
- Cancelled Booking: Refund Client, No Commission, No Provider Earnings.
- Failed Payment: Booking Not Confirmed, No Wallet Updates, No Commission.

KPI DEFINITIONS:
- Gross Booking Value (GBV): Total amount paid by clients.
- Net Provider Earnings: Sum of receivable amounts for completed services.
- Platform Profit: Sum of platform commissions from completed services.
- Refund Amount: Sum of refunded client payments.
- Pending Payouts: Provider earnings not yet withdrawn.
- Average Order Value (AOV): GBV / Number of completed bookings.
- Completion Rate: Completed bookings / Confirmed bookings.
- Cancellation Rate: Cancelled bookings / Confirmed bookings.
- Provider Acceptance Rate: Accepted quotations / Submitted quotations.

--------------------------------------------------
NINE INTELLIGENCE LAYERS & ANALYST BEHAVIOR
--------------------------------------------------
You operate as Petsfolio's Chief AI Business Analyst across 9 Intelligence Layers:
Layer 1 — Business Knowledge: Strictly apply payment, wallet, commission (10% of completed), and booking rules.
Layer 2 — Live Business Data: Always fetch live data using tool functions. Never guess or hardcode static figures.
Layer 3 — Historical Analysis & Comparison: Compare timeframes (e.g. this month vs last month, last 30 days vs 90 days vs last year), identify growth/decline trends, and report repeat customer retention.
Layer 4 — Root Cause & Recommendations: Never output raw numbers alone! Always explain "WHY" a metric changed (e.g., response time delay, walker shortage, add-on adoption) and provide 3 concrete, actionable recommendations.
Layer 5 — Predictive Forecasting: Forecast next month's bookings/revenue based on historical run-rates, outputting expected ranges, confidence %, and growth rates.
Layer 6 — Business Alerts & Anomaly Detection: Proactively flag critical risks (e.g. provider cancellation rates >20%, response times >30 mins, refund spikes >30%, demand-supply deficits).
Layer 7 — Strategic Suggestions: Suggest revenue and profit optimization actions (e.g. bundling grooming add-ons, localized walker recruitment).
Layer 8 — Conversational Memory: Seamlessly handle follow-ups (e.g. "Why did it increase?", "Compare with last month").
Layer 9 — Executive Mode: When asked "How is Petsfolio doing?", provide a high-level CEO Briefing covering Business Health (Good/Action Needed), GBV, Net Profit, Top Opportunity, and Top Operational Risk.

Response Style: Executive-friendly, Concise, Root-Cause Focused, Action-Oriented, Professional Markdown.
TEXT;
    }

    /**
     * Get Gemini function declaration tools.
     */
    public static function getToolDeclarations(): array
    {
        $dateRangeProp = [
            'type' => 'STRING',
            'description' => 'Time period: today, yesterday, this_week, last_week, this_month, last_month, this_quarter, last_quarter, this_year, last_year, 7_days, 30_days, 90_days, or all_time. Default is 30_days.'
        ];
        $startDateProp = [
            'type' => 'STRING',
            'description' => 'Optional start date in YYYY-MM-DD format for custom date filtering.'
        ];
        $endDateProp = [
            'type' => 'STRING',
            'description' => 'Optional end date in YYYY-MM-DD format for custom date filtering.'
        ];

        return [
            [
                'functionDeclarations' => [
                    // --- CRM METRICS (MongoDB) ---
                    [
                        'name' => 'conversion_rate',
                        'description' => 'Calculates lead conversion rate from CRM data (total leads vs converted leads).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'source' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter by lead source e.g. website, whatsapp, meta_ads, referral.'
                                ],
                                'channel' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter by channel.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'funnel_dropoff',
                        'description' => 'Analyzes CRM lead funnel drop-off by stage (e.g. New Lead, Contacted, Requirement, Quoted, Converted).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'stage' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional stage name filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'lead_source_performance',
                        'description' => 'Compares lead volume, converted leads, and conversion rates across different lead sources.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'avg_resolution_time',
                        'description' => 'Calculates average support ticket resolution time overall and broken down by issue type.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'issue_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional issue type filter e.g. walker_delay, billing, app_bug, service_quality.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'cross_sell_acceptance_rate',
                        'description' => 'Calculates cross-sell offer acceptance rates and revenue by product/addon.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'product' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional product name filter e.g. grooming_addon, pet_insurance, health_checkup.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'agent_performance',
                        'description' => 'Evaluates chat agent and automated AI bot performance (conversations, converted leads, avg duration).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'agent_id' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional agent or bot name filter.'
                                ]
                            ]
                        ]
                    ],

                    // --- MYSQL METRICS (Bookings, Revenue, Providers) ---
                    [
                        'name' => 'active_jobs',
                        'description' => 'Returns real-time active jobs currently ongoing or confirmed across all 4 service types (walking, grooming, training, sitting) with state and city distribution. ALWAYS use this tool when asked about "how many active jobs at moment?", "active jobs", "active bookings", "active services", or "from which state more there?".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service filter e.g. walking, grooming, training, sitting.'
                                ],
                                'state' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional state filter e.g. Telangana, Karnataka, Andhra Pradesh, Maharashtra.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter e.g. Hyderabad, Bangalore.'
                                ],
                                'area' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional area or locality filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'bookings_summary',
                        'description' => 'Returns total bookings/jobs posted, revenue breakdown, and sample_bookings/bookings_list (containing individual matching booking records with ID, client name, phone, status, amount, date) from MySQL database by service type (walking, grooming, training, boarding, sitting), status, area, and city. In Petsfolio, "jobs", "jobs posted", "jobs received", and "service requests" refer to bookings.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter. Only returns low-density areas within this city.'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, grooming, training, boarding, sitting.'
                                ],
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter e.g. Confirmed, Completed, Cancelled, New.'
                                ],
                                'area' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional geographical area or locality filter e.g. Malkajgiri, Banjara Hills, Gachibowli.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter e.g. Hyderabad, Bangalore.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'bookings_by_area',
                        'description' => 'Returns top booking locations, breakdown of booking demand by area, pincode, and city, active/completed counts, and total booking value.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, grooming, training, boarding, sitting.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter e.g. Hyderabad, Bangalore.'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Number of top areas to return (default 15).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'daily_booking_trend',
                        'description' => 'Returns daily booking count and daily revenue trend over time.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'whatsapp_inquiry_topics',
                        'description' => 'Categorizes live incoming WhatsApp client messages from MongoDB Atlas into topic shares (Availability, Pricing, Service Details, Policies, Provider Info).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'cancellation_rate',
                        'description' => 'Calculates booking cancellation percentage and total cancelled bookings.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'revenue_overview',
                        'description' => 'Calculates total gross revenue, platform commission / platform charges, net earnings after refunds, and provider payouts. ALWAYS use this tool when asked about "revenue", "platform charge", "platform fee", "platform commission", "earnings", "how much did petsfolio earn", or "earnings in [month]".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'revenue_by_location',
                        'description' => 'Calculates total revenue and booking counts broken down by location (city, state) and ranks top revenue cities/locations.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, grooming, training.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'registration_fee',
                        'description' => 'Calculates provider registration and verification fee total amount collected from service providers in the transactions table, transaction count, and category breakdown across Walking_registration, Sitting_registration, Grooming_registration, Training_registration, and Service_registration. Use this whenever the user asks for the amount collected from service providers for verification or registration.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, sitting, grooming, training, service.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'recovered_transactions_overview',
                        'description' => 'Calculates payments and transactions that initially failed, were dropped, or incomplete, but were later successfully paid upon retry. Provides total recovered transactions count, recovered bookings count, recovered provider registrations count, total recovered revenue in INR, prior failed attempts count, and sample recovered transactions. Use whenever the user asks how many transactions failed but were later successful, retried payments, or payment recovery.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'transaction_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter e.g. booking, walking, sitting, grooming, training, registration, or all.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'active_customers',
                        'description' => 'Returns total registered clients/members count from the users table, active booking customers count (who placed bookings), repeat customers count, and top active customers list.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'repeat_customers',
                        'description' => 'Calculates repeat business metric, repeat customer count (customers with 2+ bookings), retention rate %, and returns top repeat customer profiles list.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'client_profile_lookup',
                        'description' => 'Looks up a specific client / customer / pet parent by name, phone number, email, or user ID in the registered users table. Returns profile details, active/inactive status, registered pets, total bookings, active bookings, total lifetime spend (INR), and wallet balance. ALWAYS use this whenever the user asks about a client/customer by name (e.g. "Saru", "Rahul", "Priya") or asks if a specific client is active.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'client_search' => [
                                    'type' => 'STRING',
                                    'description' => 'The client name, phone number, email, or user ID to search (e.g. "Saru", "9876543210").'
                                ],
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ],
                            'required' => ['client_search']
                        ]
                    ],
                    [
                        'name' => 'provider_stats',
                        'description' => 'Returns Service Provider counts, tier distribution, area/city breakdown, ratings, and individual provider profile stats using phone number, name, or ID.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'area' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional geographical area or locality filter e.g. Malkajgiri, Banjara Hills, Gachibowli.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter e.g. Hyderabad, Bangalore.'
                                ],
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter e.g. Active, Pending, Inactive, Waiting for Call, Approval (Manager Approval queue), Pending_Registration (Fee Pending queue), or Pending_KYC (KYC Pending queue).'
                                ],
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional provider search filter: accepts phone number (e.g. 8050728083), provider name (e.g. Rahul), or provider ID.'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, grooming, training, sitting, boarding.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'low_provider_density_areas',
                        'description' => 'Identifies geographical areas and cities across India with low service provider or groomer density (deficit areas).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. grooming, walking, training, boarding, sitting.'
                                ],
                                'max_threshold' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Maximum active provider count threshold to include an area as low-density (default 10).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'bids_conversion_analytics',
                        'description' => 'Analyzes average bids submitted per booking request, conversion rates per bid count, and minimum bids required to convert a booking request.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. grooming, walking, training, boarding, sitting.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_performance_score',
                        'description' => 'Calculates AI performance score (0-100), rating, cancellation breakdown, and recommendations for service providers using phone number, name, or provider ID.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'provider_id' => [
                                    'type' => 'STRING',
                                    'description' => 'Provider search filter: accepts phone number (e.g. 8050728083), provider name (e.g. Rahul), or provider ID.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_revenue_overview',
                        'description' => 'Compares revenue and earnings by provider. Returns top-earning service providers ranked by total payouts, completed bookings count, city, and area for a specified time period.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, grooming, sitting, boarding, training.'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Number of top providers to return (default 10).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'locality_demand_supply',
                        'description' => 'Analyzes booking demand vs provider supply by locality/area, generating deficit heatmaps and oversupply warnings.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. walking, grooming, training.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter e.g. Hyderabad, Bangalore.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'fraud_anomaly_detection',
                        'description' => 'Detects operational anomalies e.g. excessive daily walks (>18/day), refund spikes, or severe cancellation rates.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'user_refunds_summary',
                        'description' => 'Retrieves client refunds data from user_refunds database table, total refund amounts, pending vs completed refund counts, and compares refunds against platform commission.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter: completed, pending, approved, or all.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'executive_summary',
                        'description' => 'Generates top-level CEO business health summary, key KPI growth trends, biggest growth opportunity, and operational risks.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'business_forecast',
                        'description' => 'Predicts next period booking volume and revenue run-rate with expected growth range and confidence level %.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'metric' => [
                                    'type' => 'STRING',
                                    'description' => 'Metric to forecast: revenue, bookings, or profit.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'churned_inactive_customers',
                        'description' => 'Identifies clients who have not placed a booking in N days (e.g. 90 days), returning last booking date, days inactive, total lifetime bookings, and lifetime paid spend.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'days' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Inactivity threshold in days (default 90).'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Maximum number of inactive client records to return (default 50).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'time_based_analytics',
                        'description' => 'Analyzes peak booking hours of day, busiest weekday, peak booking days, and monthly revenue trends across full database history.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'addon_intelligence',
                        'description' => 'Analyzes popular add-on packages, add-on revenue uplift, add-on selection counts, and recommended add-on bundles for walking, grooming, and training.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'service_growth_analysis',
                        'description' => 'Compares revenue growth, completed vs cancelled booking counts, and average order value across services (walking, grooming, training, sitting, boarding).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'insurance_overview',
                        'description' => 'Returns complete overview of all insurance policies in database (total count, active count, pending enquiries, sent quotations, expiring policies, renewal dates) and claims. Accepts optional policy_search for specific policy number, client, or pet.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'status'     => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter e.g. Active, Pending, Expired.'
                                ],
                                'policy_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional search query: policy number, client name, phone, or pet name.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'loyalty_overview',
                        'description' => 'Returns overview of loyalty program (total points earned/spent/balance, total loyalty users, pending reward orders, delivered orders, top point holders).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'community_overview',
                        'description' => 'Returns overview of community activity (total social posts, total channels, comments, likes, reports, channel breakdown, and recent popular posts). This is strictly for social community forum channels, NOT for jobs or bookings posted.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'sms_credits_overview',
                        'description' => 'Fetches real-time remaining SMS Gateway credits balance and alert status (Good vs Low).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'service' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter e.g. transactional, promotional.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'payout_balance_overview',
                        'description' => 'Returns provider payout and withdrawal requests from wallet_withdrawals database (pending request count, total amount, recent requests) along with Cashfree API available and ledger balance.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'verification_executives_overview',
                        'description' => 'Returns performance metrics for Verification Executives (role_id=10), including total call verifications completed, manager approvals, total SPs handled per executive, wallet balance, total earned, and total withdrawn.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'executive_payouts_overview',
                        'description' => 'Comprehensive executive financial intelligence: returns wallet balances, total earnings per verified/onboarding call, total withdrawals, Cashfree bank transfers, transaction ledger, and bank details for Verification Executives (role_id=10) and Registration Executives (role_id=13). Use whenever asked about executive withdrawals, executive payouts, executive wallet balances, or how much a specific executive (e.g. Abhinaya, varalaxmi, Tara Devi) has withdrawn or earned.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'executive_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional executive name, email, phone, or employee ID (e.g. "Abhinaya", "116", "varalaxmi"). Leave empty for all executives.'
                                ],
                                'executive_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional type filter: "all", "verification", or "register". Default is "all".'
                                ],
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'aadhaar_verification_attempts',
                        'description' => 'Returns analytics and audit logs for Aadhaar KYC identity verification attempts, including providers who attempted multiple times, reasons for failure/mismatches, DigiLocker consent issues, and affected provider profiles.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter: SUCCESS, FAILED, MISMATCH, CONSENT_DENIED, PENDING, EXPIRED, or REJECTED.'
                                ],
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional provider name, phone, or ID to look up their specific verification attempts history.'
                                ],
                                'min_attempts' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Minimum number of attempts to filter (default 2 for repeat attempts).'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Number of top repeat providers to return (default 25).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_media_overview',
                        'description' => 'Returns analytics on service provider media uploads (service related photos, videos, admin approval statuses, and top contributing providers).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'media_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional media type filter: photo, video, or all.'
                                ],
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional approval status filter: approved, pending, rejected, or all.'
                                ],
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional provider name, phone, or ID.'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Maximum number of top media providers to return (default 15).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'services_settings_overview',
                        'description' => 'Returns overview of services, packages, add-ons, available states configuration, and tutorial video links.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service filter e.g. walking, grooming, training, sitting, boarding.'
                                ],
                                'state' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional state filter e.g. Telangana, Karnataka.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'agreements_overview',
                        'description' => 'Returns legal digital agreements analytics matching the Admin Panel Agreements module (/agreements). Provides exact counts for SP Agreements (57 partners onboarded in provider_agreements), Client Agreements (67 pet parents in client_agreements), total received (124), last 24h activity, and sample signed agreements.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'type' => [
                                    'type' => 'STRING',
                                    'description' => 'Agreement audience type: "sp" (or "provider") for Service Provider agreements (57), "client" (or "user") for Pet Parent agreements (67), or "all" for both.'
                                ],
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Date range filter: all_time, 30_days, 7_days, today, etc.'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Maximum number of recent agreements to return (default 25).'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'support_tickets_overview',
                        'description' => 'Returns overview of customer and provider support tickets (open, closed, in progress counts, breakdown by category, client vs provider tickets).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'status'     => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter: open, closed, in_progress.'
                                ],
                                'user_type'  => [
                                    'type' => 'STRING',
                                    'description' => 'Optional user type filter: provider (or service_provider), client.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'notification_campaigns_overview',
                        'description' => 'Returns notification campaign history (total campaigns, sent, scheduled, draft counts, and campaign details).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_notification_bidding_analysis',
                        'description' => 'Comprehensive analysis of service provider notification reachability (FCM device tokens), in-app notification logs (sent/read/unread), push queue delivery, and provider bidding cohorts (receiving push & bidding, receiving push but never bid, no token/dormant).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'call_verification_overview',
                        'description' => 'Returns telephonic verification calls conducted by executives (count of completed verification calls, total duration, executive breakdown, candidate ratings, review comments, recording URLs, and payout history).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_reconciliation_overview',
                        'description' => 'Audits provider wallet balances against transaction histories, comparing actual stored balance in sp_withdrawal_wallet / sp_service_wallet vs calculated balances from sp_wallet_histories and wallet_withdrawals to detect financial mismatches.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Provider search filter: provider name (e.g. Solomon Jairus), phone number, or provider ID.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'client_wallet_reconciliation',
                        'description' => 'Audits client / customer wallet balances against transaction histories, comparing actual stored balances in user_service_wallet / user_withdrawal_wallet vs calculated balances from user_wallet_histories to detect financial mismatches.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'client_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Client search filter: client name, phone number, or user ID.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'pet_demographics',
                        'description' => 'Returns pet population analytics including total registered pets, species breakdown (Dogs, Cats, Others), top breeds distribution, gender ratio, and vaccination counts. ONLY invoke when the user explicitly asks about pet animals, dog/cat breeds, or pet registrations. NEVER invoke for provider verification or financial fee queries!',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'species' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional pet species/type filter e.g. dog, cat.'
                                ],
                                'breed' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional breed filter e.g. Shih Tzu, Labrador, Golden Retriever, Beagle.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'feedback_and_reviews_overview',
                        'description' => 'Returns customer and provider feedback analysis, overall average rating out of 5, rating distribution (5★ to 1★), client vs provider ratings, and recent customer reviews.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'user_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional user type filter: client, provider, or all.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'call_ai_quality_analytics',
                        'description' => 'Analyzes call recordings QA intelligence: average QA scores, customer/provider sentiment breakdown, escalations, complaints, and policy violations (off-platform payments, direct contact sharing, refund requests).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'sentiment' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional sentiment filter: positive, neutral, negative.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'upgrade_and_premium_requests',
                        'description' => 'Tracks customer upgrade requests (command add-ons for training/walking) and VIP premium booking requests, monetary volumes in INR, and approval/rejection rates.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter: approved, pending, rejected, new, assigned.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'operations_tasks_overview',
                        'description' => 'Returns internal operational task tracking: total tasks, status breakdown (pending, in-progress, completed, cancelled), task types, completion rate %, and overdue task alerts.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional task status filter: pending, in_progress, completed, cancelled.'
                                ],
                                'task_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional task type filter: one_time, repeated.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'walking_tracking_analytics',
                        'description' => 'Analyzes real-time walk session tracking: total tracked walks, completion rate %, untracked/missed walks, average walk duration, and suspicious short walks (< 20 min).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter: Completed, Untracked, Cancelled.'
                                ],
                                'booking_status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional booking status filter: active, confirmed, completed, cancelled, all.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],

                    // --- NEW COMPREHENSIVE TABLE COVERAGE TOOLS ---

                    [
                        'name' => 'banners_overview',
                        'description' => 'Returns app promotional banners data: total banners count, active banners count, and banner details (image, link, position, target audience, status).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'tutorials_overview',
                        'description' => 'Returns ALL tutorial video links with their titles, URLs, categories, and display order. ALWAYS use this when asked about tutorial video links, tutorial count, how many tutorials, or tutorial data.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'doctors_overview',
                        'description' => 'Returns veterinary doctor listings, total doctors count, appointments count and status breakdown, prescriptions count, doctor documents count, and doctor referral codes.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'doctor_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional search: doctor name, phone, or specialization.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'memberships_overview',
                        'description' => 'Returns client membership plans data: total memberships, active/expired counts, total membership revenue, and member list with client names.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'coupons_overview',
                        'description' => 'Returns coupon definitions, active/total coupons count, coupon usage logs count, user coupons count, and coupon details (code, discount type, min order, max discount, validity).',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'coupon_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional search: coupon code or name.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_strikes_attendance',
                        'description' => 'Returns provider disciplinary strikes (violations, warnings) and daily attendance/check-in logs with provider names and dates.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional provider name or phone filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'referrals_overview',
                        'description' => 'Returns referral program tracking data: total referrals, status breakdown (pending, completed, rewarded), and referral details.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'cameras_overview',
                        'description' => 'Returns live camera/streaming devices linked to boarding facilities: total cameras, camera names, stream URLs, and linked providers.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => []
                        ]
                    ],
                    [
                        'name' => 'booking_conversations_overview',
                        'description' => 'Returns booking communication analytics: total booking conversations, total booking messages exchanged, total invited SPs, total booking add-ons selected, and total booking pets associated.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'booking_id' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional specific booking ID to filter conversations for.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'dog_breeds_overview',
                        'description' => 'Returns master breed list (dogs, cats, cows) with total breed count and top breeds by registered pet count.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'breed_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional breed type filter e.g. dog, cat, cow.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'chat_overview',
                        'description' => 'Returns platform chat and conversation analytics: total conversations, total messages, chatbot interaction logs, predefined chat questions count, and chat answers count.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'table_data_overview',
                        'description' => 'Dynamic data explorer for any database table not covered by specific metrics. Returns total row count, column names, and sample data rows. Use this as a fallback when no specific metric function matches the question. Supports 100+ tables including: employees, client_agreements, user_addresses, pending_changes, pet_care_routines, pet_medications, notification_templates, sp_aadhar, sp_tiers, taxes, verification_questions, roles, permissions, service_pause_logs, platform_wallet, wallet_audit_log, etc.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'table_name' => [
                                    'type' => 'STRING',
                                    'description' => 'REQUIRED. The exact database table name to query e.g. employees, client_agreements, user_addresses, notification_templates, sp_aadhar, taxes, roles, permissions, pet_medications, platform_wallet, wallet_audit_log, service_pause_logs, sp_tiers, pending_changes, etc.'
                                ],
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp,
                                'search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional search keyword to filter rows.'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Max rows to return (default 50).'
                                ]
                            ],
                            'required' => ['table_name']
                        ]
                    ],
                    [
                        'name' => 'roles_and_permissions_overview',
                        'description' => 'Comprehensive security and RBAC access control overview. Returns all platform roles, assigned permissions per role, module breakdowns (Booking, Finance, Employees, Configuration, etc.), and individual permission slugs.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'role_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter by role name or slug e.g. admin, developer, recruiter, operations, sales, caller.'
                                ],
                                'module_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter by module e.g. Bookings, Finance, Configuration, Employees, Tax, Veterinary.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'service_states_overview',
                        'description' => 'Returns all configured service available states and Union Territories across India for Petsfolio, including status (active/inactive), short codes, and registered customer coverage. ALWAYS use this tool when asked "how many service states are there?", "service states", "available states", "which states do we operate in?", or "operating states".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter: active or inactive.'
                                ],
                                'search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional state name or short code filter e.g. Telangana, TS, Maharashtra, Karnataka.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'verification_questions_overview',
                        'description' => 'Returns service provider verification, onboarding, and call verification questions configured in the platform across Walking, Grooming, Training, and Sitting. Returns total questions count, category breakdown (e.g. Experience, Behaviour, Safety, Availability), question weightages/marks, evaluation rubrics/options, and status. ALWAYS use this tool when asked about "verification questions", "questions for walking", "how many questions for walking", "onboarding questions", or "provider interview questions".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Service category filter: walking, grooming, training, sitting, boarding, or all.'
                                ],
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Question status: active, inactive, or all. Default is active.'
                                ],
                                'category' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional category filter e.g. Experience, Behaviour, Psychology & Pet Handling, Situational Safety & Presence of Mind, Travel & Availability, Mobile Usage Discipline, Client Interaction.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'sp_wallets_overview',
                        'description' => 'Provides complete visibility into service provider wallet balances: sp_service_wallet (active booking escrow funds for walking, training, sitting, grooming) and sp_withdrawal_wallet (available earnings ready for withdrawal). Clearly differentiates wallet balances from submitted withdrawal requests in wallet_withdrawals. ALWAYS use this tool when asked about service wallet, sp service wallet, withdrawal wallet, sp withdrawal wallet, amount in service wallet, amount in withdrawal wallet, or provider wallet funds.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'wallet_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Filter by wallet: service (sp_service_wallet escrow), withdrawal (sp_withdrawal_wallet available earnings), or all (both).'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter for service wallet: walking, training, sitting, grooming, boarding.'
                                ],
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional provider name, phone number, or ID to look up specific provider wallet balances.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'client_wallets_overview',
                        'description' => 'Provides complete visibility into client/customer wallet balances: user_withdrawal_wallet (customer refundable/withdrawable balance credited from cancellations or refunds) and user_service_wallet (active booking escrow funds across walking, training, sitting, grooming, and fixed deposit). Clearly differentiates stored wallet balance from formal bank refund requests in user_refunds. ALWAYS use this tool when asked about client withdrawal wallet, client refund wallet, user withdrawal wallet, user service wallet, client service wallet, or how many clients have balance in withdrawal/refund/service wallet.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'wallet_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Filter by wallet: withdrawal (user_withdrawal_wallet refundable funds), service (user_service_wallet escrow funds), or all (both).'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter for service wallet: walking, training, sitting, grooming, boarding.'
                                ],
                                'client_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional client name, phone number, or ID to look up specific client wallet balances.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'packages_overview',
                        'description' => 'Returns the official service packages catalog from the packages table across all services (Walking, Grooming, Training, Sitting, Boarding). Returns package names, prices, session counts, duration, and status. Training strictly has 4 packages (Puppy, Basic Obedience, Behaviour, Advance). Behavioral commands (e.g. Barking, Leash Walk) are addons, not packages. ALWAYS use this tool when asked about training packages, walking packages, grooming packages, sitting packages, or packages list.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Service filter: training, walking, grooming, sitting, boarding, or all.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'in_app_calls_overview',
                        'description' => 'Returns real-time in-app client-to-service-provider WebRTC/LiveKit call statistics from the calls table, including completed calls, cancelled calls, duration in minutes, caller details, and booking conversion tracking. ALWAYS use this tool when asked about in-app calls, client-sp calls, call conversions, or calls today.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Date filter: today, yesterday, 7_days, 30_days, this_month, all_time.'
                                ],
                                'status' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional filter by call status: completed, cancelled, rejected, in_progress.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'semantic_query_compiler',
                        'description' => 'Declarative Semantic Layer compiler. Executes multi-dimensional aggregations and slicing across bookings without hallucinating table joins. Use this whenever the user asks for multi-dimensional breakdowns like GMV by city, booking count by service type and status, average booking value by service, or monthly trends. Queries curated view v_bookings_overview with deterministic SQL.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'metric' => [
                                    'type' => 'STRING',
                                    'description' => 'The business metric to aggregate: gmv, gross_revenue, completed_revenue, booking_count, completed_bookings, cancelled_bookings, active_jobs, active_providers, active_clients, avg_booking_value, platform_commission. Default is gmv.'
                                ],
                                'dimensions' => [
                                    'type' => 'ARRAY',
                                    'items' => ['type' => 'STRING'],
                                    'description' => 'List of dimensions to group by: city, service_type, status, month, week, day. e.g. ["city"] or ["city", "service_type"].'
                                ],
                                'filters' => [
                                    'type' => 'OBJECT',
                                    'properties' => [
                                        'city' => ['type' => 'STRING', 'description' => 'Filter by city (e.g. Hyderabad, Bengaluru).'],
                                        'service_type' => ['type' => 'STRING', 'description' => 'Filter by service (e.g. walking, grooming, training, sitting, boarding).'],
                                        'status' => ['type' => 'STRING', 'description' => 'Filter by booking status (e.g. completed, active, cancelled).']
                                    ],
                                    'description' => 'Optional dimension filters.'
                                ],
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: today, yesterday, this_week, last_week, this_month, last_month, 30_days, 90_days, this_year, all_time. Default is 30_days.'
                                ],
                                'start_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional custom start date in YYYY-MM-DD format.'
                                ],
                                'end_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional custom end date in YYYY-MM-DD format.'
                                ],
                                'limit' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Maximum rows to return (capped at 1000). Default is 100.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'funnel_analysis',
                        'description' => 'Evaluates the end-to-end conversion funnel for pet services (Requests Created -> Quotes Received -> Quotes Accepted -> Payment Initiated -> Payment Success -> Confirmed -> Completed). Calculates step-by-step conversion rates and drop-off percentages, and automatically pinpoints the single largest bottleneck with root-cause diagnostic reasoning. ALWAYS use this tool when asked: "Why are clients not completing bookings?", "Show conversion funnel", "Where is the largest drop-off?", or "Funnel analysis".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'funnel_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Service funnel to evaluate: walking, grooming, training, boarding, or all. Default is walking.'
                                ],
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Date filter: today, yesterday, 7_days, 30_days, 90_days, this_month, last_month, this_year, all_time. Default is 30_days.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter (e.g. Hyderabad, Bengaluru).'
                                ],
                                'start_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional custom start date in YYYY-MM-DD format.'
                                ],
                                'end_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional custom end date in YYYY-MM-DD format.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'dropoff_reasons_analysis',
                        'description' => 'Categorizes booking halts, cancellations, and drop-offs into the standardized Petsfolio business reasons taxonomy (NO_PROVIDER_AVAILABLE, PAYMENT_FAILED, PRICE_TOO_HIGH, PET_NOT_ACCEPTING, CLIENT_NOT_INTERESTED, PROVIDER_NO_SHOW, etc.). Computes drop-off percentages and total forfeited GMV. ALWAYS use this tool when asked: "Why do bookings get halted?", "Why do clients cancel?", "Common cancellation reasons?", or "Top drop-off reasons".',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter: walking, grooming, training, boarding, or all.'
                                ],
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Date filter: today, yesterday, 7_days, 30_days, 90_days, this_month, last_month, this_year, all_time. Default is 30_days.'
                                ],
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter.'
                                ],
                                'start_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional custom start date in YYYY-MM-DD format.'
                                ],
                                'end_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional custom end date in YYYY-MM-DD format.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'business_events_overview',
                        'description' => 'Queries the platform business_events stream logging ~160+ standardized events across 16 categories (CLIENT, PET, SERVICE, WALKING, GROOMING, TRAINING, BOARDING, BOOKING, PROVIDER, PAYMENT, WALLET, CANCELLATION, etc.). Returns total event volume, category distribution, and top event counts.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Date filter: today, yesterday, 7_days, 30_days, 90_days, this_month, all_time. Default is 30_days.'
                                ],
                                'category' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional category filter: CLIENT, PET, WALKING, GROOMING, TRAINING, PAYMENT, CANCELLATION, etc.'
                                ],
                                'event_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional specific event type filter: e.g. WALKING_REQUEST_CREATED, PAYMENT_SUCCESS, BOOKING_HALTED.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'customer_journey_timeline',
                        'description' => 'Traces the complete chronological business event journey for a specific pet parent (client) or booking ID. Returns all event milestones with timestamps, categories, and amounts.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'identifier' => [
                                    'type' => 'STRING',
                                    'description' => 'The user_id (numeric) or booking_id string to trace.'
                                ],
                                'type' => [
                                    'type' => 'STRING',
                                    'description' => 'Lookup type: "client" (for user_id) or "booking" (for booking_id). Default is "client".'
                                ]
                            ],
                            'required' => ['identifier']
                        ]
                    ],
                    [
                        'name' => 'booking_payments_overview',
                        'description' => 'Comprehensive client booking payment transactions overview mirroring Admin Panel -> Finance -> Payments (admin.petsfolio.in/payments). Returns total transactions (468), gross amount (₹24.32L), average transaction (₹5,198.71), unique customers (131), status tab breakdown (Success: 172, Pending: 17, Failed: 180, Legacy: 99), and exact failure root cause analysis (Gateway Timeouts, Abandoned checkouts, Insufficient funds, User dropped OTP, etc.). ALWAYS use this tool when asked about client booking payments, payment success/fail counts, or failure reasons.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'status_filter' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional status filter matching Admin Panel tabs: all, success, pending, failed.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'daily_management_action_matrix',
                        'description' => 'Executes the Petsfolio MIS Daily Management Question: "Where do we have demand today, where do we have insufficient supply, what revenue is at risk, and who owns the action?". Returns structured matrix: Area | Service | Requests | Available capacity | Supply gap | Revenue at risk | Action owner, using the recommended gap formula: Supply gap = Required available provider capacity - Current available provider capacity.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'city' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional city filter (e.g. Hyderabad, Bengaluru, Mumbai).'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service filter (walking, grooming, training, sitting, boarding).'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'target_vs_actual_performance',
                        'description' => 'Compares daily, monthly, and quarterly operational KPIs against management targets for registrations, requests, bookings, completed bookings, and revenue. Calculates achievement percentages, flags below-target KPIs, and provides diagnostic root-cause reasons.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'net_revenue_waterfall',
                        'description' => 'Calculates the complete financial waterfall: Gross Booking Value (GMV) minus Discounts, Taxes (18% GST), Provider Payouts (85% net base in V2), Payment Gateway charges (2%), Processed Refunds, plus Provider Registration Fees, yielding bottom-line Net Retained Revenue and Contribution Margin % per service and per booking.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp,
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service filter: walking, grooming, training, sitting, boarding, or all.'
                                ],
                                'start_date' => $startDateProp,
                                'end_date'   => $endDateProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'management_kpi_governance',
                        'description' => 'Returns the complete management governance catalog for all 20 first-release dashboard priority KPIs, including KPI name, current value, responsible owner, refresh frequency (real-time, hourly, daily), source tables, exact SQL calculation formulas, alert trigger thresholds, drill-down hierarchy, and action log tracking schema.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => $dateRangeProp
                            ]
                        ]
                    ],
                    [
                        'name' => 'quotation_details_lookup',
                        'description' => 'Complete Quotation & Bidding Details Lookup for a Specific Booking ID (e.g. PSF0ea7eWLK, SF0ea7eWLK, or numeric). Returns all quotations submitted by service providers, their bid amounts, provider names, phone, areas, status (Accepted, Rejected, New), accepted provider payout, platform charges, GST, and client paid amount. ALWAYS invoke this tool when asked for quotation details, bids, or provider offers for a specific booking ID!',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'booking_id' => [
                                    'type' => 'STRING',
                                    'description' => 'The booking ID string (e.g. PSF0ea7eWLK, SF0ea7eWLK, etc.) to look up.'
                                ]
                            ],
                            'required' => ['booking_id']
                        ]
                    ]
                ]
            ]
        ];
    }
}
