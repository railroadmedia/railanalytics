<?php

namespace Railroad\Railanalytics\TrackingProviders;

class ImpactTrackingProvider
{
    const SESSION_PREFIX = 'railanalytics.impact';

    protected static $headTop = '';
    protected static $bodyTop = '';

    public static function queue()
    {
        session(
            [
                self::SESSION_PREFIX . 'headTop' => self::$headTop,
                self::SESSION_PREFIX . 'bodyTop' => self::$bodyTop
            ]
        );

        self::$headTop = '';
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

    public static function bodyTop()
    {
        self::$bodyTop .= session(self::SESSION_PREFIX . 'bodyTop', '');
        session([self::SESSION_PREFIX . 'bodyTop' => '']);

        return
            "
                <script type='text/javascript'>
                    ire('identify', {customerId: 'test-cutomer-id', customerEmail: 'test-customer-email'});
                </script>
            "
            . self::$bodyTop;
    }

}