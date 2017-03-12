<?php

namespace FindBrok\PersonalityInsights\Models;

use JsonSerializable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseModel implements JsonSerializable
{
    use Macroable;

    /**
     * Get a property.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        // Return Property if its there.
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        // Nothing to return.
        return null;
    }

    /**
     * Traverse Collection of Nodes and return specific Node if
     * criteria matches.
     *
     * @param string     $propName
     * @param string     $propValue
     * @param Collection $nodes
     *
     * @return TraitTreeNode|ConsumptionPreferencesCategoryNode|ConsumptionPreferencesNode|null
     */
    public function traverseNodesAndFindBy($propName, $propValue, Collection $nodes = null)
    {
        // Nodes are null so nothing to Traverse.
        if (is_null($nodes)) {
            return null;
        }

        foreach ($nodes as $node) {
            if ($node->{$propName} == $propValue) {
                return $node;
            }

            if ($node->hasChildren()) {
                $node = $this->traverseNodesAndFindBy($propName, $propValue, $node->getChildren());

                if (! is_null($node) && $node instanceof BaseModel) {
                    return $node;
                }
            }
        }

        // Nothing Found.
        return null;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            } else {
                return $value;
            }
        }, get_object_vars($this));
    }
}
