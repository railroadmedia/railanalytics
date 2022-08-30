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
            env('APP_ENV') . ".providers." . brand() .
            '.google-tag-manager.tracking-id'
        );
        return
            "
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','" . $trackingId . "');</script>
            <!-- End Google Tag Manager -->
        "
            . self::$headTop;
    }

    public static function bodyTop()
    {
        $trackingId = config(
            'railanalytics.' .
            env('APP_ENV') . ".providers." . brand() .
            '.google-tag-manager.tracking-id'
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