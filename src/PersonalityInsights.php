<?php

namespace FindBrok\PersonalityInsights;

use FindBrok\PersonalityInsights\Contracts\InsightsContract;
use FindBrok\PersonalityInsights\Support\Util\ResultsProcessor;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * Class PersonalityInsights
 *
 * @package FindBrok\PersonalityInsights
 */
class PersonalityInsights extends AbstractPersonalityInsights implements InsightsContract
{
    /**
     * Traits
     */
    use ResultsProcessor;

    /**
     * The Cache repository
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new PersonalityInsights
     *
     * @param array $contentItems
     * @param Cache $cache
     */
    public function __construct($contentItems = [], Cache $cache)
    {
        //New Up a container
        $this->newUpContainer($contentItems);
        //Inject cache service in
        $this->cache = $cache;
    }

    /**
     * @param string $id
     * @return null
     */
    public function getInsight($id = '')
    {
        //No insight with this ID
        if(! $this->has($id, $this->collectTree()))  {
            //We return null
            return null;
        }

    }

    /**
     * Get Full Insights
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFullProfile()
    {
        //We have the request in cache and cache is on
        if ($this->cacheIsOn() && $this->cache->has($this->getContainer()->getCacheKey())) {
            //Return results from cache
            return $this->cache->get($this->getContainer()->getCacheKey());
        }

        //Cross the bridge
        $response = $this->makeBridge()->post('v2/profile', $this->getContainer()->getContentsForRequest());
        //Put the results in the profile props
        $profile = collect(json_decode($response->getBody()->getContents(), true));

        //Cache results if cache is on
        if ($this->cacheIsOn()) {
            $this->cache->put($this->getContainer()->getCacheKey(), $profile, $this->cacheLifetime());
        }

        //Return Results
        return $profile;
    }
}
