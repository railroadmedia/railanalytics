<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleTagManagerTrackingProvider
{
    use GetBrandFromDomain;

    const SESSION_PREFIX = 'railanalytics.google-tag-manager.';

    protected static $headTop = '';
    protected static $bodyTop = '';

    public static function queue($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        session(
            [
                self::SESSION_PREFIX . $brand . '.headTop' => self::$headTop,
                self::SESSION_PREFIX . $brand . '.bodyTop' => self::$bodyTop
            ]
        );

        self::$headTop = '';
        self::$bodyTop = '';
    }

    public static function headTop($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        self::$headTop .= session(self::SESSION_PREFIX . $brand . '.headTop', '');

        session([self::SESSION_PREFIX . $brand . '.headTop' => '']);

        $trackingId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.google-tag-manager.tracking-id'
        );

        if (empty($trackingId)) {
            return '';
        }

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

    public static function bodyTop($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        self::$bodyTop .= session(self::SESSION_PREFIX . $brand . '.bodyTop', '');

        session([self::SESSION_PREFIX . $brand . '.bodyTop' => '']);

        $trackingId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.google-tag-manager.tracking-id'
        );

        if (empty($trackingId)) {
            return '';
        }

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