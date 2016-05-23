<?php

namespace FindBrok\PersonalityInsights;

use FindBrok\PersonalityInsights\Contracts\InsightsContract;

/**
 * Class PersonalityInsights
 *
 * @package FindBrok\PersonalityInsights
 */
class PersonalityInsights extends AbstractPersonalityInsights implements InsightsContract
{
    /**
     * Create a new PersonalityInsights
     *
     * @param array $contentItems
     */
    public function __construct($contentItems = [])
    {
        //New Up a container
        $this->newUpContainer($contentItems);
        //Set Client
        $this->setClient();
    }

    /**
     * Get Insights
     *
     * @return mixed
     */
    public function getProfile()
    {

    }
}
