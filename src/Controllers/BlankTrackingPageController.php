<?php

namespace Railroad\Railanalytics\Controllers;

use Railroad\Railanalytics\Tracker;

class BlankTrackingPageController
{
    /**
     * @param $name
     * @return mixed|null
     */
    public function show()
    {
        return view('railanalytics::blank-tracking-page');
    }
}