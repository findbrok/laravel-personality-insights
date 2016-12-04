<?php

namespace FindBrok\PersonalityInsights\Contracts;

interface InsightsInterface
{
    /**
     * Pre-load a profile.
     *
     * @param \Illuminate\Support\Collection $profile
     *
     * @return self
     */
    public function loadProfile($profile);

    /**
     * Get Full Insights From Watson API.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProfileFromWatson();

    /**
     * Get Full Insights.
     *
     * @return \Illuminate\Support\Collection
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
     * @return self
     */
    public function clean();
}
