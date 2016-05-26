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
    public function hasInsight($id = '', $node)
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
                if ($this->hasInsight($id, $childNode)) {
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

    /**
     * Get Word count
     *
     * @return int|null
     */
    public function getWordCount()
    {
        return $this->getFromProfile('word_count');
    }

    /**
     * Get Source
     *
     * @return mixed
     */
    public function getSource()
    {
        return $this->getFromProfile('source');
    }

    /**
     * Get the author
     *
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->getFromProfile('id');
    }

    /**
     * Get the language analysed
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getFromProfile('processed_lang');
    }

    /**
     * Get the results tree
     *
     * @return \FindBrok\PersonalityInsights\Support\DataCollector\InsightNode
     */
    public function getTree()
    {
        return transform_to_node($this->getFromProfile('tree'));
    }

    /**
     * Get the Message word count if exists
     *
     * @return string
     */
    public function getWordCountMessage()
    {
        return $this->getFromProfile('word_count_message');
    }

    /**
     * Get the analysis level
     *
     * @return string
     */
    public function analysisLevel()
    {
        //Get Word count
        $wordCount = $this->getWordCount();
        //Very Strong
        if($wordCount >= 6000) {
            return 'Very Strong';
        } elseif ($wordCount < 6000 && $wordCount >= 3500) {
            //Strong analysis
            return 'Strong';
        } elseif ($wordCount < 3500 && $wordCount >= 100) {
            //Weak analysis
            return 'Weak';
        } elseif ($wordCount == 100) {
            //Very weak
            return 'Very Weak';
        }
    }

    /**
     * Check if analysis is very strong
     *
     * @return bool
     */
    public function isAnalysisVeryStrong()
    {
        return $this->analysisLevel() == 'Very Strong';
    }

    /**
     * Check if analysis is strong
     *
     * @return bool
     */
    public function isAnalysisStrong()
    {
        return $this->isAnalysisVeryStrong() || $this->analysisLevel() == 'Strong';
    }

    /**
     * Check if analysis is weak
     *
     * @return bool
     */
    public function isAnalysisWeak()
    {
        return $this->isAnalysisVeryWeak() || $this->analysisLevel() == 'Weak';
    }

    /**
     * Check if analysis is very weak
     *
     * @return bool
     */
    public function isAnalysisVeryWeak()
    {
        return $this->analysisLevel() == 'Very Weak';
    }
}
