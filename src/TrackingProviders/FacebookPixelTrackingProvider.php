<?php

namespace Railroad\Railanalytics\TrackingProviders;

class FacebookPixelTrackingProvider
{
    const SESSION_PREFIX = 'railanalytics.facebook-pixel';

    protected static $bodyBottom = '';

    public static function queue()
    {
        session([self::SESSION_PREFIX . 'bodyBottom' => self::$bodyBottom]);
    }

    public static function headBottom()
    {
        self::$bodyBottom = session(self::SESSION_PREFIX . 'bodyBottom', '');

        session([self::SESSION_PREFIX . 'bodyBottom' => '']);

        $pixelId = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.facebook-pixel.pixel-id'
        );

        return
        "
            <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '" . $pixelId . "'); // Insert your pixel ID here.
            fbq('track', 'PageView');
            </script>
            <noscript><img height='1' width='1' style='display:none'
            src='https://www.facebook.com/tr?id=" . $pixelId . "&ev=PageView&noscript=1'
            /></noscript>
            <!-- DO NOT MODIFY -->
            <!-- End Facebook Pixel Code -->
        ";
    }

    public static function trackRegistration()
    {

    }
}