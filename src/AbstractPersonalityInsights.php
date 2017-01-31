<?php

namespace FindBrok\PersonalityInsights;

use FindBrok\PersonalityInsights\Models\Profile;
use FindBrok\PersonalityInsights\Auth\AccessManager;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentItem;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentListContainer;
use FindBrok\PersonalityInsights\Contracts\InsightsInterface as InsightsContract;

abstract class AbstractPersonalityInsights implements InsightsContract
{
    /**
     * The ContentListContainer instance.
     *
     * @var ContentListContainer
     */
    protected $contentListContainer = null;

    /**
     * The name of the credentials to use.
     *
     * @var string
     */
    protected $credentialsName = null;

    /**
     * The API Version to use.
     *
     * @var string
     */
    protected $apiVersion = null;

    /**
     * Request Headers.
     *
     * @var array
     */
    protected $headers = [
        'Accept' => 'application/json',
    ];

    /**
     * Request Query.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Create the ContentListContainer and push in items.
     *
     * @param array $contentItems
     *
     * @return void
     */
    public function newUpContainer($contentItems = [])
    {
        // New Up Container.
        $this->contentListContainer = app(ContentListContainer::SERVICE_ID, $contentItems);
    }

    /**
     * Get the current Container.
     *
     * @return ContentListContainer
     */
    public function getContainer()
    {
        // Return container.
        return $this->contentListContainer;
    }

    /**
     * Specify the credentials name to use.
     *
     * @param string $name
     *
     * @return $this
     */
    public function usingCredentials($name = null)
    {
        // Set credentials name.
        $this->credentialsName = config('personality-insights.credentials.'.$name);

        // Return this.
        return $this;
    }

    /**
     * Sets the API version to use for the requests.
     *
     * @param string $apiVersion
     *
     * @return $this
     */
    public function usingApiVersion($apiVersion = null)
    {
        // Set credentials name.
        $this->apiVersion = $apiVersion;

        // Return this.
        return $this;
    }

    /**
     * Return the Credential Name to use.
     *
     * @return string
     */
    public function getCredentialsName()
    {
        return $this->credentialsName ?: config('personality-insights.default_credentials');
    }

    /**
     * Return the Api Version to use.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion ?: config('personality-insights.api_version');
    }

    /**
     * Return the headers used for making request.
     *
     * @return array
     */
    protected function getHeaders()
    {
        // Return headers.
        return collect($this->headers)
            ->merge([ 'X-Watson-Learning-Opt-Out' => config('personality-insights.x_watson_learning_opt_out') ])->all();
    }

    /**
     * Get Query to pass additionally to the request,
     *
     * @return string
     */
    protected function getQuery()
    {
        // Our final results.
        $finalQueryString = '';

        // Construct query string.
        foreach ($this->query as $key => $value) {
            $finalQueryString .= $key.'='.$value.'&';
        }

        // We have something in the result.
        if ( ! empty($finalQueryString)) {
            rtrim($finalQueryString, '&');
        }

        // We return our results.
        return $finalQueryString;
    }

    /**
     * Send API Request to Watson and get Response.
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    protected function sendRequest()
    {
        // Get AccessManager.
        $accessManager = $this->makeAccessManager();

        // Make a Watson Bridge.
        $watsonBridge = $this->makeBridge($accessManager);

        // Cross the Bridge and return the Response.
        return $watsonBridge->post($accessManager->getProfileResourcePath().$this->getQuery(), $this->getContainer()
                                                                                                    ->getContentsForRequest());
    }

    /**
     * Make headers as they were.
     *
     * @return void
     */
    protected function cleanHeaders()
    {
        // Clean up header.
        $this->headers = [ 'Accept' => 'application/json' ];
    }

    /**
     * Reset Query data in the Class.
     *
     * @return void
     */
    protected function cleanQuery()
    {
        $this->query = [];
    }

    /**
     * Append Headers to request.
     *
     * @param array $headers
     *
     * @return self
     */
    public function appendHeaders(array $headers = [])
    {
        // Append headers.
        $this->headers = collect($this->headers)->merge($headers)->all();

        // Return calling object.
        return $this;
    }

    /**
     * Add Query to the Request.
     *
     * @param array $query
     *
     * @return self
     */
    public function withQuery(array $query = [])
    {
        // Set Query in class.
        $this->query = collect($this->query)->merge($query)->all();

        return $this;
    }

    /**
     * Creates and returns a new instance of AccessManager.
     *
     * @return AccessManager
     */
    public function makeAccessManager()
    {
        return app(AccessManager::SERVICE_ID, [
            'credentialsName' => $this->getCredentialsName(),
            'apiVersion'      => $this->getApiVersion(),
        ]);
    }

    /**
     * Create a new WatsonBridge to handle Requests.
     *
     * @param AccessManager|null $accessManager
     *
     * @return \FindBrok\WatsonBridge\Bridge;
     */
    public function makeBridge(AccessManager $accessManager = null)
    {
        // Make AccessManager if its not Present.
        $accessManager = $accessManager ?: $this->makeAccessManager();

        // Create and Return Bridge.
        return app('PIBridge', $accessManager->getCredentials())->appendHeaders($this->getHeaders());
    }

    /**
     * Add a ContentItem to ContentListContainer.
     *
     * @param array|ContentItem $items
     *
     * @return self
     */
    public function addSingleContentItem($items = [])
    {
        //Push ContentItem in ContentListContainer
        $this->contentListContainer->push($items instanceof ContentItem ? $items : personality_insights_content_item($items));

        //Return object
        return $this;
    }

    /**
     * Add ContentItems to the Container.
     *
     * @param array $items
     *
     * @return self
     */
    public function addContentItems($items = [])
    {
        //Loop on each item
        collect($items)->each(function ($item) {
            //Add each content to the Container
            $this->addSingleContentItem($item);
        });

        //Return object
        return $this;
    }

    /**
     * Checks if cache is on.
     *
     * @return bool
     */
    public function cacheIsOn()
    {
        return config('personality-insights.cache_on');
    }

    /**
     * Get The cache lifetime in minutes.
     *
     * @return int
     */
    public function cacheLifetime()
    {
        return config('personality-insights.cache_expiration');
    }

    /**
     * Checks if profile data is already loaded in profile prop.
     *
     * @return bool
     */
    public function hasProfilePreLoaded()
    {
        return property_exists($this, 'profile') && ! is_null($this->profile) && $this->profile instanceof Profile;
    }
}
