<?php

namespace FindBrok\PersonalityInsights;

use FindBrok\PersonalityInsights\Contracts\InsightsInterface as InsightsContract;
use FindBrok\PersonalityInsights\Facades\PersonalityInsightsFacade;
use FindBrok\WatsonBridge\Bridge;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class InsightsServiceProvider.
 */
class InsightsServiceProvider extends ServiceProvider
{
    /**
     * Define the implementations contracts maps to which concrete classes.
     *
     * @var array
     */
    protected $implementations = [
        InsightsContract::class => PersonalityInsights::class,
        'PersonalityInsights'   => PersonalityInsights::class,
    ];

    /**
     * Define all Facades here.
     *
     * @var array
     */
    protected $facades = [
        'PersonalityInsights' => PersonalityInsightsFacade::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Publish config file
        $this->publishes([
            __DIR__.'/config/personality-insights.php' => config_path('personality-insights.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Merge Config File
        $this->mergeConfigFrom(__DIR__.'/config/personality-insights.php', 'personality-insights');
        //Register Bindings
        $this->registerBindings();
        //Register Facades
        $this->registerFacades();
    }

    /**
     * Registers all Interface to Class bindings.
     *
     * @return void
     */
    public function registerBindings()
    {
        //Bind Implementations of Interfaces
        collect($this->implementations)->each(function ($class, $interface) {
            //Bind Interface to class
            $this->app->bind($interface, $class);
        });

        //Bind WatsonBridge for Personality insights that we depend on
        $this->app->bind('PersonalityInsightsBridge', function ($app, $args) {
            //Get Username
            $username = config('personality-insights.credentials.'.$args['credentialsName'].'.username');
            //Get Password
            $password = config('personality-insights.credentials.'.$args['credentialsName'].'.password');
            //Get base url
            $url = config('personality-insights.credentials.'.$args['credentialsName'].'.url');
            //Return bridge
            return new Bridge($username, $password, $url);
        });
    }

    /**
     * Registers all facades.
     *
     * @return void
     */
    public function registerFacades()
    {
        //Register all facades
        collect($this->facades)->each(function ($facadeClass, $alias) {
            //Add Facade
            $this->app->booting(function () use ($alias, $facadeClass) {
                //Get loader instance
                $loader = AliasLoader::getInstance();
                //Add alias
                $loader->alias($alias, $facadeClass);
            });
        });
    }
}
