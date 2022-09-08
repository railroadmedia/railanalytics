<?php

namespace Railroad\Railanalytics\TrackingProviders;

class FacebookPixelTrackingProvider
{
    const SESSION_PREFIX = 'railanalytics.facebook-pixel';

    protected static $headBottom = '';
    protected static $bodyTop = '';

    public static function queue()
    {
        session(
            [
                self::SESSION_PREFIX . 'headBottom' => self::$headBottom,
                self::SESSION_PREFIX . 'bodyTop' => self::$bodyTop
            ]
        );

        self::$headBottom = '';
        self::$bodyTop = '';
    }

    public static function headBottom()
    {
        self::$headBottom .= session(self::SESSION_PREFIX . 'headBottom', '');

        session([self::SESSION_PREFIX . 'headBottom' => '']);

        $pixelId = config(
            'railanalytics.' .
            env('APP_ENV') . ".providers." . brand() .
            '.facebook-pixel.pixel-id'
        );

        return
            "
            <!-- Facebook Pixel Code -->
            <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '" . $pixelId . "'); // Insert your pixel ID here.
            fbq('track', 'PageView');
            </script>
            <noscript><img height='1' width='1' style='display:none'
            src='https://www.facebook.com/tr?id=" . $pixelId . "&ev=PageView&noscript=1'
            /></noscript>
            <!-- DO NOT MODIFY -->
            <!-- End Facebook Pixel Code -->
        "
            . self::$headBottom;
    }

    public static function bodyTop()
    {
        self::$bodyTop .= session(self::SESSION_PREFIX . 'bodyTop', '');

        session([self::SESSION_PREFIX . 'bodyTop' => '']);

        return self::$bodyTop;
    }

    /**
     * @param $id
     * @param $name
     * @param $category
     * @param string $currency
     */
    public static function trackProductImpression(
        $id,
        $name,
        $category,
        $currency = 'USD'
    ) {
        self::$bodyTop .=
            "    
                <script>
                    fbq('track', 'ViewContent', {
                        content_ids: ['" . $id . "'],
                        content_type: 'product'
                    });
                </script>
            ";
    }

    /**
     * @param $id
     * @param $name
     * @param $category
     * @param $value
     * @param string $currency
     */
    public static function trackProductDetailsImpression(
        $id,
        $name,
        $category,
        $value,
        $currency = 'USD'
    ) {

        self::$bodyTop .=
            "
                <script>
                    fbq('track', 'ViewContent', {
                        content_ids: ['" . $id . "'],
                        content_type: 'product',
                        value: " . number_format($value, 2, '.', '') . ",
                        currency: '" . $currency . "'
                    });
                </script>
        ";
    }

    /**
     * @param $id
     * @param $name
     * @param $category
     * @param $value
     * @param $quantity
     * @param string $currency
     */
    public static function trackAddToCart(
        $id,
        $name,
        $category,
        $value,
        $quantity,
        $currency = 'USD'
    ) {

        self::$bodyTop .=
            "
                <script>
                    fbq('track', 'AddToCart', {
                        content_ids: ['" . $id . "'],
                        content_type: 'product',
                        value: " . number_format($value, 2, '.', '') . ",
                        currency: '" . $currency . "'
                    });
                </script>

            ";
    }

    /**
     * @param array $products
     * @param int $step
     * @param string $currency
     */
    public static function trackInitiateCheckout(
        array $products,
        $step,
        $value,
        $currency = 'USD'
    ) {
        self::$bodyTop .=
            "
                <script>
                    fbq('track', 'InitiateCheckout');
                </script>
            ";
    }

    /**
     * @param $step
     * @param $value
     * @param $shippingOption
     */
    public static function trackAddPaymentInformation($step, $value, $shippingOption)
    {
        self::$bodyTop .=
            "
                <script>
                    fbq('track', 'AddPaymentInfo');
                </script>
            ";
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
        $paymentType,
        $promoCode,
        $currency = 'USD'
    ) {
        self::$bodyTop .=
            "
                <script>
                    fbq('track', 'Purchase', {
                        content_ids: ['"
            . implode("', '", array_column($products, 'id')) . "'],
                        content_type: 'product',
                        value: " . number_format($revenue, 2, '.', '') . ",
                        currency: '" . $currency . "'
                    });
                </script>
            ";
    }

    /**
     * @param $value
     * @param string $currency
     */
    public static function trackLead(
        $value = null,
        $currency = 'USD'
    ) {
        if (is_null($value)) {
            self::$bodyTop .=
                "
                   <script>
                       fbq('track', 'Lead', {});
                   </script>
                ";
        } else {
            self::$bodyTop .=
                "
                    <script>
                        fbq('track', 'Lead', {
                            value: " . number_format($value, 2, '.', '') . ",
                            currency: '" . $currency . "'
                        });
                    </script>
                ";
        }
    }
}