<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Illuminate\Support\Collection;

/**
 * Class ContentListContainer
 *
 * @package FindBrok\PersonalityInsights\Support\DataCollector
 */
class ContentListContainer extends Collection
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
     * Remove all invalid contents in the ContentListContainer
     *
     * @return void
     */
    public function cleanContainer()
    {
        $this->reject(function ($item) {
            //Remove all which are not content item
            return ! ($item instanceof ContentItem);
        });
        //Return Container
        return $this;
    }
}
