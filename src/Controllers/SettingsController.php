<?php

namespace GeniusTS\Preferences\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Artisan;
use GeniusTS\Preferences\Models\Domain;
use GeniusTS\Preferences\Models\Element;
use GeniusTS\Preferences\Models\Setting;
use GeniusTS\Preferences\PreferencesManager;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\HttpFoundation\Response;
use GeniusTS\Preferences\Requests\SettingsRequest;
use GeniusTS\Preferences\Events\PreferencesUpdated;
use Illuminate\Foundation\Validation\ValidatesRequests;
use GeniusTS\Preferences\Events\BeforeUpdatePreference;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class SettingsController
 *
 * @package GeniusTS\Preferences
 */
abstract class SettingsController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Use transactions
     *
     * @var bool
     */
    protected $transactions = false;

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
        if ($this->transactions)
        {
            DB::transaction(function () use ($request) {
                $this->process($request);
            });
        }
        else
        {
            $this->process($request);
        }

        if (App::configurationIsCached())
        {
            Artisan::call('config:cache');
        }

        return $this->handleSuccessResponse();
    }

    /**
     * Saving settings process
     *
     * @param \GeniusTS\Preferences\Requests\SettingsRequest $request
     */
    protected function process(SettingsRequest $request)
    {
        Event::fire(new BeforeUpdatePreference);

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

        Event::fire(new PreferencesUpdated);
    }

    /**
     * @return Response
     */
    abstract protected function handleSuccessResponse();
}