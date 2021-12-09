<?php

namespace Railroad\Railanalytics\TrackingProviders;

use Exception;
use Illuminate\Support\Facades\Auth;

class ImpactTrackingProvider
{
    const SESSION_PREFIX = 'railanalytics.impact';

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
            self::$customerEmail = sha1(Auth::user()->getEmail());
        }
    }

    public static function queue()
    {
        session(
            [
                self::SESSION_PREFIX . 'headTop' => self::$headTop,
                self::SESSION_PREFIX . 'headBottom' => self::$headBottom,
                self::SESSION_PREFIX . 'bodyTop' => self::$bodyTop
            ]
        );

        self::$headTop = '';
        self::$headBottom = '';
        self::$bodyTop = '';
    }


    /**
     * @return string
     */
    public static function headTop()
    {
        self::$headTop .= session(self::SESSION_PREFIX . 'headTop', '');
        session([self::SESSION_PREFIX . 'headTop' => '']);
        $uttLink = config('railanalytics.' . env('APP_ENV') . '.providers.impact.utt-link');

        return
            self::$headTop .
            "
                <!-- Impact Analytics -->
                <script type='text/javascript'>
                    (function(a,b,c,d,e,f,g){e['ire_o']=c;e[c]=e[c]||function(){(e[c].a=e[c].a||[]).push(arguments)};
                        f=d.createElement(b);g=d.getElementsByTagName(b)[0];f.async=1;f.src=a;g.parentNode.insertBefore(f,g);})
                    ('https://utt.impactcdn.com/" .  $uttLink . ".js','script','ire',document,window); 
                </script>
            ";
    }

    /**
     * @return string
     */
    public static function headBottom()
    {
        self::$headBottom .= session(self::SESSION_PREFIX . 'headBottom', '');
        session([self::SESSION_PREFIX . 'headBottom' => '']);

        return
            self::$headBottom . " ";
    }

    /**
     * @return string
     */
    public static function bodyTop()
    {
        self::$bodyTop .= session(self::SESSION_PREFIX . 'bodyTop', '');
        session([self::SESSION_PREFIX . 'bodyTop' => '']);

        return
            "
                <script type='text/javascript'>
                    ire('identify', {customerId: '" . self::$customerId . "', customerEmail: '" . self::$customerEmail . "'});
                </script>
            "
            . self::$bodyTop;
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
    )
    {
        $tagActionTrackerId = config('railanalytics.' . env('APP_ENV') . '.providers.impact.tag-action-tracker-id');
        $signUpActionTrackerId = config('railanalytics.' . env('APP_ENV') . '.providers.impact.sign-up-action-tracker-id');

        $status = "";
        if ($paymentType == "initial_order") {
            $status = "New";
        } elseif ($paymentType == "subscription_renewal") {
            $status = "Returning";
        }

        $totalDiscount = 0;
        $jsonProductsArray = [];
        foreach ($products as $product) {
            $totalDiscount =+ $product['discount'];
            $jsonProductsArray[] = "
                            {
                                subTotal: " . $product['quantity'] * $product['value']. ",
                                category: \"" . $product['category'] . "\",
                                sku: \"" . $product['sku'] . "\",
                                quantity: " . $product['quantity'] . ",
                                name: \"" . $product['name'] . "\",
                            },";

        }
        $output =
            "
                <script type='text/javascript'>
                    ire('trackConversion', $signUpActionTrackerId, {
                        orderId: '" . $transactionId . "',
                        customerId: '" . self::$customerId . "',
                        customerEmail: '" . self::$customerEmail . "'
                    },
                    {
                        verifySiteDefinitionMatch:true
                    }
                    );

                    ire('trackConversion', $tagActionTrackerId, {
                        orderId: '" . $transactionId . "',
                        customerId: '" . self::$customerId . "',
                        customerEmail: '" . self::$customerEmail . "',
                        customerStatus: '" . $status . "',
                        currencyCode: '" . $currency . "',
                        orderPromoCode: '" . $promoCode . "',
                        orderDiscount: " . $totalDiscount . ",
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

    public static function trackTransactionAPI( array $products,
        $transactionId, $promoCode, $currency = 'USD')
    {

        $now = date("Y-m-d") . "T" . date("H:i:s");

        $sid = config('railanalytics.' . env('APP_ENV') . '.providers.impact.sid');
        $authToken = config('railanalytics.' . env('APP_ENV') . '.providers.impact.auth-token');
        $apiActionTrackerId = config('railanalytics.' . env('APP_ENV') . '.providers.impact.api-action-tracker-id');
        $campaignId = config('railanalytics.' . env('APP_ENV') . '.providers.impact.campaign-id');


        $url = "https://" . $sid . ":" . $authToken . "@api.impact.com/Advertisers/" . $sid . "/Conversions?" .
            "CampaignId=" . $campaignId . "&ActionTrackerId=" . $apiActionTrackerId . "&EventDate=" . $now.
            "&OrderId=" . $transactionId . "&CustomerId=" . self::$customerId . "&CustomerEmail=C" . self::$customerEmail .
            "&OrderPromoCode=" . $promoCode . "&CurrencyCode=" . $currency;

        foreach ($products as $index => $product) {
            $i = $index + 1;
            $url .= "&ItemCategory" . $i . "=" . $product['category'] ."&ItemSku" . $i . "=" .
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
            throw new Exception('Error in Impact tracking conversion api function from railanalytics: '.curl_error($curl));
        }

        curl_close($curl);
    }

}