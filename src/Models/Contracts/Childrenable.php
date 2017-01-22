<?php

namespace FindBrok\PersonalityInsights\Models\Contracts;

interface Childrenable
{
    /**
     * Checks if Model has children.
     *
     * @return bool
     */
    public function hasChildren();

    /**
     * Gets Children nodes.
     *
     * @return mixed
     */
    public function getChildren();
}