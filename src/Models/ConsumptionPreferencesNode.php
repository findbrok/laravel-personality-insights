<?php

namespace FindBrok\PersonalityInsights\Models;

use FindBrok\PersonalityInsights\Models\Contracts\Childrenable;

class ConsumptionPreferencesNode extends BaseModel implements Childrenable
{
    /**
     * The unique identifier of the consumption preferences category to which the results pertain. IDs have the form
     * consumption_preferences_category.
     *
     * @var string
     */
    public $consumption_preference_id;

    /**
     * The user-visible name of the consumption preference.
     *
     * @var string
     */
    public $name;

    /**
     * The score for the consumption preference:
     * 0.0 (unlikely)
     * 0.5 (neutral)
     * 1.0 (likely)
     * The scores for some preferences are binary and do not allow a neutral value. The score is an indication of
     * preference based on the results inferred from the input text, not a normalized percentile.
     *
     * @var float
     */
    public $score;

    /**
     * Checks if ConsumptionPreferencesNode has Children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return false;
    }

    /**
     * Returns children nodes.
     *
     * @return null
     */
    public function getChildren()
    {
        return null;
    }

    /**
     * Checks if Profile is Likely to this preference.
     *
     * @return bool
     */
    public function isLikely()
    {
        return $this->score == 1.0;
    }

    /**
     * Checks if Profile is Unlikely to this preference.
     *
     * @return bool
     */
    public function isUnlikely()
    {
        return $this->score == 0.0;
    }

    /**
     * Checks if Profile is Neutral to this preference.
     *
     * @return bool
     */
    public function isNeutral()
    {
        return $this->score == 0.5;
    }
}