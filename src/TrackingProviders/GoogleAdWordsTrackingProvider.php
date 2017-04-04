<?php

namespace Railroad\Railanalytics\TrackingProviders;

class GoogleAdWordsTrackingProvider extends TrackingProviderBase
{
    const NAME = 'google-adwords';

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

        return
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
                <img height='1' width='1' style='border-style:none;' alt='' src='//www.googleadservices.com/pagead/conversion/1071462884/?value=197.00&amp;currency_code=USD&amp;label=Mo1VCNzVp2UQ5PP0_gM&amp;guid=ON&amp;script=0'/>
                </div>
                </noscript>
            ";
    }
}