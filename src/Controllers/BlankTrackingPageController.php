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
            $cacheTrackingData = cache('cache')->get($cacheKey);

//            cache('cache')->forget($cacheKey); // todo:

            \Illuminate\Support\Facades\Log::info('--- FUNC: $cacheKey: ' . $cacheKey);
            \Illuminate\Support\Facades\Log::info('--- FUNC: $cacheTrackingData: ' . var_export($cacheTrackingData, true));
        }

        return view('railanalytics::blank-tracking-page', ['cacheKey' => $cacheKey, 'cacheTrackingData' => $cacheTrackingData]);
    }
}
