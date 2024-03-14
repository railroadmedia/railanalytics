<?php

namespace Railroad\Railanalytics\TrackingProviders;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $url = "https://" . $baseURL . "/?nid" . $nid . "&adv_event_id=" . $eventID . "&verification_token=" .
            $verificationToken . '&timestamp=' . $timestamp . '&email=' . $email .
            '&aid=' . $brandID . '&order_id=' . $id;


        Log::info("Everflow Track Conversion url: $url");

        $url = str_replace(" ", '%20', $url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);

        $headers = [];
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Content-Length: 0';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(
                'Error in Impact tracking conversion api function from railanalytics: ' . curl_error($curl)
            );
        }

        curl_close($curl);
    }

}
