<?php

namespace FindBrok\PersonalityInsights\Models;

use Illuminate\Support\Collection;
use FindBrok\PersonalityInsights\Models\Contracts\Childrenable;

class TraitTreeNode extends BaseModel implements Childrenable
{
    /**
     * The unique identifier of the characteristic to which the results pertain.
     *
     * @var string
     */
    public $trait_id;

    /**
     * The user-visible name of the characteristic.
     *
     * @var string
     */
    public $name;

    /**
     * The category of the characteristic.
     *
     * @var string
     */
    public $category;

    /**
     * The normalized percentile score for the characteristic. The range is 0 to 1. For example, if the percentile for
     * Openness is 0.60, the author scored in the 60th percentile; the author is more open than 59 percent of the
     * population and less open than 39 percent of the population.
     *
     * @var float
     */
    public $percentile;

    /**
     * If you request raw scores, the raw score for the characteristic. A positive or negative score indicates more or
     * less of the characteristic; zero indicates neutrality or no evidence for a score. The raw score is computed
     * based on the input and the service model; it is not normalized or compared with a sample population. The raw
     * score enables comparison of the results against a different sampling population and with a custom normalization
     * approach.
     *
     * @var float
     */
    public $raw_score;

    /**
     * For personality (Big Five) dimensions, a Collection of TraitTreeNode objects that provides more detailed results
     * for the facets of each dimension as inferred from the input text.
     *
     * @var Collection
     */
    protected $children;

    /**
     * Sets the children.
     *
     * @param TraitTreeNode[] $children
     *
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = collect($children);

        return $this;
    }

    /**
     * Gets Children nodes.
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Checks if TraitTreeNode has Children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return
            $this->children instanceof Collection &&
            $this->children->isNotEmpty();
    }

    /**
     * Calculate the percentage for this node.
     *
     * @param int $decimal
     *
     * @return float|null
     */
    public function calculatePercentage($decimal = 1)
    {
        // Profile not loaded yet.
        if (is_null($this->percentile)) {
            return;
        }

        // Calculate percentage and return value
        return (float) number_format($this->percentile * 100, $decimal, '.', '');
    }
}
