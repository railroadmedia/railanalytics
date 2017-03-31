<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleAnalyticsTrackingProvider
{
    public static function getHeadTopTrackingCode()
    {
        $trackingId = config('railanalytics.' .
                             env('APP_ENV') .
                             '.providers.google-analytics.tracking-id');

        return
        "
            <!-- Analytics Tracking -->
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            
                ga('create', '" . $trackingId . "', 'auto');
                ga('require', 'ec');
            </script>
            <!-- ------------------ -->
        ";
    }

    public static function getHeadBottomTrackingCode()
    {

    }

    public static function getBodyTopTrackingCode()
    {

    }

    public static function getBodyBottomTrackingCode()
    {

    }

    public static function trackBase(callable $otherTracking)
    {
        $otherTrackingOutput = $otherTracking();

        return
        "
            <!-- Analytics Tracking -->
            <script>
        " .
        $otherTrackingOutput .
        "
                ga('send', 'pageview');
            </script>

            <!-- ------------------ -->
        ";
    }

    public static function trackProductImpression(
        $id,
        $name,
        $category,
        $currency = 'USD'
    ) {
        return
        "
            ga('ec:addImpression', {
                'id': '" . $id . "',
                'name': '" . $name . "',
                'category': '" . $category . "',
                'position': 1
            });
        ";
    }

    public static function trackProductDetailsImpression(
        $trackGroupName,
        $id,
        $name,
        $category,
        $value,
        $currency = 'USD'
    ) {

    }

    public static function trackAddToCart(
        $trackGroupName,
        $id,
        $name,
        $category,
        $value,
        $quantity,
        $currency = 'USD'
    ) {

    }

    public static function trackInitiateCheckout()
    {

    }

    public static function trackAddPaymentInformation()
    {

    }

    public static function trackTransaction()
    {

    }

    public static function trackRegistration()
    {

    }
}