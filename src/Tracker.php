<?php

namespace Railroad\Railanalytics;

use Exception;
use Railroad\Railanalytics\TrackingProviders\TrackingProviderFactory;

/**
 * Class Tracker
 *
 * @package Railroad\Railanalytics
 *
 * @method static string getHeadTopTrackingCode($trackGroupName)
 * @method static string getHeadBottomTrackingCode($trackGroupName)
 * @method static string getBodyTopTrackingCode($trackGroupName)
 * @method static string getBodyBottomTrackingCode($trackGroupName)
 * @method static string trackBase($trackGroupName, callable $otherTracking)
 * @method static string trackProductImpression($trackGroupName, $id, $name, $category, $currency = null)
 * @method static string trackProductDetailsImpression($trackGroupName, $id, $name, $category, $value, $currency = null)
 * @method static string trackAddToCart($trackGroupName, $id, $name, $category, $value, $quantity, $currency = null)
 * @method static string trackInitiateCheckout($trackGroupName, array $products, $step, $currency = 'USD')
 * @method static string trackAddPaymentInformation($trackGroupName)
 * @method static string trackTransaction(array $products, $transactionId, $revenue, $tax, $shipping, $currency = 'USD')
 * @method static string trackRegistration($trackGroupName)
 */
class Tracker
{
    const SESSION_KEY = 'railroad.tracker.queue.';

    /**
     * @param $trackGroupName
     * @param callable $function
     */
    public static function queue($trackGroupName, callable $function)
    {
        $sessionQueueString = session('railroad.tracker.queue.' . $trackGroupName, '');

        $sessionQueueString .= $function();

        session(['railroad.tracker.queue.' . $trackGroupName => $sessionQueueString]);
    }

    /**
     * @param $trackGroupName
     * @return string
     */
    public static function trackAndClearQueue($trackGroupName)
    {
        $sessionQueueString = session('railroad.tracker.queue.' . $trackGroupName, '');

        session(['railroad.tracker.queue.' . $trackGroupName => '']);

        return $sessionQueueString;
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $trackGroupName = $arguments[0];
        $providerNames = config(
            'railanalytics.' . env('APP_ENV') . '.tracking-provider-groups.' . $trackGroupName
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
                    array_slice($arguments, 1)
                );
            }
        }

        return $outputString;
    }
}