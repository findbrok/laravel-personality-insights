<?php

namespace FindBrok\PersonalityInsights;

use FindBrok\WatsonBridge\Bridge;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
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
        $this->publishes([
            __DIR__.'/../config/personality-insights.php' => config_path('personality-insights.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge Config File.
        $this->mergeConfigFrom(__DIR__.'/../config/personality-insights.php', 'personality-insights');

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
    protected function registerBindings()
    {
        // Bind Personality Insights interface.
        $this->app->bind(InsightsContract::class, PersonalityInsights::class);

        // Bind Personality Insights Service.
        $this->app->bind(PersonalityInsights::SERVICE_ID, PersonalityInsights::class);

        // Registers the Access Manager.
        $this->registerAccessManager();

        // Registers the Bridge.
        $this->registerBridge();

        // Bind PersonalityInsights ContentListContainer in App.
        $this->app->bind(ContentListContainer::SERVICE_ID, function () {
            return (new ContentListContainer)->cleanContainer();
        });
    }

    /**
     * Registers the Access Manager in
     * the Container.
     *
     * @return void
     */
    protected function registerAccessManager()
    {
        // Bind AccessManager.
        $this->app->singleton(AccessManager::SERVICE_ID, function (Application $app) {
            /** @var Repository $configRepo */
            $configRepo = $app->make('config');

            return new AccessManager(
                $configRepo->get('personality-insights.default_credentials'),
                $configRepo->get('personality-insights.api_version')
            );
        });
    }

    /**
     * Registers the Bridge.
     *
     * @return void
     */
    protected function registerBridge()
    {
        // Bind WatsonBridge for Personality insights that we depend on.
        $this->app->bind('PIBridge', function (Application $app) {
            // Get Default Credentials.
            $credentials = $app->make(AccessManager::SERVICE_ID)->getCredentials();

            // Return an Instance of Bridge.
            return new Bridge($credentials['username'], $credentials['password'], $credentials['url']);
        });
    }

    /**
     * Registers all facades.
     *
     * @return void
     */
    protected function registerFacades()
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
