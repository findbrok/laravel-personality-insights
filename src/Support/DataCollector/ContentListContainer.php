<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

/**
 * Class ContentListContainer.
 */
class ContentListContainer extends Collection
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
     * Remove all invalid contents in the ContentListContainer.
     *
     * @return void
     */
    public function cleanContainer()
    {
        $this->reject(function ($item) {
            //Remove all which are not content item
            return !($item instanceof ContentItem);
        });
        //Return Container
        return $this;
    }

    /**
     * Unique cache key for this Container.
     *
     * @return string
     */
    public function getCacheKey()
    {
        //Return Key
        return 'PersonalityInsights-'.Uuid::uuid5(Uuid::NAMESPACE_DNS, collect(['contentItems' => $this->toArray()])->toJson())->toString();
    }

    /**
     * Get the content of the Container for passing to a request.
     *
     * @return string
     */
    public function getContentsForRequest()
    {
        //Return correct format for request
        return collect(['contentItems' => $this->toArray()])->all();
    }
}
