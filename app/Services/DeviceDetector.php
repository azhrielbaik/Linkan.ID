<?php

namespace App\Services;

class DeviceDetector
{
    /**
     * Detect device type from user agent string.
     *
     * @param string $userAgent
     * @return string "Mobile", "Tablet", or "Desktop"
     */
    public static function detect(string $userAgent): string
    {
        if (empty(trim($userAgent))) {
            return 'Desktop';
        }

        $userAgent = strtolower($userAgent);

        // Check for Tablet first because tablets often include 'mobile' in their UA
        // e.g. Android tablet might have "Android" but not "Mobile", or might have both if it's weird.
        // Usually, Android tablets have "Android" but NOT "Mobile Safari", whereas phones have both.
        // Let's use some common tablet keywords.
        $tabletKeywords = ['ipad', 'tablet', 'kindle', 'playbook', 'silk', 'sm-t'];
        foreach ($tabletKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return 'Tablet';
            }
        }

        // Additional heuristic: Android without Mobile is usually a tablet
        if (str_contains($userAgent, 'android') && !str_contains($userAgent, 'mobile')) {
            return 'Tablet';
        }

        // Check for Mobile
        $mobileKeywords = ['mobile', 'iphone', 'ipod', 'android', 'windows phone', 'blackberry', 'webos', 'opera mini', 'iemobile'];
        foreach ($mobileKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return 'Mobile';
            }
        }

        return 'Desktop';
    }
}
