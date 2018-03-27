<?php

namespace GeniusTS\Preferences;


use Illuminate\Support\Collection;
use GeniusTS\Preferences\Models\Domain;
use GeniusTS\Preferences\Exceptions\DomainNotExist;
use GeniusTS\Preferences\Exceptions\DomainAlreadyExist;

class PreferencesManager
{

    /**
     * @var Collection
     */
    protected $domains;

    /**
     * PreferencesManager constructor.
     */
    public function __construct()
    {
        $this->domains = new Collection;
    }

    /**
     * Add a new settings domain
     *
     * @param \GeniusTS\Preferences\Models\Domain $domain
     *
     * @return $this
     * @throws \GeniusTS\Preferences\Exceptions\DomainAlreadyExist
     */
    public function addDomain(Domain $domain)
    {
        if ($this->checkNameSpace($domain->key))
        {
            throw new DomainAlreadyExist();
        }

        $this->domains->push($domain);

        return $this;
    }

    /**
     * remove domain
     *
     * @param \GeniusTS\Preferences\Models\Domain|string $domain
     *
     * @return $this
     */
    public function removeDomain($domain)
    {
        if ($domain instanceof $domain)
        {
            $key = $domain->key;
        }
        else
        {
            $key = $domain;
        }

        $this->domains->reject(function ($value) use ($key) {
            return $value->key === $key;
        });

        return $this;
    }

    /**
     * return a specific domain
     *
     * @param string $key
     *
     * @return \GeniusTS\Preferences\Models\Domain
     * @throws \GeniusTS\Preferences\Exceptions\DomainNotExist
     */
    public function getDomain($key)
    {
        if (! $this->checkNameSpace($key))
        {
            throw new DomainNotExist();
        }

        return $this->domains->first(function ($domain) use ($key) {
            return $domain->key === $key;
        });
    }

    /**
     * @param $key
     *
     * @return bool
     */
    protected function checkNameSpace($key)
    {
        if (! $this->domains->where('key', $key)->count())
        {
            return false;
        }

        return true;
    }

    /**
     * @param $property
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