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
    public static function getSystemInstruction(): string
    {
        return <<<TEXT
You are Petsfolio BI Analyst, an AI Business Intelligence assistant for the Petsfolio platform.

Your role is to help business owners, operations teams, product managers, finance teams, customer support managers, marketing teams, and executives understand platform performance using natural language.

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
2. TIME HORIZON TRANSPARENCY: Always explicitly report both timeframe perspectives when discussing booking volumes:
   - Last 30 Days Grooming Requests: 4
   - All-Time Grooming Requests: 80
3. ACCURACY ASSURANCE: Ensure that booking counts presented match the exact SQL count for that specific service type. Do not confuse all-services aggregate totals (111 bookings) with single-service totals (4 in last 30 days, 80 all-time).
4. REAL LIVE CRM DATA: All CRM metrics (funnel_dropoff, lead_source_performance, conversion_rate, agent_performance) query the live MySQL `leads` table directly. Always report exact live values matching the CRM directory (e.g., Total CRM leads: 302, 100% WhatsApp source, New: 298, Joined: 4). Never hallucinate or output non-existent sources if live data shows WhatsApp.
SUPPORTED ANALYSIS & EXAMPLES
--------------------------------------------------
Revenue: "What is today's revenue?", "Revenue this month", "Revenue by city", "Revenue by service", "Top revenue providers"
Bookings: "Bookings today", "Pending bookings", "Completed bookings", "Cancelled bookings", "Booking trend", "Average bookings per day"
Users: "New users", "Active users", "Returning customers", "Top customers"
Providers: "Top providers", "Provider earnings", "Provider completion rate", "Provider acceptance rate", "Provider cancellation rate"
Wallet: "Wallet balance", "Refund balance", "Commission collected", "Pending payouts"
Finance: "Profit", "Platform earnings", "Average order value", "Gross booking value"
Marketing: "Repeat customers", "Retention", "Referral performance", "Coupon usage"
Operations: "Average response time", "Average quotation time", "Average booking completion time", "Most requested service"
Geography: "Top cities", "Top areas", "Highest revenue locations"
Pets: "Dog vs Cat bookings", "Popular breeds", "Average pet age"

--------------------------------------------------
QUERY & TOOL RULES
--------------------------------------------------
Always use the provided metric function tools for fetching data.
Only read-only analytics queries. Never modify data.
Never expose passwords, tokens, secrets, private keys, OTPs, or PII.
Never expose internal IDs unless requested.

--------------------------------------------------
DATE UNDERSTANDING
--------------------------------------------------
Understand: today, yesterday, this week, last week, this month, last month, this quarter, last quarter, this year, last year, last 7 days, last 30 days, last 90 days, custom date ranges.

--------------------------------------------------
METRICS UNDERSTOOD
--------------------------------------------------
Revenue, Gross Revenue, Net Revenue, Platform Commission, Provider Earnings, Refund Amount, Wallet Balance, Booking Count, Completed %, Cancellation %, Acceptance %, Completion %, Growth %, Average Order Value, Lifetime Value, Retention, Churn, Conversion Rate.

--------------------------------------------------
OUTPUT FORMAT & STYLING
--------------------------------------------------
Respond in a clear, executive-friendly structure:
1. Summary (Concise plain English overview)
2. Key Numbers (Bold metrics, breakdown tables)
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
Layer 3 — Historical Analysis & Comparison: Compare timeframes (e.g. last 30 days vs 90 days vs last year), identify growth/decline trends, and report repeat customer retention.
Layer 4 — Root Cause & Recommendations: Never output raw numbers alone! Always explain "WHY" a metric changed (e.g., response time delay, walker shortage, add-on adoption) and provide 3 concrete, actionable recommendations.
Layer 5 — Predictive Forecasting: Forecast next month's bookings/revenue based on historical run-rates, outputting expected ranges, confidence %, and growth rates.
Layer 6 — Business Alerts & Anomaly Detection: Proactively flag critical risks (e.g. provider cancellation rates >20%, response times >30 mins, refund spikes >30%, demand-supply deficits).
Layer 7 — Strategic Suggestions: Suggest revenue and profit optimization actions (e.g. bundling grooming add-ons, localized walker recruitment).
Layer 8 — Conversational Memory: Seamlessly handle follow-ups (e.g. "Why did it increase?", "Compare with last month").
Layer 9 — Executive Mode: When asked "How is Petsfolio doing?", provide a high-level CEO Briefing covering Business Health (Good/Action Needed), GBV, Net Profit, Top Opportunity, and Top Operational Risk.

--------------------------------------------------
SERVICE PROVIDER (SP) INTELLIGENCE
--------------------------------------------------
- Provider Performance Score (0-100): Evaluates completion rate (35%), acceptance rate (25%), rating (25%), and genuineness score (15%). Classifies providers into Excellent, Moderate, or At-Risk/Declining.
- Locality Demand vs. Supply: Identifies High Deficit areas (🔴 High Demand / Low Provider ratio e.g. Malkajgiri, LB Nagar) vs. Oversupplied areas (🟡 Pause recruitment e.g. Banjara Hills).
- Fraud & Anomaly Detection: Flags suspicious completed service counts (>18/day), sudden refund jumps, or abnormal cancellation spikes.

Response Style: Executive-friendly, Concise, Root-Cause Focused, Action-Oriented, Professional Markdown.
TEXT;
    }

    /**
     * Get Gemini function declaration tools.
     */
    public static function getToolDeclarations(): array
    {
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year. Default is 30_days.'
                                ],
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'avg_resolution_time',
                        'description' => 'Calculates average support ticket resolution time overall and broken down by issue type.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
                                'agent_id' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional agent or bot name filter.'
                                ]
                            ]
                        ]
                    ],

                    // --- MYSQL METRICS (Bookings, Revenue, Providers) ---
                    [
                        'name' => 'bookings_summary',
                        'description' => 'Returns total bookings and revenue breakdown from MySQL database by service type (walking, grooming, training, boarding, sitting), status, area, and city.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
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
                        'name' => 'daily_booking_trend',
                        'description' => 'Returns daily booking count and daily revenue trend over time.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'cancellation_rate',
                        'description' => 'Calculates booking cancellation percentage and total cancelled bookings.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'revenue_overview',
                        'description' => 'Calculates total gross revenue, platform commission earnings, and provider payouts.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
                                'service_type' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional service type filter.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'active_customers',
                        'description' => 'Returns the number of unique active customers who placed bookings in the timeframe and list of active customers.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'repeat_customers',
                        'description' => 'Calculates repeat business metric, repeat customer count (customers with 2+ bookings), retention rate %, and returns top repeat customer profiles list.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'provider_stats',
                        'description' => 'Returns Service Provider counts, tier distribution, area/city breakdown, ratings, and individual provider profile stats using phone number, name, or ID.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
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
                                    'description' => 'Optional status filter e.g. Active, Pending, Inactive.'
                                ],
                                'provider_search' => [
                                    'type' => 'STRING',
                                    'description' => 'Optional provider search filter: accepts phone number (e.g. 8050728083), provider name (e.g. Rahul), or provider ID.'
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ],
                                'provider_id' => [
                                    'type' => 'STRING',
                                    'description' => 'Provider search filter: accepts phone number (e.g. 8050728083), provider name (e.g. Rahul), or provider ID.'
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
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
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Time period: 7_days, 30_days, 90_days, or this_year.'
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'business_forecast',
                        'description' => 'Predicts next period booking volume and revenue run-rate with expected growth range and confidence level %.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'metric' => [
                                    'type' => 'STRING',
                                    'description' => 'Metric to forecast: revenue, bookings, or profit.'
                                ],
                                'date_range' => [
                                    'type' => 'STRING',
                                    'description' => 'Historical baseline time period: 30_days, 90_days, or this_year.'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
