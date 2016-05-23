<?php

namespace FindBrok\PersonalityInsights\Facades;

use FindBrok\PersonalityInsights\Contracts\InsightsContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class PersonalityInsightsFacade
 *
 * @package FindBrok\PersonalityInsights\Facades
 */
class PersonalityInsightsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function getFacadeAccessor()
    {
        //Return the accessor as being the InsightsContract interface
        return InsightsContract::class;
    }
}
