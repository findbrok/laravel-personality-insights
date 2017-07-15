<?php

namespace FindBrok\PersonalityInsights\Models;

class Warning
{
    /**
     * The identifier of the warning message, one of WORD_COUNT_MESSAGE, JSON_AS_TEXT, or PARTIAL_TEXT_USED.
     *
     * @var string
     */
    public $warning_id;

    /**
     * The message associated with the warning_id.
     * WORD_COUNT_MESSAGE: There were number words in the input. We need a minimum of 600, preferably 1,200 or more, to
     * compute statistically significant estimates.
     *
     * JSON_AS_TEXT: Request input was processed as text/plain as
     * indicated, however detected a JSON input. Did you mean application/json?
     *
     * PARTIAL_TEXT_USED: The text provided to
     * compute the profile was trimmed for performance reasons. This action does not affect the accuracy of the output,
     * as not all of the input text was required. This message applies only when Arabic input text exceeds a threshold
     * at which additional words do not contribute to the accuracy of the profile.
     *
     * @var string
     */
    public $message;
}
