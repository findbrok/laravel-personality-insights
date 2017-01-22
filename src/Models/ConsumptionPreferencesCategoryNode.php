<?php

namespace FindBrok\PersonalityInsights\Models;

use Illuminate\Support\Collection;
use FindBrok\PersonalityInsights\Models\Contracts\Childrenable;

class ConsumptionPreferencesCategoryNode extends BaseModel implements Childrenable
{
    /**
     * The unique identifier of the consumption preferences category to which the results pertain. IDs have the form
     * consumption_preferences_category.
     *
     * @var string
     */
    public $consumption_preference_category_id;

    /**
     * The user-visible name of the consumption preferences category.
     *
     * @var string
     */
    public $name;

    /**
     * A Collection of ConsumptionPreferencesNode objects that provides detailed results inferred from the input text
     * for the individual preferences of the category.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $consumption_preferences;

    /**
     * Set Consumption Preferences.
     *
     * @param ConsumptionPreferencesNode[] $consumption_preferences
     *
     * @return $this
     */
    public function setConsumptionPreferences($consumption_preferences)
    {
        $this->consumption_preferences = collect($consumption_preferences);

        return $this;
    }

    /**
     * Checks if ConsumptionPreferencesCategoryNode has Children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return
            $this->consumption_preferences instanceof Collection &&
            ! $this->consumption_preferences->isEmpty();
    }

    /**
     * Gets Children nodes.
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->consumption_preferences;
    }
}
