<?php

namespace FindBrok\PersonalityInsights;

use Config;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentItem;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentListContainer;

/**
 * Class AbstractPersonalityInsights
 *
 * @package FindBrok\PersonalityInsights
 */
abstract class AbstractPersonalityInsights
{
    /**
     * The ContentListContainer
     *
     * @var ContentListContainer
     */
    protected $contentListContainer = null;

    /**
     * Get then name of the credentials to use
     *
     * @var string
     */
    protected $credentialsName = null;

    /**
     * Request Headers
     *
     * @var array
     */
    protected $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * Create the ContentListContainer and push in items
     *
     * @param array $contentItems
     * @return void
     */
    protected function newUpContainer($contentItems = [])
    {
        //New Up Container
        $this->contentListContainer = (new ContentListContainer($contentItems))->cleanContainer();
    }

    /**
     * Get the current Container
     *
     * @return ContentListContainer
     */
    public function getContainer()
    {
        //Return container
        return $this->contentListContainer;
    }

    /**
     * Specify the credentials name to use
     *
     * @param string $name
     * @return self
     */
    public function usingCredentials($name = null)
    {
        //set credentials name
        $this->credentialsName = Config::has('personality-insights.credentials.'.$name) ? $name : null;
        //Return this object
        return $this;
    }

    /**
     * Return the Credential Name to use
     *
     * @return string
     */
    public function getCredentialsName()
    {
        //Return Credential name
        return is_null($this->credentialsName) ? config('personality-insights.default_credentials') : $this->credentialsName;
    }

    /**
     * Return the headers used for making request
     *
     * @return array
     */
    protected function getHeaders()
    {
        //Return headers
        return collect($this->headers)->merge([
            'X-Watson-Learning-Opt-Out' => config('personality-insights.x_watson_learning_opt_out')
        ])->all();
    }

    /**
     * Make headers as they were
     *
     * @return void
     */
    protected function cleanHeaders()
    {
        //Clean up header
        $this->headers = ['Accept' => 'application/json'];
    }

    /**
     * Append Headers to request
     *
     * @param array $headers
     * @return self
     */
    public function appendHeaders($headers = [])
    {
        //Append headers
        $this->headers = collect($this->headers)->merge($headers)->all();
        //Return calling object
        return $this;
    }

    /**
     * Create a new WatsonBridge to handle Requests
     *
     * @return Bridge
     */
    public function makeBridge()
    {
        //Return the bridge
        return app('PersonalityInsightsBridge', ['credentialsName' => $this->getCredentialsName()])->appendHeaders($this->getHeaders());
    }

    /**
     * Add a ContentItem to ContentListContainer
     *
     * @param array|\FindBrok\PersonalityInsights\Support\DataCollector\ContentItem $items
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
     * Add ContentItems to the Container
     *
     * @param array $items
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
     * Checks if cache is on
     *
     * @return bool
     */
    public function cacheIsOn()
    {
        return config('personality-insights.cache_on');
    }

    /**
     * Get The cache lifetime in minutes
     *
     * @return int
     */
    public function cacheLifetime()
    {
        return config('personality-insights.cache_expiration');
    }

    /**
     * Checks if profile data is already loaded in profile prop
     *
     * @return bool
     */
    public function hasProfilePreLoaded()
    {
        return property_exists($this, 'profile') && ! is_null($this->profile);
    }
}
