<?php

namespace Railroad\Railanalytics\Controllers;

use Illuminate\Support\Facades\Cache;

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
            $cacheTrackingData = Cache::store('redis')->get($cacheKey);

            Cache::store('redis')->forget($cacheKey);
        }

        return view('railanalytics::blank-tracking-page', ['cacheKey' => $cacheKey, 'cacheTrackingData' => $cacheTrackingData]);
    }
}
