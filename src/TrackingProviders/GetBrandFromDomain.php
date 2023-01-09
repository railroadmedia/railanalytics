<?php

namespace Railroad\Railanalytics\TrackingProviders;

use Illuminate\Support\Str;

trait GetBrandFromDomain
{
    // this function will match any domain ending in the config key, so it can match all subdomains
    public static function getBrandFromDomain()
    {
        $requestDomain = request()->getHost();
        $brandDomains = config('railanalytics.brand_domains');

        foreach ($brandDomains as $domainToMatch => $brand) {
            if (Str::endsWith($requestDomain, $domainToMatch)) {
                return $brand;
            }
        }

        return null;
    }
}