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
     * Cleans the object by erasing all profile and content info.
     *
     * @return $this
     */
    public function clean();
}
