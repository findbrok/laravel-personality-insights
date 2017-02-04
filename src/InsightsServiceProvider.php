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
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file.
        $this->publishes([__DIR__.'/config/personality-insights.php' => config_path('personality-insights.php'),],
                         'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge Config File.
        $this->mergeConfigFrom(__DIR__.'/config/personality-insights.php', 'personality-insights');

        // Register Bindings.
        $this->registerBindings();

        // Register Facades.
        $this->registerFacades();
    }

    /**
     * Registers all Interface to Class bindings.
     *
     * @return void
     */
    public function registerBindings()
    {
        // Bind Personality Insights interface.
        $this->app->bind(InsightsContract::class, PersonalityInsights::class);

        // Bind Personality Insights Service.
        $this->app->bind(PersonalityInsights::SERVICE_ID, PersonalityInsights::class);

        // Bind AccessManager.
        $this->app->bind(AccessManager::SERVICE_ID,
            function ($app, $args = ['credentialsName' => 'default', 'apiVersion' => 'v3']) {
                return new AccessManager($args['credentialsName'], $args['apiVersion']);
            });

        // Bind WatsonBridge for Personality insights that we depend on.
        $this->app->bind('PIBridge', function ($app, $args = ['username' => '', 'password' => '', 'url' => '']) {
            return new Bridge($args['username'], $args['password'], $args['url']);
        });

        // Bind PersonalityInsights ContentListContainer in App.
        $this->app->bind(ContentListContainer::SERVICE_ID, function ($app, $contentItems = []) {
            return (new ContentListContainer($contentItems))->cleanContainer();
        });
    }

    /**
     * Registers all facades.
     *
     * @return void
     */
    public function registerFacades()
    {
        // Since Laravel 5.4 allows for automatic Facade
        // we do not need to register Facades here.
        if ($this->app->version() >= 5.0 && $this->app->version() < 5.4) {
            // Add Facade.
            $this->app->booting(function () {
                AliasLoader::getInstance()->alias(PersonalityInsights::SERVICE_ID, PersonalityInsightsFacade::class);
            });
        }
    }
}
