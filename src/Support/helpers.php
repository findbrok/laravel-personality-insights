<?php

use FindBrok\PersonalityInsights\Support\DataCollector;

if ( ! function_exists('personality_insights_content_item')) {
    /**
     * Creates a new ContentItem.
     *
     * @param array $item
     *
     * @return DataCollector\ContentItem
     */
    function personality_insights_content_item($item = [])
    {
        //Return new content item
        return new DataCollector\ContentItem($item);
    }
}
