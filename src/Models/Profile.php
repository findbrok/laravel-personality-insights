<?php

namespace FindBrok\PersonalityInsights\Models;

class Profile
{
    /**
     * The number of words that were found in the input.
     *
     * @var int
     */
    public $word_count;

    /**
     * The language model that was used to process the input; for example, en.
     *
     * @var string
     */
    public $processed_language;

    /**
     * A recursive array of TraitTreeNode objects that provides detailed results for the Big Five personality
     * characteristics (dimensions and facets) inferred from the input text.
     *
     * @var array
     */
    public $personality;
}