<?php

namespace Railroad\Railanalytics\TrackingProviders;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Railroad\Railanalytics\Tracker;

class EverflowTrackingProvider
{
    use GetBrandFromDomain;

    const SESSION_PREFIX = 'railanalytics.everflow.';

    protected static $headTop = '';
    protected static $headBottom = '';
    protected static $bodyTop = '';

    /**
     * EverflowTrackingProvider constructor.
     *
     */
    public function __construct()
    {}

    public static function queue($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        session(
            [
                self::SESSION_PREFIX . $brand . '.headTop' => self::$headTop,
                self::SESSION_PREFIX . $brand . '.headBottom' => self::$headBottom,
                self::SESSION_PREFIX . $brand . '.bodyTop' => self::$bodyTop,
            ]
        );

        self::clear();
    }

    public static function clear() {
        self::$headTop = '';
        self::$bodyTop = '';
        self::$headBottom = '';
    }

    public static function trackEverFlowConversionAPI(
        $id,
        $email,
        $timestamp
    ) {
        $brand = Tracker::$brandOverride;

        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }
        $baseURL = config('railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.everflow.base_link');
        $verificationToken = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.everflow.verification_token'
        );
        $brandID = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.everflow.brand_id'
        );
        $eventID = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.everflow.conversion_event_id'
        );

        $nid = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.everflow.nid'
        );

        $parameters = [
            'nid' => $nid,
            'adv_event_id' => $eventID,
            'verification_token' => $verificationToken,
            'timestamp' => $timestamp,
            'email' => $email,
            'aid' => $brandID,
            'order_id' => $id
        ];

        $response = Http::post($baseURL, $parameters);
        if ($response->status() != 200) {
            $msg = print_r($response->body(), true);
            $status = $response->status();
            $parametersString = print_r($parameters, true);
            Log::warning("Everflow Tracking returned $status with contents $msg");
            Log::warning("Data sent: $baseURL with packet $parametersString ");

        }
    }

}
