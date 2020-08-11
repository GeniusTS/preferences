<?php

namespace GeniusTS\Preferences;


use Illuminate\Support\Collection;
use GeniusTS\Preferences\Models\Domain;
use GeniusTS\Preferences\Exceptions\DomainAlreadyExist;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if ($this->checkNamespace($domain->key))
        {
            throw new DomainAlreadyExist;
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
        $key = $domain instanceof Domain ? $domain->key : $domain;

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
     */
    public function getDomain($key)
    {
        if (! $domain = $this->checkNamespace($key))
        {
            throw new NotFoundHttpException('Preferences Domain not found!');
        }

        return $domain;
    }

    /**
     * @param $key
     *
     * @return \GeniusTS\Preferences\Models\Domain|null
     */
    protected function checkNamespace($key)
    {
        return $this->domains->first(function ($domain) use ($key) {
            return $domain->key === $key;
        });
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
