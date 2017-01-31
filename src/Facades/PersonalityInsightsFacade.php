<?php

namespace FindBrok\PersonalityInsights\Facades;

use Illuminate\Support\Facades\Facade;
use FindBrok\PersonalityInsights\Contracts\InsightsInterface as InsightsContract;

class PersonalityInsightsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        // Return the accessor as being the InsightsContract interface.
        return InsightsContract::class;
    }
}
