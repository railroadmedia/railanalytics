<?php

namespace Railroad\Railanalytics;

use Exception;
use Illuminate\Support\Facades\Log;
use Railroad\Railanalytics\TrackingProviders\GetBrandFromDomain;
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
 * @method static string clear()
 *
 * @method static string trackPageView()
 * @method static string trackProductImpression($id, $name, $category, $currency = null)
 * @method static string trackProductDetailsImpression($id, $name, $category, $value, $currency = null)
 * @method static string trackAddToCart($id, $name, $category, $value, $quantity, $currency = null)
 * @method static string trackInitiateCheckout(array $products, $step, $currency = 'USD')
 * @method static string trackAddPaymentInformation()
 * @method static string trackTransaction(array $products, $transactionId, $revenue, $tax, $shipping, $paymentType, $promoCode, $currency = 'USD')
 * @method static string trackLead($value = null, $currency = 'USD')
 * @method static string trackTransactionAPI(array $products, $transactionId, $promoCode, $userID, $email, $currency = 'USD', $affiliateClickCode=null)
 * @method static string trackEverFlowConversionAPI($orderID, $email, $timestamp)
 */
class Tracker
{
    use GetBrandFromDomain;

    public static $brandOverride = null;

    public static function queue($brand, callable $function)
    {
        self::$brandOverride = $brand;

        $function();

        self::__callStatic('queue', [$brand]);

        self::$brandOverride = null;
    }

    public static function getQueueForBrand($brand): array
    {
        self::$brandOverride = $brand;

        $queueData = [
            'headTop' => self::headTop($brand),
            'headBottom' => self::headBottom($brand),
            'bodyTop' => self::bodyTop($brand),
            'bodyBottom' => self::bodyBottom($brand),
        ];

        self::clear();

        self::$brandOverride = null;

        return $queueData;
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $brand = self::$brandOverride;

        if (empty($brand)) {
            $brand = self::getBrandFromDomain();
        }

        $environment = (in_array(env('APP_ENV'), ['local', 'beta-testing', 'production'])) ? env('APP_ENV') : 'beta-testing';
        $providerNames = config(
            'railanalytics.' . $brand . '.' . $environment . '.active-tracking-providers'
        );

        if (!is_array($providerNames)) {
            Log::error(
                'Railanalytics is not configured properly, ' .
                'you must set a tracking provider group name.'
            );

            return '';
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