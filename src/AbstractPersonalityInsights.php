<?php

namespace FindBrok\PersonalityInsights;

use Config;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentItem;
use FindBrok\PersonalityInsights\Support\DataCollector\ContentListContainer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

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
     * Guzzle http client for performing API request
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The request object
     *
     * @var \GuzzleHttp\Psr7\Request
     */
    protected $request;

    /**
     * Create the ContentListContainer and push in items
     *
     * @param array $contentItems
     * @return void
     */
    public function newUpContainer($contentItems = [])
    {
        //New Up Container
        $this->contentListContainer = (new ContentListContainer($contentItems))->cleanContainer();
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
     * Creates the http client
     *
     * @return void
     */
    protected function setClient()
    {
        //Create client using API endpoint sets it the th class variable
        $this->client = new Client([
            'base_uri'  => config('personality-insights.credentials.'.$this->getCredentialsName().'.url'),
        ]);
    }

    /**
     * Return the Http client instance
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        //Return client
        return $this->client;
    }

    /**
     * Return the authorization for making request
     *
     * @return array
     */
    protected function getAuth()
    {
        //Return access authorization
        return [
            'auth' => [
                config('personality-insights.credentials.'.$this->getCredentialsName().'.username'),
                config('personality-insights.credentials.'.$this->getCredentialsName().'.password')
            ]
        ];
    }

    /**
     * Return the headers used for making request
     *
     * @return array
     */
    protected function getHeaders()
    {
        //Return headers
        return [
            'headers' => [
                'Accept' => 'application/json',
                'X-Watson-Learning-Opt-Out' => config('personality-insights.x_watson_learning_opt_out')
            ]
        ];
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
}
