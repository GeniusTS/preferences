<?php

namespace GeniusTS\Preferences\Controllers;


use Illuminate\Http\Request;
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
     * @param \Illuminate\Http\Request
     * @param \GeniusTS\Preferences\Models\Domain|null  $domain
     * @param \GeniusTS\Preferences\Models\Element|null $element
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $domain = null, $element = null)
    {
        $this->validate($request, $this->rules($request));

        if ($this->transactions)
        {
            DB::transaction(function () use ($request, $domain, $element) {
                $this->process($request, $domain, $element);
            });
        }
        else
        {
            $this->process($request, $domain, $element);
        }

        if (App::configurationIsCached())
        {
            Artisan::call('config:cache');
        }

        return $this->handleSuccessResponse($domain, $element);
    }

    /**
     * Saving settings process
     *
     * @param \Illuminate\Http\Request                  $request
     * @param \GeniusTS\Preferences\Models\Domain|null  $domain
     * @param \GeniusTS\Preferences\Models\Element|null $element
     */
    protected function process(Request $request, Domain $domain = null, Element $element = null)
    {
        Event::fire(new BeforeUpdatePreference);

        if ($element)
        {
            $this->saveElement($domain, $element, $request);
        }
        elseif ($domain)
        {
            $this->processDomainElements($domain, $request);
        }
        else
        {
            foreach ($this->preferences->domains as $namespace)
            {
                $this->processDomainElements($namespace, $request);
            }
        }

        Event::fire(new PreferencesUpdated);
    }

    /**
     * Saving domain elements to database
     *
     * @param \GeniusTS\Preferences\Models\Domain $domain
     * @param \Illuminate\Http\Request            $request
     */
    protected function processDomainElements(Domain $domain, Request $request)
    {
        /** @var Element $element */
        foreach ($domain->elements as $element)
        {
            $this->saveElement($domain, $element, $request);
        }
    }

    /**
     * Save element to database
     *
     * @param \GeniusTS\Preferences\Models\Domain  $domain
     * @param \GeniusTS\Preferences\Models\Element $element
     * @param \Illuminate\Http\Request             $request
     */
    protected function saveElement(Domain $domain, Element $element, Request $request)
    {
        $model = Setting::findBySlugOrNew($element->name, $domain->key);
        $model->value = $this->getElementValue($domain, $element, $request);
        $model->save();
    }

    /**
     * Get element value from request
     *
     * @param \GeniusTS\Preferences\Models\Domain  $domain
     * @param \GeniusTS\Preferences\Models\Element $element
     * @param \Illuminate\Http\Request             $request
     *
     * @return mixed
     */
    protected function getElementValue(Domain $domain, Element $element, Request $request)
    {
        return $request->input("{$domain->key}.{$element->name}", null);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function rules(Request $request)
    {
        /** @var \GeniusTS\Preferences\Models\Domain $domain */
        $domain = $request->route()->parameter('preferences_domain');

        if ($domain)
        {
            return $this->singleDomainRules($domain);
        }

        return $this->preferencesRules();
    }

    /**
     * get rule for a specific domain
     *
     * @param \GeniusTS\Preferences\Models\Domain $domain
     *
     * @return array
     */
    protected function singleDomainRules($domain)
    {
        return $domain->getRules()->toArray();
    }

    /**
     * get validation rules for preferences domains
     */
    protected function preferencesRules()
    {
        $rules = [];

        $this->preferences
            ->domains
            ->map(function (Domain $domain) use (&$rules) {
                $rules = array_merge($rules, $domain->getRules()->toArray());
            });

        return $rules;
    }

    /**
     * @param \GeniusTS\Preferences\Models\Domain|null  $domain
     * @param \GeniusTS\Preferences\Models\Element|null $element
     *
     * @return Response
     */
    abstract protected function handleSuccessResponse($domain, $element);
}
