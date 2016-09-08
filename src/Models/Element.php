<?php

namespace GeniusTS\Preferences\Models;


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
     * @var string
     */
    protected $rules;

    /**
     * @var View
     */
    protected $view;

    /**
     * Element constructor.
     *
     * @param string $name
     * @param View   $view
     * @param null   $rules
     */
    public function __construct($name, View $view, $rules = null)
    {
        $this->name = $name;
        $this->view = $view;

        if (is_string($rules))
        {
            $this->rules = $rules;
        }
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