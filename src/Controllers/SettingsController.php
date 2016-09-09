<?php

namespace GeniusTS\Preferences\Controllers;


use GeniusTS\Preferences\Models\Domain;
use GeniusTS\Preferences\Models\Element;
use GeniusTS\Preferences\Models\Setting;
use GeniusTS\Preferences\PreferencesManager;
use Symfony\Component\HttpFoundation\Response;
use GeniusTS\Preferences\Requests\SettingsRequest;

/**
 * Class SettingsController
 *
 * @package GeniusTS\Preferences
 */
abstract class SettingsController
{

    /**
     * @var PreferencesManager
     */
    protected $preferences;

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        $this->preferences = resolve('preferences');
    }

    /**
     * Handle update settings request
     *
     * @param \GeniusTS\Preferences\Requests\SettingsRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(SettingsRequest $request)
    {
        /** @var Domain $domain */
        foreach ($this->preferences->domains as $domain)
        {
            /** @var Element $element */
            foreach ($domain->elements as $element)
            {
                $model = Setting::findBySlugOrNew($element->name, $domain->key);
                $model->value = $request->get($element->name, null);
                $model->save();
            }
        }

        return $this->handleSuccessResponse();
    }

    /**
     * @return Response
     */
    abstract protected function handleSuccessResponse();
}