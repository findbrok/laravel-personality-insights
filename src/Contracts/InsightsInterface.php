<?php

namespace FindBrok\PersonalityInsights\Contracts;

use FindBrok\PersonalityInsights\Models\Profile;

interface InsightsInterface
{
    /**
     * Pre-load a profile.
     *
     * @param Profile $profile
     *
     * @return $this
     */
    public function loadProfile($profile);

    /**
     * Get Full Insights From Watson API.
     *
     * @return Profile
     */
    public function getProfileFromWatson();

    /**
     * Get Full Insights.
     *
     * @return Profile
     */
    public function getFullProfile();

    /**
     * Get a data item from Profile.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function getFromProfile($id = '');

    /**
     * Get an Insight Data.
     *
     * @param string $id
     *
     * @return \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode|null
     */
    public function getInsight($id = '');

    /**
     * Cleans the object by erasing all profile and content info.
     *
     * @return $this
     */
    public function clean();
}
