<?php

namespace FindBrok\PersonalityInsights\Support\Util;
use Illuminate\Support\Collection;

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
     * @return \Illuminate\Support\Collection
     */
    public function getTree()
    {
        return collect($this->getFullProfile()->get('tree'));
    }

    /**
     * Collect all item in the results tree
     *
     * @return \Illuminate\Support\Collection
     */
    public function collectTree()
    {
        return $this->collectAll($this->getTree());
    }

    /**
     * Make all arrays as Collection
     *
     * @param \Illuminate\Support\Collection $node
     * @return \Illuminate\Support\Collection
     */
    public function collectAll($node)
    {
        return $node->transform(function ($item) {
            if (! is_array($item)) {
                return $item;
            } else {
                return $this->collectAll(collect($item));
            }
        });
    }

    /**
     * Check if we have specified ID, in profile
     *
     * @param string $id
     * @param \Illuminate\Support\Collection $node
     * @return bool
     */
    public function has($id = '', $node)
    {
        //No node
        if ($node == null) {
            return false;
        }
        //We have the id
        if($node->has('id') && $node->get('id') == $id) {
            //We found it
            return true;
        } else if($node->has('children') && $node->get('children') instanceof Collection) {
            //Check in each children
            foreach($node->get('children') as $childNode) {
                if($this->has($id, $childNode)) {
                    //We found it
                    return true;
                }
            }
        } else {
            //Nothing found
            return false;
        }
    }
}
