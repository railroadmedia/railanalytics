<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleAnalyticsTrackingProvider
{
    use GetBrandFromDomain;

    const SESSION_PREFIX = 'railanalytics.google-analytics.';

    protected static $headTop = '';
    protected static $headBottom = '';

    public static function queue($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        session(
            [
                self::SESSION_PREFIX . $brand . '.headTop' => self::$headTop,
                self::SESSION_PREFIX . $brand . '.headBottom' => self::$headBottom
            ]
        );

        self::clear();
    }

    public static function clear() {
        self::$headTop = '';
        self::$headBottom = '';
    }

    /**
     * @return string
     */
    public static function headTop($brand = null)
    {
        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        self::$headTop .= session(self::SESSION_PREFIX . $brand . '.headTop', '');

        session([self::SESSION_PREFIX . $brand . '.headTop' => '']);

        $trackingId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.google-analytics.tracking-id'
        );

        $optimiseId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.google-analytics.optimise-id',
            null
        );

        if (empty($trackingId)) {
            return '';
        }

        $optimiseCode = "";
        $analyticsCode =
            "
                <!-- Analytics Tracking -->
                <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
                
                    ga('create', '" . $trackingId . "', 'auto');
            ";


        if (!empty($optimiseId)) {
            $optimiseCode = "
                <!-- Anti-flicker snippet (recommended)  -->
                <style>.async-hide { opacity: 0 !important} </style>
                <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
                h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
                (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
                })(window,document.documentElement,'async-hide','dataLayer',4000,
                {'GTM-WP9MPV8':true});</script>
            ";

            $analyticsCode .= "ga('require', '" . $optimiseId . "');";
        }

        $analyticsCode .= "
                    ga('require', 'ec');
                </script>
                <!-- ------------------ -->
            ";

        return $optimiseCode . $analyticsCode . self::$headTop;
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

        $trackingId = config(
            'railanalytics.' . $brand . '.' . env('APP_ENV') .
            '.providers.google-analytics.tracking-id'
        );
        
        if (empty($trackingId)) {
            return '';
        }

        return
            self::$headBottom .
            "
                <script>
                    ga('send', 'pageview');
                </script>
            ";
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
        self::$headBottom .=
            "    
                <script>
                    ga('ec:addImpression', {
                        'id': '" . $id . "',
                        'name': \"" . $name . "\",
                        'category': \"" . $category . "\",
                        'position': 1
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
        self::$headBottom .=
            "
                <script>
                    ga('ec:addProduct', {
                        'id': '" . $id . "',
                        'name': \"" . $name . "\",
                        'category': \"" . $category . "\"
                    });
                    
                    ga('ec:setAction', 'detail');
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
        self::$headBottom .=
            "
                <script>
                    ga('ec:addProduct', {
                        'id': '" . $id . "',
                        'name': \"" . $name . "\",
                        'category': \"" . $category . "\",
                        'price': '" . $value . "',
                        'quantity': " . $quantity . "
                    });
                    ga('ec:setAction', 'add');
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
        self::$headBottom .=
            "
                <script>
                    ga('ec:setAction', 'checkout_option', {
                        'step': " . $step . ",
                        'option': '" . $shippingOption . "'
                    });
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
        $output =
            "
                <script>
            ";

        foreach ($products as $product) {
            $output .=
                "
                    ga('ec:addProduct', {
                        'id': '" . $product['id'] . "',
                        'name': \"" . $product['name'] . "\",
                        'category': \"" . $product['category'] . "\",
                        'price': '" . $product['value'] . "',
                        'quantity': " . $product['quantity'] . "
                    });
                ";
        }

        $output .=
            "
                ga('ec:setAction','purchase', {
                    'id': '" . $transactionId . "',
                    'revenue': '" . $revenue . "',
                    'tax': '" . $tax . "',
                    'shipping': '" . $shipping . "'
                });
            ";

        $output .=
            "
                </script>
            ";

        self::$headBottom .= $output;
    }
}