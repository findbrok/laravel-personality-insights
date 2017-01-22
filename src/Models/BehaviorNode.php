<?php

namespace FindBrok\PersonalityInsights\Models;

class BehaviorNode extends BaseModel
{
    /**
     * The unique identifier of the characteristic to which the results pertain. IDs have the form behavior_value.
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
     * The category of the characteristic: behavior for temporal data.
     *
     * @var string
     */
    public $category;

    /**
     * For JSON content that is timestamped, the percentage of timestamped input data that occurred during that day of
     * the week or hour of the day. The range is 0 to 1.
     *
     * @var float
     */
    public $percentage;
}