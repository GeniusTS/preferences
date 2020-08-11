<?php

namespace GeniusTS\Preferences\Models;


use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

/**
 * Class Element
 *
 * @package GeniusTS\Preferences
 * @property string $name
 * @property string $namespace
 * @property string $rules
 * @property View   $view
 */
class Element
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $rules;

    /**
     * Element constructor.
     *
     * @param string       $name
     * @param array|string $rules
     */
    public function __construct($name, $rules = '')
    {
        $this->name = $name;
        $this->rules = new Collection(is_array($rules) ? $rules : [$name => $rules]);
    }

    /**
     * Set the name space of element
     *
     * @param string $namespace
     *
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $property
     *
     * @return null
     */
    public function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->{$property};
        }

        return null;
    }
}
