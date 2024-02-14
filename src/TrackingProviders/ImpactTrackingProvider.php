<?php

namespace Railroad\Railanalytics\TrackingProviders;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Railroad\Railanalytics\Tracker;

class ImpactTrackingProvider
{
    use GetBrandFromDomain;

    const SESSION_PREFIX = 'railanalytics.impact.';

    protected static $headTop = '';
    protected static $headBottom = '';
    protected static $bodyTop = '';
    private static $customerId = '';
    private static $customerEmail = '';

    /**
     * ImpactTrackingProvider constructor.
     *
     */
    public function __construct()
    {
        if (Auth::user()) {
            self::$customerId = Auth::user()->getId();
            self::$customerEmail = md5(Auth::user()->getEmail());
        }
    }

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

    /**
     * @return string
     */
    public static function headBottom($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        self::$headBottom .= session(self::SESSION_PREFIX . $brand . '.headBottom', '');

        session([self::SESSION_PREFIX . $brand . '.headBottom' => '']);

        return
            self::$headBottom . " ";
    }

    public static function trackTransactionAPI(
        array $products,
        $transactionId,
        $promoCode,
        $userID,
        $email,
        $currency = 'USD',
        $affiliateClickCode = null
    ) {
        $brand = Tracker::$brandOverride;

        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        $now = date("Y-m-d") . "T" . date("H:i:s");

        $sid = config('railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.sid');
        $authToken = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.auth-token'
        );
        $hasTrial = array_filter($products, function($product) { return $product['category'] == 'TrialStart';}) ;
        $tagName = $hasTrial ? 'tag-action-tracker-id' : 'api-action-tracker-id';
        $apiActionTrackerId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.' . $tagName
        );
        $campaignId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.campaign-id'
        );
        $hashedEmail = md5($email);
        $url = "https://" . $sid . ":" . $authToken . "@api.impact.com/Advertisers/" . $sid . "/Conversions?" .
            "CampaignId=" . $campaignId . "&ActionTrackerId=" . $apiActionTrackerId . "&EventDate=" . $now .
            "&OrderId=" . $transactionId . "&CustomerId=" . $userID . "&CustomerEmail=C" . $hashedEmail .
            "&OrderPromoCode=" . $promoCode . "&CurrencyCode=" . $currency;

        if (!empty($affiliateClickCode)) {
            $url .= "&ClickId=" . $affiliateClickCode;
        }

        foreach ($products as $index => $product) {
            $i = $index + 1;
            $url .= "&ItemCategory" . $i . "=" . $product['category'] . "&ItemSku" . $i . "=" .
                $product['sku'] . "&ItemSubtotal" . $i . "=" . $product['value'] . "&ItemQuantity" . $i . "=" . $product['quantity'];
        }

        Log::info("Impact Track Purchase url: $url");

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
