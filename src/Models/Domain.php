<?php

namespace GeniusTS\Preferences\Models;


use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @var View
     */
    protected $view;

    /**
     * @var Collection
     */
    protected $rules;

    /**
     * Tab constructor.
     *
     * @param string      $key
     * @param View|string $view
     * @param null        $label
     */
    public function __construct($key, $view, $label = null)
    {
        $this->key = $key;
        $this->view = $view;
        $this->label = $label;
        $this->elements = new Collection;
        $this->rules = new Collection;
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
     * return a specific element
     *
     * @param string $name
     *
     * @return \GeniusTS\Preferences\Models\Element
     */
    public function getElement($name)
    {
        if (!$element = $this->checkElement($name)) {
            throw new NotFoundHttpException('Element does not exists in this Domain!');
        }

        return $element;
    }

    /**
     * Check if domain fas a specific element
     *
     * @param string $name
     *
     * @return \GeniusTS\Preferences\Models\Element|null
     */
    protected function checkElement($name)
    {
        return $this->elements->first(function ($element) use ($name) {
            return $element->name === $name;
        });
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
        $key = $element instanceof Element ? $element->name : $element;

        $this->elements->reject(function ($value) use ($key) {
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
        return Lang::get($this->label ?: $this->key);
    }

    /**
     * return the inputs rules
     *
     * @return Collection
     */
    public function getRules()
    {
        $this->elements->map(function (Element $element) {
            $element->rules->map(function ($value, $key) use ($element) {
                if (!preg_match("/^{$element->name}(\..*)?$/", $key)) {
                    $key = "{$element->name}.$key";
                }

                if (!preg_match("/^{$this->key}\./", $key)) {
                    $key = "{$this->key}.$key";
                }

                $this->rules->put($key, $value);
            });
        });

        return $this->rules;
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($property === 'view') {
            return $this->view && is_string($this->view) ? view($this->view) : $this->view;
        }

        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return null;
    }
}
