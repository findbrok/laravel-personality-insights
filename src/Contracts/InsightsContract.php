<?php

namespace FindBrok\PersonalityInsights\Contracts;

/**
 * Interface InsightsContract
 *
 * @package FindBrok\PersonalityInsights\Contracts
 */
interface InsightsContract
{
    /**
     * Get Full Insights
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFullProfile();

    /**
     * Return insight Details
     *
     * @return \Illuminate\Support\Collection
     */
    public function getInsight();
}
