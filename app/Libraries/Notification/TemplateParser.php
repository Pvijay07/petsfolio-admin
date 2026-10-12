<?php

namespace App\Libraries\Notification;

/**
 * TemplateParser
 * Handles variable replacement in notification templates
 */
class TemplateParser
{
    /**
     * Parse a template string and replace {{variable}} placeholders
     *
     * @param string $template  Template string with {{variable}} placeholders
     * @param array  $variables Key-value pairs of variable replacements
     * @return string Parsed string
     */
    public function parse($template, $variables = [])
    {
        if (empty($template)) {
            return '';
        }

        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', (string) $value, $template);
        }

        // Remove any remaining unresolved placeholders
        $template = preg_replace('/\{\{[^}]+\}\}/', '', $template);

        return trim($template);
    }

    /**
     * Extract variable names from a template string
     *
     * @param string $template Template string
     * @return array List of variable names found
     */
    public function extractVariables($template)
    {
        preg_match_all('/\{\{(\w+)\}\}/', $template, $matches);
        return array_unique($matches[1] ?? []);
    }

    /**
     * Validate that all required variables are provided
     *
     * @param string $template  Template string
     * @param array  $variables Provided variables
     * @return array Missing variable names
     */
    public function getMissingVariables($template, $variables = [])
    {
        $required = $this->extractVariables($template);
        $provided = array_keys($variables);

        return array_diff($required, $provided);
    }

    /**
     * Preview a template with sample data
     *
     * @param string $template Template string
     * @return string Previewed template with sample values
     */
    public function preview($template)
    {
        $sampleData = [
            'user_name'         => 'John Doe',
            'user_phone'        => '9876543210',
            'app_name'          => 'Petsfolio',
            'booking_id'        => 'BK-12345',
            'service_name'      => 'Dog Walking',
            'service_date'      => date('d M Y'),
            'service_time'      => '10:00 AM',
            'provider_name'     => 'Rahul Kumar',
            'pet_name'          => 'Buddy',
            'amount'            => '₹499',
            'transaction_id'    => 'TXN-98765',
            'duration'          => '30 mins',
            'balance'           => '₹1,200',
            'transaction_type'  => 'Credit',
            'points'            => '50',
            'total_points'      => '500',
            'milestone_name'    => 'Gold Member',
            'reward_name'       => 'Free Grooming',
            'days_inactive'     => '15',
            'last_service_date' => date('d M Y', strtotime('-15 days')),
            'offer_name'        => 'Weekend Special',
            'discount_percent'  => '20%',
            'coupon_code'       => 'PETS20',
            'valid_until'       => date('d M Y', strtotime('+7 days')),
        ];

        return $this->parse($template, $sampleData);
    }
}
