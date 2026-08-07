<?php

namespace Tests\Unit;

use App\Services\DeviceDetector;
use PHPUnit\Framework\TestCase;

class DeviceDetectorTest extends TestCase
{
    /**
     * Property 6: DeviceDetector Returns Only Valid Categories
     */
    public function test_returns_only_valid_categories()
    {
        $testStrings = [
            '',
            ' ',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Some random string 123 !@#',
            str_repeat('a', 1000)
        ];

        $validCategories = ['Mobile', 'Tablet', 'Desktop'];

        foreach ($testStrings as $ua) {
            $result = DeviceDetector::detect($ua);
            $this->assertContains($result, $validCategories);
        }
    }

    public function test_detects_common_mobile()
    {
        $mobileUAs = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows Phone 10.0; Android 6.0.1; Microsoft; RM-1152) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Mobile Safari/537.36 Edge/15.15254'
        ];

        foreach ($mobileUAs as $ua) {
            $this->assertEquals('Mobile', DeviceDetector::detect($ua));
        }
    }

    public function test_detects_common_tablet()
    {
        $tabletUAs = [
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 10; SM-T860) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 9; Kindle Fire HDX 8.9 Build/KFSAWI) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36'
        ];

        foreach ($tabletUAs as $ua) {
            $this->assertEquals('Tablet', DeviceDetector::detect($ua));
        }
    }

    public function test_detects_common_desktop()
    {
        $desktopUAs = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:89.0) Gecko/20100101 Firefox/89.0'
        ];

        foreach ($desktopUAs as $ua) {
            $this->assertEquals('Desktop', DeviceDetector::detect($ua));
        }
    }

    public function test_detects_empty_string_as_desktop()
    {
        $this->assertEquals('Desktop', DeviceDetector::detect(''));
        $this->assertEquals('Desktop', DeviceDetector::detect('   '));
    }

    public function test_android_tablet_with_mobile_keyword()
    {
        // An Android tablet user agent string that aggressively includes "mobile"
        // But since it's an SM-T series (Samsung tablet), it should be detected as Tablet
        $ua = 'Mozilla/5.0 (Linux; Android 10; SM-T500) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Mobile Safari/537.36';
        $this->assertEquals('Tablet', DeviceDetector::detect($ua));
    }
}
