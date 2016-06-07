<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Illuminate\Support\Collection;

/**
 * Class InsightNode.
 */
class InsightNode extends Collection
{
    /**
     * Create a new ContentListContainer.
     *
     * @param mixed $items
     */
    public function __construct($items = [])
    {
        //Execute parent construct
        parent::__construct($items);
    }

    /**
     * Calculate the percentage for this node.
     *
     * @param int $decimal
     *
     * @return float|null
     */
    public function calculatePercentage($decimal = 1)
    {
        //Percentage not found
        if (! $this->has('percentage')) {
            return;
        }
        //Calculate percentage and return value
        return (float) number_format($this->get('percentage') * 100, $decimal, '.', '');
    }

    /**
     * Calculate the sampling error percentage for this node.
     *
     * @param int $decimal
     *
     * @return float|null
     */
    public function calculateErrorPercentage($decimal = 1)
    {
        //Sampling error not found
        if (! $this->has('sampling_error')) {
            return;
        }
        //Calculate percentage and return value
        return (float) number_format($this->get('sampling_error') * 100, $decimal, '.', '');
    }
}
