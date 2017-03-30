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
            default:
                return null;
        }
    }
}