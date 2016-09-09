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
     * @return mixed
     */
    public function edit()
    {
        return view('preferences.settings');
    }

    /**
     * Handle the success saving settings response
     *
     * @return Response
     */
    protected function handleSuccessResponse()
    {
        return redirect($this->redirectTo);
    }
}