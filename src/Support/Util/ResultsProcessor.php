<?php

namespace FindBrok\PersonalityInsights\Support\Util;

use FindBrok\PersonalityInsights\Support\DataCollector\InsightNode;

/**
 * Class ResultsProcessor
 *
 * @package FindBrok\PersonalityInsights\Support\Util
 */
trait ResultsProcessor
{
    /**
     * Get the results tree
     *
     * @return \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode
     */
    public function getTree()
    {
        return transform_to_node($this->getFullProfile()->get('tree'));
    }

    /**
     * Collect all item in the results tree in nodes
     *
     * @return \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode
     */
    public function collectTree()
    {
        return $this->collectAll($this->getTree());
    }

    /**
     * Make all arrays as Node
     *
     * @param \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode $node
     * @return \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode
     */
    public function collectAll($node)
    {
        return $node->transform(function ($item) {
            if (! is_array($item)) {
                return $item;
            } else {
                return $this->collectAll(transform_to_node($item));
            }
        });
    }

    /**
     * Check if we have specified ID, in profile
     *
     * @param string $id
     * @param \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode $node
     * @return bool
     */
    public function has($id = '', $node)
    {
        //No node
        if ($node == null) {
            return false;
        }
        //We have the id
        if ($node->has('id') && $node->get('id') == $id) {
            //We found it
            return true;
        } elseif ($node->has('children') && $node->get('children') instanceof InsightNode) {
            //Check in each children
            foreach ($node->get('children') as $childNode) {
                if ($this->has($id, $childNode)) {
                    //We found it
                    return true;
                }
            }
        } else {
            //Nothing found
            return false;
        }
    }

    /**
     * Get a node Using its ID
     *
     * @param string $id
     * @param \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode $node
     * @return \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode|null
     */
    public function getNodeById($id = '', $node)
    {
        //No node
        if ($node == null) {
            return null;
        }
        //This is the matching node
        if ($node->get('id') == $id) {
            //Return the node
            return $node;
        } elseif ($node->has('children') && $node->get('children') instanceof InsightNode) {
            //Check in each children
            foreach ($node->get('children') as $childNode) {
                if ($this->getNodeById($id, $childNode) != null) {
                    //We found it
                    return $this->getNodeById($id, $childNode);
                }
            }
        } else {
            //Nothing found
            return null;
        }
    }
}
