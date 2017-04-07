<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleTagManagerTrackingProvider
{
    protected static $headTop = '';
    protected static $bodyTop = '';

    public static function headTop()
    {
        $trackingId = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-tag-manager.tracking-id'
        );

        return
            "
            
        "
            . self::$headTop;
    }

    public static function bodyTop()
    {
        $trackingId = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-tag-manager.tracking-id'
        );

        return
            "
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src='https://www.googletagmanager.com/ns.html?id=" . $trackingId . "'
            height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        "
            . self::$bodyTop;
    }
}