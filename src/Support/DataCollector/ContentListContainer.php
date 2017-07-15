<?php

namespace FindBrok\PersonalityInsights\Support\DataCollector;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;

class ContentListContainer extends Collection
{
    /**
     * The Class Service ID.
     */
    const SERVICE_ID = 'PIContentListContainer';

    /**
     * ContentListContainer constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * Sets Contents to the Container.
     *
     * @param array $items
     *
     * @return $this
     */
    public function setContentsItems($items = [])
    {
        $this->items = $this->getArrayableItems($items);

        return $this;
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
        return 'PersonalityInsights-'.Uuid::uuid5(Uuid::NAMESPACE_DNS,
                                                  collect(['contentItems' => $this->toArray()])->toJson())->toString();
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
