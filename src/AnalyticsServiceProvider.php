<?php namespace Railroad\Railanalytics;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    protected $listen = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        parent::boot($events);

        $this->publishes(
            [
                __DIR__ . '/../config/railanalytics.php' => config_path('railanalytics.php'),
            ]
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
