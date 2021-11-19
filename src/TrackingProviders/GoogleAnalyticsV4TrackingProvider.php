<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleAnalyticsV4TrackingProvider
{
    const SESSION_PREFIX = 'railanalytics.google-analytics-v4';

    protected static $headTop = '';
    protected static $headBottom = '';

    public static function queue()
    {
        session(
            [
                self::SESSION_PREFIX . 'headTop' => self::$headTop,
                self::SESSION_PREFIX . 'headBottom' => self::$headBottom
            ]
        );

        self::$headTop = '';
        self::$headBottom = '';
    }

    /**
     * @return string
     */
    public static function headTop()
    {
        self::$headTop .= session(self::SESSION_PREFIX . 'headTop', '');

        session([self::SESSION_PREFIX . 'headTop' => '']);

        $trackingId = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-analytics-v4.tracking-id'
        );

        return
            self::$headTop .
            "

                <!-- Global site tag (gtag.js) - Google Analytics -->
                <script async src='https://www.googletagmanager.com/gtag/js?id=" . $trackingId . "'></script>
                <script>
                    window.dataLayer = window.dataLayer || [];
                  function gtag(){dataLayer.push(arguments);}
                  gtag('js', new Date());
                  gtag('config', '" . $trackingId. "');
                  
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
    )
    {

        self::$headBottom .=
            "
                <script>
                    gtag('event', 'add_to_cart',{
                        items: [  
                            {
                                item_id: '" . $id . "',
                                item_name: \"" . $name . "\",
                                item_category: \"" . $category . "\",
                                price: '" . $value . "',
                                quantity: " . $quantity . "
                            }
                        ]
                    });
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
    )
    {
        self::$headBottom .=
            "    
                <script>
                    gtag('event', 'view_item', {
                      items: [
                        {
                          item_id: '" . $id . "',
                          item_name:  \"" . $name . "\",
                          item_category: \"" . $category . "\",
                        }
                      ]
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
    )
    {

        self::$headBottom .=
            "
                 <script>
                    gtag('event', 'view_item', {
                      currency: '" . $currency . "',
                      value: '" . $value . "',
                      items: [
                        {
                          item_id: '" . $id . "',
                          item_name:  \"" . $name . "\",
                          item_category: \"" . $category . "\",
                        }
                      ]
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
              $currency = 'USD'
    )
    {
        $output =
            "
                <script>
            ";

        $output .=
            "
                gtag('event', 'purchase', {
                  transaction_id: '" . $transactionId . "',
                  value: '" . $revenue . "',
                  shipping: '" . $shipping . "',
                  tax:  '" . $tax . "',
                  currency:  '" . $currency . "',
                  items: [
            ";

        foreach ($products as $product) {
            $output .=
                "
                    {
                        'item_id': '" . $product['id'] . "',
                        'item_name': \"" . $product['name'] . "\",
                        'item_category': \"" . $product['category'] . "\",
                        'price': '" . $product['value'] . "',
                        'quantity': " . $product['quantity'] . "
                    },
                ";
        }

        $output .=
            "   ],
              });
            " .
            "
                </script>
            ";

        self::$headBottom .= $output;
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

        $output =
            "
                <script>
            ";

        $output .=
            "
                gtag('event', 'begin_checkout', {
                  currency: '" . $currency . "',
                  value: '" . $value . "',
                  items: [
            ";

        foreach ($products as $product) {
            $output .=
                "
                    {
                        'item_id': '" . $product['id'] . "',
                        'item_name': \"" . $product['name'] . "\",
                        'item_category': \"" . $product['type'] . "\",
                        'price': '" . $product['value'] . "',
                        'quantity': " . $product['quantity'] . "
                    },
                ";
        }

        $output .=
            "   ],
              });
            " .
            "
                </script>
            ";

        self::$headBottom .= $output;
    }


}