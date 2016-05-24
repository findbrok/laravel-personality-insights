<?php

use FindBrok\PersonalityInsights\Support\DataCollector;

if (! function_exists('personality_insights_content_item')) {
    /**
     * Creates a new ContentItem
     *
     * @param array $item
     * @return DataCollector\ContentItem
     */
    function personality_insights_content_item($item = [])
    {
        //Return new content item
        return new DataCollector\ContentItem($item);
    }
}

if(! function_exists('transform_to_node')) {
    /**
     * Create a new InsightNode from item
     *
     * @param array|\Illuminate\Support\Collection $item
     * @return DataCollector\InsightNode
     */
    function transform_to_node($item)
    {
        //Return new Node
        return new DataCollector\InsightNode(is_array($item) ? collect($item) : $item);
    }
}
