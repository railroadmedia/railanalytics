<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleAdWordsTrackingProvider
{
    const SESSION_PREFIX = 'railanalytics.google-analytics';

    protected static $bodyTop = '';

    public static function queue()
    {
        session([self::SESSION_PREFIX . 'bodyTop' => self::$bodyTop]);

        self::$bodyTop = '';
    }

    public static function bodyTop()
    {
        self::$bodyTop .= session(self::SESSION_PREFIX . 'bodyTop', '');

        session([self::SESSION_PREFIX . 'bodyTop' => '']);

        return self::$bodyTop;
    }

    public static function trackTransaction(
        array $products,
        $transactionId,
        $revenue,
        $tax,
        $shipping,
        $currency = 'USD'
    ) {
        $conversionId = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-adwords.google-conversion-id'
        );
        $conversionLanguage = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-adwords.google-conversion-language'
        );
        $conversionFormat = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-adwords.google-conversion-format'
        );
        $conversionColor = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-adwords.google-conversion-color'
        );
        $conversionLabel = config(
            'railanalytics.' .
            env('APP_ENV') .
            '.providers.google-adwords.google-conversion-label'
        );

        $revenue = number_format($revenue, 2, '.', '');

        self::$bodyTop .=
            "
                <!-- Google Code for Drumeo Orders Conversion Page -->
                <script type='text/javascript'>
                    /* <![CDATA[ */
                    var google_conversion_id = " . $conversionId . ";
                    var google_conversion_language = '" . $conversionLanguage . "';
                    var google_conversion_format = '" . $conversionFormat . "';
                    var google_conversion_color = '" . $conversionColor . "';
                    var google_conversion_label = '" . $conversionLabel . "';
                    var google_conversion_value = " . $revenue . ";
                    var google_conversion_currency = '" . $currency . "';
                    var google_remarketing_only = false;
                    /* ]]> */
                </script>
                <script type='text/javascript' src='//www.googleadservices.com/pagead/conversion.js'>
                </script>
                <noscript>
                <div style='display:inline;'>
                <img height='1' width='1' style='border-style:none;' alt='' src='//www.googleadservices.com/pagead/conversion/" . $conversionId . "/?value=" . $revenue . "&amp;currency_code=" . $currency . "&amp;label=" . $conversionLabel . "&amp;guid=ON&amp;script=0'/>
                </div>
                </noscript>
            ";
    }
}