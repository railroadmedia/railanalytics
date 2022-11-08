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
        return view('railanalytics::blank-tracking-page');
    }
}
