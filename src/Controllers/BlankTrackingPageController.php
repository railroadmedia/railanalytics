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
            Log::info('BlankTrackingPageController show $cacheTrackingData: ' . var_export($cacheTrackingData, true));

//            Cache::store('redis')->forget($cacheKey);
        }

        return view('railanalytics::blank-tracking-page', ['cacheKey' => $cacheKey, 'cacheTrackingData' => $cacheTrackingData]);
    }
}
