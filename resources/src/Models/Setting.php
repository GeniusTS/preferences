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
        'value',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @param $slug
     *
     * @return Setting|null
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * @param $slug
     *
     * @return Setting|null
     */
    public static function findBySlugOrNew($slug)
    {
        return self::where('slug', $slug)->firstOrNew(['slug' => $slug]);
    }
}
