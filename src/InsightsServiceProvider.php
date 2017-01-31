<?php

namespace FindBrok\PersonalityInsights;

use JsonMapper;
use FindBrok\WatsonBridge\Bridge;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use FindBrok\PersonalityInsights\Auth\AccessManager;
use FindBrok\PersonalityInsights\Facades\PersonalityInsightsFacade;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentListContainer;
use FindBrok\PersonalityInsights\Contracts\InsightsInterface as InsightsContract;

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
        // Publish config file
        $this->publishes([
            __DIR__ . '/config/personality-insights.php' => config_path('personality-insights.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge Config File
        $this->mergeConfigFrom(__DIR__ . '/config/personality-insights.php', 'personality-insights');

        // Register Bindings
        $this->registerBindings();

        // Register Facades
        $this->registerFacades();
    }

    /**
     * Registers all Interface to Class bindings.
     *
     * @return void
     */
    public function registerBindings()
    {
        // Bind Implementations of Interfaces.
        collect($this->implementations)->each(function ($class, $interface) {
            $this->app->bind($interface, $class);
        });

        // Bind AccessManager.
        $this->app->bind(AccessManager::SERVICE_ID, function ($app, $args) {
            return new AccessManager($args['credentialsName'], $args['apiVersion']);
        });

        // Bind WatsonBridge for Personality insights that we depend on.
        $this->app->bind('PIBridge', function ($app, $args) {
            return new Bridge($args['username'], $args['password'], $args['url']);
        });

        // Bind PersonalityInsights ContentListContainer in App.
        $this->app->bind(ContentListContainer::SERVICE_ID, function ($app, $contentItems) {
            return (new ContentListContainer($contentItems))->cleanContainer();
        });

        // JSON Mapper Service.
        $this->app->bind(JsonMapper::class, function ($app) {
            return new JsonMapper;
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
