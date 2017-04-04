<?php

namespace Railroad\Railanalytics;

use Exception;
use Railroad\Railanalytics\TrackingProviders\TrackingProviderFactory;

/**
 * Class Tracker
 *
 * @package Railroad\Railanalytics
 *
 * @method static string headTop()
 * @method static string headBottom()
 * @method static string bodyTop()
 * @method static string bodyBottom()
 *
 * @method static string trackPageView()
 * @method static string trackProductImpression($id, $name, $category, $currency = null)
 * @method static string trackProductDetailsImpression($id, $name, $category, $value, $currency = null)
 * @method static string trackAddToCart($id, $name, $category, $value, $quantity, $currency = null)
 * @method static string trackInitiateCheckout(array $products, $step, $currency = 'USD')
 * @method static string trackAddPaymentInformation()
 * @method static string trackTransaction(array $products, $transactionId, $revenue, $tax, $shipping, $currency = 'USD')
 * @method static string trackRegistration()
 */
class Tracker
{
    /**
     * @param callable $function
     * @throws Exception
     * @internal param $trackGroupName
     */
    public static function queue(callable $function)
    {
        $providerNames = config(
            'railanalytics.' . env('APP_ENV') . '.active-tracking-providers'
        );

        if (empty($providerNames) || !is_array($providerNames)) {
            throw new Exception(
                'Railanalytics is not configured properly, ' .
                'you must set a tracking provider group name.'
            );
        }

        $function();

        /**
         * @var $factory TrackingProviderFactory
         */
        $factory = app(TrackingProviderFactory::class);

        $outputString = '';

        foreach ($providerNames as $providerName) {
            $provider = $factory->build($providerName);

            if (!is_null($provider) && method_exists($provider, 'queue')) {
                $outputString .= call_user_func(
                    [$provider, 'queue']
                );
            }
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $providerNames = config(
            'railanalytics.' . env('APP_ENV') . '.active-tracking-providers'
        );

        if (empty($providerNames) || !is_array($providerNames)) {
            throw new Exception(
                'Railanalytics is not configured properly, ' .
                'you must set a tracking provider group name.'
            );
        }

        /**
         * @var $factory TrackingProviderFactory
         */
        $factory = app(TrackingProviderFactory::class);

        $outputString = '';

        foreach ($providerNames as $providerName) {
            $provider = $factory->build($providerName);

            if (!is_null($provider) && method_exists($provider, $name)) {
                $outputString .= call_user_func_array(
                    [$provider, $name],
                    $arguments
                );
            }
        }

        return $outputString;
    }
}