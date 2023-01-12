<?php

namespace Railroad\Railanalytics\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BlankTrackingPageController
{
    /**
     * @param $name
     * @return mixed|null
     */
    public function show()
    {
        $cacheKey = request()->get('cache_key');
        $cacheTrackingData = [];

        if (!empty($cacheKey)) {
            Log::info('BlankTrackingPageController show $cacheKey: ' . $cacheKey);

            $cacheTrackingData = Cache::store('redis')->get($cacheKey);
            $store = Cache::store('redis');

            Log::info('Cache Prefix railanalytics: ' . $this->getProtectedValue($this->getProtectedValue(Cache::store('redis'), 'store'), 'prefix'));

            Log::info('BlankTrackingPageController show $cacheTrackingData: ' . var_export($cacheTrackingData, true));

//            Cache::store('redis')->forget($cacheKey);
        }

        return view('railanalytics::blank-tracking-page', ['cacheKey' => $cacheKey, 'cacheTrackingData' => $cacheTrackingData]);
    }

    public function getProtectedValue($obj, $name) {
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        return $array[$prefix.$name];
    }
}
