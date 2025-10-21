<?php

namespace App\Services;

class UserAgentParser
{
    public static function parse(string $userAgent): array
    {
        $device = self::getDevice($userAgent);
        $browser = self::getBrowser($userAgent);
        $os = self::getOS($userAgent);

        return [
            'device' => $device,
            'browser' => $browser,
            'os' => $os,
        ];
    }

    private static function getDevice(string $userAgent): string
    {
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $userAgent)) {
            if (preg_match('/iPad/i', $userAgent)) {
                return 'Tablet';
            }
            return 'Mobile';
        }
        return 'Desktop';
    }

    private static function getBrowser(string $userAgent): string
    {
        $browsers = [
            '/Edge/i' => 'Microsoft Edge',
            '/Edg/i' => 'Microsoft Edge',
            '/OPR/i' => 'Opera',
            '/Opera/i' => 'Opera',
            '/Chrome/i' => 'Google Chrome',
            '/Safari/i' => 'Safari',
            '/Firefox/i' => 'Mozilla Firefox',
            '/MSIE/i' => 'Internet Explorer',
            '/Trident/i' => 'Internet Explorer',
        ];

        foreach ($browsers as $regex => $browser) {
            if (preg_match($regex, $userAgent)) {
                return $browser;
            }
        }

        return 'Unknown';
    }

    private static function getOS(string $userAgent): string
    {
        $os = [
            '/Windows NT 10/i' => 'Windows 10',
            '/Windows NT 11/i' => 'Windows 11',
            '/Windows NT 6.3/i' => 'Windows 8.1',
            '/Windows NT 6.2/i' => 'Windows 8',
            '/Windows NT 6.1/i' => 'Windows 7',
            '/Windows NT 6.0/i' => 'Windows Vista',
            '/Windows NT 5.1/i' => 'Windows XP',
            '/Windows/i' => 'Windows',
            '/Mac OS X/i' => 'macOS',
            '/Macintosh/i' => 'macOS',
            '/Ubuntu/i' => 'Ubuntu',
            '/Linux/i' => 'Linux',
            '/Android/i' => 'Android',
            '/iPhone/i' => 'iOS',
            '/iPad/i' => 'iOS',
            '/iPod/i' => 'iOS',
        ];

        foreach ($os as $regex => $operatingSystem) {
            if (preg_match($regex, $userAgent)) {
                return $operatingSystem;
            }
        }

        return 'Unknown';
    }
}