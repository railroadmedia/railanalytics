<?php

namespace Railroad\Railanalytics\TrackingProviders;

class TrackingProviderFactory
{
    public static function build($name)
    {
        switch ($name) {
            case 'google-analytics':
            case 'ga':
                return app(GoogleAnalyticsTrackingProvider::class);
        }
    }
}