<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;

class ContentListContainer extends Collection
{
    /**
     * {@inheritdoc}
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * Remove all invalid contents in the ContentListContainer.
     *
     * @return $this
     */
    public function cleanContainer()
    {
        $this->reject(function ($item) {
            // Remove all which are not content item.
            return ! ($item instanceof ContentItem);
        });

        // Return Container.
        return $this;
    }

    /**
     * Unique cache key for this Container.
     *
     * @return string
     */
    public function getCacheKey()
    {
        // Return Key.
        return 'PersonalityInsights-' .
               Uuid::uuid5(Uuid::NAMESPACE_DNS, collect(['contentItems' => $this->toArray()])->toJson())->toString();
    }

    /**
     * Get the content of the Container for passing to a request.
     *
     * @return string
     */
    public function getContentsForRequest()
    {
        // Return correct format for request.
        return collect(['contentItems' => $this->toArray()])->all();
    }
}
