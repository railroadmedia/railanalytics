<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleAnalyticsTrackingProvider
{
    public static function getHeadTopTrackingCode()
    {

    }

    public static function getHeadBottomTrackingCode()
    {

    }

    public static function getBodyTopTrackingCode()
    {

    }

    public static function getBodyBottomTrackingCode()
    {
        $trackingId = config('railanalytics.providers.google-analytics.tracking-id');

        echo
        "
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            
                ga('create', '" . $trackingId . "', 'auto');
                ga('send', 'pageview');
            </script>
        ";
    }

    public static function trackBase($trackGroupName)
    {

    }

    public static function trackProductImpression(
        $trackGroupName,
        $id,
        $name,
        $category,
        $value,
        $currency = null
    ) {

    }

    public static function trackProductDetailsImpression(
        $trackGroupName,
        $id,
        $name,
        $category,
        $value,
        $currency = null
    ) {

    }

    public static function trackAddToCart(
        $trackGroupName,
        $id,
        $name,
        $category,
        $value,
        $quantity,
        $currency = null
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