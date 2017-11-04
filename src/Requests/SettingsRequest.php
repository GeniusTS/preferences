<?php

namespace GeniusTS\Preferences\Requests;


use GeniusTS\Preferences\Models\Domain;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SettingsRequest
 *
 * @package GeniusTS\Preferences
 */
class SettingsRequest extends FormRequest
{

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $preferences = app('preferences');

        $preferences->domains
            ->map(function (Domain $domain)
            {
                $this->rules = array_merge($this->rules, $domain->getRules()->toArray());
            });

        return $this->rules;
    }
}
