<?php

namespace FindBrok\PersonalityInsights;

use JsonMapper;
use FindBrok\PersonalityInsights\Models\Profile;
use Illuminate\Contracts\Cache\Repository as Cache;
use FindBrok\PersonalityInsights\Support\Util\ResultsProcessor;

class PersonalityInsights extends AbstractPersonalityInsights
{
    use ResultsProcessor;

    /**
     * Full profile.
     *
     * @var Profile
     */
    protected $profile;

    /**
     * The Cache repository.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * JsonMapper instance.
     *
     * @var JsonMapper
     */
    protected $jsonMapper;

    /**
     * Create a new PersonalityInsights.
     *
     * @param Cache      $cache
     * @param JsonMapper $jsonMapper
     * @param array      $contentItems
     */
    public function __construct(Cache $cache, JsonMapper $jsonMapper, $contentItems = [])
    {
        $this->cache = $cache;
        $this->jsonMapper = $jsonMapper;

        // New Up a container.
        $this->newUpContainer($contentItems);
    }

    /**
     * Pre-load a profile.
     *
     * @param $profile
     *
     * @return $this
     */
    public function loadProfile($profile)
    {
        // Override profile
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get Full Insights From Watson API.
     *
     * @throws \FindBrok\WatsonBridge\Exceptions\WatsonBridgeException
     *
     * @return Profile
     */
    public function getProfileFromWatson()
    {
        // We have the request in cache and cache is on.
        if ($this->cacheIsOn() && $this->cache->has($this->getContainer()->getCacheKey())) {
            // Return results from cache.
            return $this->cache->get($this->getContainer()->getCacheKey());
        }

        // Send Request to Watson.
        $response = $this->sendRequest();

        // Decode and map onto Object.
        /** @var Profile $profile */
        $profile = $this->jsonMapper->map(json_decode($response->getBody()->getContents()), new Profile);

        // Cache results if cache is on.
        if ($this->cacheIsOn()) {
            $this->cache->put($this->getContainer()->getCacheKey(), $profile, $this->cacheLifetime());
        }

        // Return profile.
        return $profile;
    }

    /**
     * Get Full Insights.
     *
     * @return Profile
     */
    public function getFullProfile()
    {
        // Profile not already loaded.
        if ( ! $this->hasProfilePreLoaded()) {
            // Fetch Profile From Watson API.
            $this->profile = $this->getProfileFromWatson();
        }

        // Return Results.
        return $this->profile;
    }

    /**
     * Cleans the object by erasing all profile and content info.
     *
     * @return $this
     */
    public function clean()
    {
        // Empty Profile.
        $this->profile = null;
        // Empty credentials.
        $this->credentialsName = null;

        // Recreate a new container.
        $this->newUpContainer();
        // Clean headers.
        $this->cleanHeaders();
        // Clean Query
        $this->cleanQuery();

        // Return calling object.
        return $this;
    }
}
