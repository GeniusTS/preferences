<?php

namespace App\Http\Controllers;


use Symfony\Component\HttpFoundation\Response;
use GeniusTS\Preferences\Controllers\SettingsController as PreferencesController;

/**
 * Class SettingsController
 *
 * @package GeniusTS\Preferences
 */
class SettingsController extends PreferencesController
{

    /**
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Display the edit form
     *
     * @param \GeniusTS\Preferences\Models\Domain|null $domain
     *
     * @return mixed
     */
    public function edit($domain = null)
    {
        return view('preferences.settings', compact('domain'));
    }

    /**
     * Handle the success saving settings response
     *
     * @param \GeniusTS\Preferences\Models\Domain|null  $domain
     * @param \GeniusTS\Preferences\Models\Element|null $element
     *
     * @return Response
     */
    protected function handleSuccessResponse($domain, $element)
    {
        return redirect($this->redirectTo);
    }
}
