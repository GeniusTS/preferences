<?php
/**
 * Created by PhpStorm.
 * User: aboudeh
 * Date: 12/08/16
 * Time: 23:37
 */

namespace GeniusTS\Preferences\Models;


use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Setting
 *
 * @property integer        $id
 * @property string         $slug
 * @property string         $domain
 * @property string         $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static Builder|Setting whereId($value)
 * @method static Builder|Setting whereSlug($value)
 * @method static Builder|Setting whereValue($value)
 * @method static Builder|Setting whereCreatedAt($value)
 * @method static Builder|Setting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'domain',
        'value',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Set the value attribute
     *
     * @param $value
     */
    public function setValueAttribute($value)
    {
        if (is_array($value))
        {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get the value property
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if (! isset($this->attributes['value']))
        {
            return null;
        }

        $value = json_decode($this->attributes['value'], true);

        if (json_last_error() === JSON_ERROR_NONE)
        {
            return $value;
        }

        return $this->attributes['value'];
    }

    /**
     * Find a setting model by slug and domain
     *
     * @param $slug
     * @param $domain
     *
     * @return \GeniusTS\Preferences\Models\Setting|null
     */
    public static function findBySlug($slug, $domain)
    {
        return self::where('slug', $slug)
            ->where('domain', $domain)
            ->first();
    }

    /**
     * Find a setting model by slug and domain
     * or return a new instance if not exit
     *
     * @param $slug
     * @param $domain
     *
     * @return \GeniusTS\Preferences\Models\Setting
     */
    public static function findBySlugOrNew($slug, $domain)
    {
        return self::firstOrNew([
            'slug'   => $slug,
            'domain' => $domain,
        ]);
    }
}
