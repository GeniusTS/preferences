<?php

namespace GeniusTS\Preferences;


use Illuminate\Support\Collection;
use GeniusTS\Exceptions\DomainNotExist;
use GeniusTS\Preferences\Models\Domain;

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
        $this->rules = new Collection();
        $this->domains = new Collection();
    }

    /**
     * Add a new settings domain
     *
     * @param \GeniusTS\Preferences\Models\Domain $domain
     *
     * @return $this
     */
    public function addDomain(Domain $domain)
    {
        $this->checkNameSpace($domain->key);
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

        $this->domains->reject(function ($value) use ($key)
        {
            return $value->key === $key;
        });

        return $this;
    }

    /**
     * return a specific domain
     *
     * @param string $key
     *
     * @return Domain
     */
    public function getDomain($key)
    {
        $this->checkNameSpace($key);

        return $this->domains->first(function ($domain) use ($key)
        {
            return $domain->key === $key;
        });
    }

    /**
     * @param $key
     *
     * @return bool
     * @throws \GeniusTS\Exceptions\DomainNotExist
     */
    protected function checkNameSpace($key)
    {
        if (!$this->domains->where('key', $key)->count())
        {
            throw new DomainNotExist();
        }

        return true;
    }
}