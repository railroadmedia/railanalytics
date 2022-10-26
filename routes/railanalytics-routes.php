<?php

use Illuminate\Support\Facades\Route;
use Railroad\Railanalytics\Controllers\BlankTrackingPageController;

Route::get(
    '/railanalytics/blank-tracking-page',
    [
        'as' => 'railanalytics.blank-tracking-page',
        'uses' => BlankTrackingPageController::class . '@show'
    ]
)->middleware(config('railanalytics.blank_tracking_page_middleware_group_name'));