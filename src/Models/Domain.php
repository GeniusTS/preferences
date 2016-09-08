<?php

namespace GeniusTS\Preferences\Models;


use Illuminate\Support\Collection;

/**
 * Class Domain
 *
 * @package GeniusTS\Preferences
 * @property string     $key
 * @property string     $label
 * @property Collection $elements
 */
class Domain
{

    /**
     * The key of the tab
     *
     * @var string
     */
    protected $key;

    /**
     * label of the tab
     *
     * @var string
     */
    protected $label;

    /**
     * the inputs inside the tab
     *
     * @var Collection
     */
    protected $elements;

    /**
     * Tab constructor.
     *
     * @param      $key
     * @param null $label
     */
    public function __construct($key, $label = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->elements = new Collection();
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Add input element to the tab
     *
     * @param Element $element
     *
     * @return $this
     */
    public function addElement(Element $element)
    {
        $element->setNamespace($this->key);
        $this->elements->push($element);

        return $this;
    }

    /**
     * remove input element
     *
     * @param string|Element $element
     *
     * @return $this
     */
    public function removeElement($element)
    {
        if ($element instanceof Element)
        {
            $key = $element->name;
        }
        else
        {
            $key = $element;
        }

        $this->elements->reject(function ($value) use ($key)
        {
            return $value->name === $key;
        });

        return $this;
    }

    /**
     * return the displayed label
     *
     * @return string
     */
    public function getDisplayedName()
    {
        return $this->label ?: $this->key;
    }

    /**
     * return the inputs rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->elements->map(function (Element $element)
        {
            return [
                "{$this->key}.{$element->name}" => 'rules',
            ];
        })->toArray();
    }

    /**
     * @param string $property
     *
     * @return mixed
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