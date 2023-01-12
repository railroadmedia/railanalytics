<?php

namespace Railroad\Railanalytics\Controllers;

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
            $cacheTrackingData = cache()->store('redis')->get($cacheKey);

            cache()->store('redis')->forget($cacheKey);
        }

        return view('railanalytics::blank-tracking-page', ['cacheKey' => $cacheKey, 'cacheTrackingData' => $cacheTrackingData]);
    }
}
