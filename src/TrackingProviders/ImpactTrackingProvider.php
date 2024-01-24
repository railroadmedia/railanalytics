<?php

namespace Railroad\Railanalytics\TrackingProviders;

use Exception;
use Illuminate\Support\Facades\Auth;
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


    /**
     * @param array $products
     * @param $transactionId
     * @param $revenue
     * @param $tax
     * @param $shipping
     * @param string $currency
     */
    public static function trackTransaction(
        array $products,
        $transactionId,
        $revenue,
        $tax,
        $shipping,
        $paymentType = '',
        $promoCode = '',
        $currency = 'USD'
    ) {
        $brand = Tracker::$brandOverride;

        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        $tagActionTrackerId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.tag-action-tracker-id'
        );

        if (empty($tagActionTrackerId)) {
            return '';
        }

        $status = "";
        if ($paymentType == "initial_order") {
            $status = "New";
        } elseif ($paymentType == "subscription_renewal") {
            $status = "Returning";
        }

        $jsonProductsArray = [];
        foreach ($products as $product) {
            $jsonProductsArray[] = "
                            {
                                subTotal: " . $product['quantity'] * $product['value'] . ",
                                category: \"" . $product['category'] . "\",
                                sku: \"" . $product['sku'] . "\",
                                quantity: " . $product['quantity'] . ",
                                name: \"" . $product['name'] . "\",
                            },";
        }
        $output =
            "
                <script type='text/javascript'>
                    ire('trackConversion', $tagActionTrackerId, {
                        orderId: '" . $transactionId . "',
                        customerId: '" . self::$customerId . "',
                        customerEmail: '" . self::$customerEmail . "',
                        customerStatus: '" . $status . "',
                        currencyCode: '" . $currency . "',
                        orderPromoCode: '" . $promoCode . "',
                        items: [";
        $output .= implode(" ", $jsonProductsArray);
        $output .=
            "
                        ],
                    });
            " .
            "
                </script>
            ";

        self::$headBottom .= $output;
    }

    public static function trackTransactionAPI(
        array $products,
        $brand,
        $transactionId,
        $promoCode,
        $userID,
        $email,
        $currency = 'USD',
    ) {
        $now = date("Y-m-d") . "T" . date("H:i:s");

        $sid = config('railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.sid');
        $authToken = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.auth-token'
        );
        $apiActionTrackerId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.api-action-tracker-id'
        );
        $campaignId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.impact.campaign-id'
        );

        $url = "https://" . $sid . ":" . $authToken . "@api.impact.com/Advertisers/" . $sid . "/Conversions?" .
            "CampaignId=" . $campaignId . "&ActionTrackerId=" . $apiActionTrackerId . "&EventDate=" . $now .
            "&OrderId=" . $transactionId . "&CustomerId=" . $userID . "&CustomerEmail=C" . $email .
            "&OrderPromoCode=" . $promoCode . "&CurrencyCode=" . $currency;

        foreach ($products as $index => $product) {
            $i = $index + 1;
            $url .= "&ItemCategory" . $i . "=" . $product['category'] . "&ItemSku" . $i . "=" .
                $product['sku'] . "&ItemSubtotal" . $i . "=" . $product['value'] . "&ItemQuantity" . $i . "=" . $product['quantity'];
        }

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
