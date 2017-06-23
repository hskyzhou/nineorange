<?php
namespace HskyZhou\NineOrange;

/**
 * Class NineOrangeServiceProvider
 * @package HskyZhou\NineOrange
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/nineorange.php' => config_path('nineorange.php')], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/nineorange.php', 'nineorange');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('nineorange', function ($app) {
            return new NineOrange($app);
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
