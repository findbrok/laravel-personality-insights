<?php

namespace FindBrok\PersonalityInsights\Models;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

class Profile extends BaseModel implements Jsonable
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
     * A recursive Collection of TraitTreeNode objects that provides detailed results for the Big Five personality
     * characteristics (dimensions and facets) inferred from the input text.
     *
     * @var Collection
     */
    protected $personality;

    /**
     * A Collection of TraitTreeNode objects that provides detailed results for the Needs characteristics inferred from
     * the input text.
     *
     * @var Collection
     */
    protected $needs;

    /**
     * A Collection of TraitTreeNode objects that provides detailed results for the Needs characteristics inferred from
     * the input text.
     *
     * @var Collection
     */
    protected $values;

    /**
     * For JSON content that is timestamped, a Collection of BehaviorNode objects that provides detailed results about
     * the social behavior disclosed by the input in terms of temporal characteristics. The results include information
     * about the distribution of the content over the days of the week and the hours of the day.
     *
     * @var Collection
     */
    protected $behavior;

    /**
     * If the consumption_preferences query parameter is true, an array of ConsumptionPreferencesCategoryNode objects
     * that provides detailed results for each category of consumption preferences. Each element of the array provides
     * information for the individual preferences of that category.
     *
     * @var Collection
     */
    protected $consumption_preferences;

    /**
     * A Collection of Warning objects that provides messages associated with the input text submitted with the request.
     * The array is empty if the input generated no warnings.
     *
     * @var Collection
     */
    protected $warnings;

    /**
     * When guidance is appropriate, a message that indicates the number of words found and where that value falls in
     * the range of required or suggested number of words.
     *
     * @var string
     */
    public $word_count_message;

    /**
     * Sets Personality.
     *
     * @param TraitTreeNode[] $personality
     *
     * @return $this
     */
    public function setPersonality($personality)
    {
        $this->personality = collect($personality);

        return $this;
    }

    /**
     * Sets Needs.
     *
     * @param TraitTreeNode[] $needs
     *
     * @return $this
     */
    public function setNeeds($needs)
    {
        $this->needs = collect($needs);

        return $this;
    }

    /**
     * Sets Values.
     *
     * @param TraitTreeNode[] $values
     *
     * @return $this
     */
    public function setValues($values)
    {
        $this->values = collect($values);

        return $this;
    }

    /**
     * Sets Behavior.
     *
     * @param BehaviorNode[] $behavior
     *
     * @return $this
     */
    public function setBehavior($behavior)
    {
        $this->behavior = collect($behavior);

        return $this;
    }

    /**
     * Sets consumption_preferences.
     *
     * @param ConsumptionPreferencesCategoryNode[] $consumption_preferences
     *
     * @return $this
     */
    public function setConsumptionPreferences($consumption_preferences)
    {
        $this->consumption_preferences = collect($consumption_preferences);

        return $this;
    }

    /**
     * Sets warnings.
     *
     * @param Warning[] $warnings
     *
     * @return $this
     */
    public function setWarnings($warnings)
    {
        $this->warnings = collect($warnings);

        return $this;
    }

    /**
     * Convert Profile to json.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**get_object_vars ( object $object )
     * Get a Facet using Id.
     *
     * @param string $id
     *
     * @return TraitTreeNode|null
     */
    public function findFacetById($id)
    {
        // No personality defined yet.
        if (is_null($this->personality)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('trait_id', $id, $this->personality);
    }

    /**
     * Get a Facet using Name.
     *
     * @param string $name
     *
     * @return TraitTreeNode|null
     */
    public function findFacetByName($name)
    {
        // No personality defined yet.
        if (is_null($this->personality)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('name', $name, $this->personality);
    }

    /**
     * Checks if the Profile has the Facet given.
     *
     * @param string $facet
     *
     * @return bool
     */
    public function hasFacet($facet)
    {
        return
            ! is_null($this->findFacetByName($facet)) ||
            ! is_null($this->findFacetById($facet));
    }

    /**
     * Get a Need using Id.
     *
     * @param string $id
     *
     * @return TraitTreeNode|null
     */
    public function findNeedById($id)
    {
        // No needs defined yet.
        if (is_null($this->needs)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('trait_id', $id, $this->needs);
    }

    /**
     * Get a Need using its name.
     *
     * @param string $name
     *
     * @return TraitTreeNode|null
     */
    public function findNeedByName($name)
    {
        // No needs defined yet.
        if (is_null($this->needs)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('name', $name, $this->needs);
    }

    /**
     * Checks if Profile has given need.
     *
     * @param string $need
     *
     * @return bool
     */
    public function hasNeed($need)
    {
        return
            ! is_null($this->findNeedByName($need)) ||
            ! is_null($this->findNeedById($need));
    }

    /**
     * Get a Value using Id.
     *
     * @param string $id
     *
     * @return TraitTreeNode|null
     */
    public function findValueById($id)
    {
        // No values defined yet.
        if (is_null($this->values)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('trait_id', $id, $this->values);
    }

    /**
     * Get a Value using its name.
     *
     * @param string $name
     *
     * @return TraitTreeNode|null
     */
    public function findValueByName($name)
    {
        // No values defined yet.
        if (is_null($this->values)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('name', $name, $this->values);
    }

    /**
     * Checks if Profile has given value.
     *
     * @param string $value
     *
     * @return bool
     */
    public function hasValue($value)
    {
        return
            ! is_null($this->findValueByName($value)) ||
            ! is_null($this->findValueById($value));
    }

    /**
     * Find Behaviors a give times or Days.
     *
     * @param array|string $times
     *
     * @return Collection|BehaviorNode|null
     */
    public function findBehaviorsFor($times)
    {
        // No behaviors set yet.
        if (is_null($this->behavior)) {
            return;
        }

        // Specifically convert times to array.
        $times = ! is_array($times) ? [$times] : $times;

        // Return Behavior at specific times
        $behaviors = $this->behavior->reject(
            function ($behavior) use ($times) {
                return ! in_array($behavior->name, $times);
            });

        // Only one item.
        if ($behaviors->count() == 1) {
            return $behaviors->first();
        }

        return $behaviors;
    }

    /**
     * Get a ConsumptionPreferencesCategory using Id.
     *
     * @param string $preference
     *
     * @return ConsumptionPreferencesCategoryNode|null
     */
    public function findConsumptionPreferenceCategoryById($preference)
    {
        // No Preferences defined yet.
        if (is_null($this->consumption_preferences)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('consumption_preference_category_id', $preference, $this->consumption_preferences);
    }

    /**
     * Get a ConsumptionPreferencesCategory using its name.
     *
     * @param string $preference
     *
     * @return ConsumptionPreferencesCategoryNode|null
     */
    public function findConsumptionPreferenceCategoryName($preference)
    {
        // No Preferences defined yet.
        if (is_null($this->consumption_preferences)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('name', $preference, $this->consumption_preferences);
    }

    /**
     * Checks if ConsumptionPreferencesCategory exists on profile.
     *
     * @param string $preference
     *
     * @return bool
     */
    public function hasConsumptionPreferenceCategory($preference)
    {
        return
            ! is_null($this->findConsumptionPreferenceCategoryName($preference)) ||
            ! is_null($this->findConsumptionPreferenceCategoryById($preference));
    }

    /**
     * Find ConsumptionPreferencesNode by Id.
     *
     * @param string $preferenceId
     *
     * @return ConsumptionPreferencesNode|null
     */
    public function findConsumptionPreference($preferenceId)
    {
        // No Preferences defined yet.
        if (is_null($this->consumption_preferences)) {
            return;
        }

        // Traverse and Return.
        return $this->traverseNodesAndFindBy('consumption_preference_id', $preferenceId, $this->consumption_preferences);
    }

    /**
     * Checks if Consumption Preference exists.
     *
     * @param string $preferenceId
     *
     * @return bool
     */
    public function hasConsumptionPreference($preferenceId)
    {
        return ! is_null($this->findConsumptionPreference($preferenceId));
    }
}
