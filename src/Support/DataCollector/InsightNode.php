<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Illuminate\Support\Collection;

/**
 * Class InsightNode
 *
 * @package FindBrok\PersonalityInsights\Support\DataCollector
 */
class InsightNode extends Collection
{
    /**
     * Create a new ContentListContainer.
     *
     * @param  mixed  $items
     */
    public function __construct($items = [])
    {
        //Execute parent construct
        parent::__construct($items);
    }

    /**
     * Calculate the percentage for this node
     *
     * @return string|null
     */
    public function calculatePercentage()
    {
        //Percentage not found
        if(! $this->has('percentage')) {
            return null;
        }
        //Calculate percentage and return value
        return $this->get('percentage') * 100;
    }
}
