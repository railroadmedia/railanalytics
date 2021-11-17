<?php

namespace Railroad\Railanalytics\TrackingProviders;

class TrackingProviderFactory
{
    /**
     * @param $name
     * @return mixed|null
     */
    public static function build($name)
    {
        switch ($name) {
            case 'google-analytics':
            case 'ga':
                return app(GoogleAnalyticsTrackingProvider::class);
            case 'google-tag-manager':
            case 'gtm':
                return app(GoogleTagManagerTrackingProvider::class);
            case 'google-adwords':
            case 'gaw':
                return app(GoogleAdWordsTrackingProvider::class);
            case 'facebook-pixel':
            case 'fp':
                return app(FacebookPixelTrackingProvider::class);
            case 'impact':
            case 'im':
                return app(ImpactTrackingProvider::class);
            default:
                return null;
        }
    }
}