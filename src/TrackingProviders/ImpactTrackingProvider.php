<?php

namespace Railroad\Railanalytics\TrackingProviders;

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
                    ire('trackConversion', 27558, {
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

}