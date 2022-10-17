<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="BotAllowedSite",
 *      required={"bot_id", "website_url"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="bot_id",
 *          description="bot_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="website_url",
 *          description="website_url",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class BotAllowedSite extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;

    public $table = 'bot_allowed_sites';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $fillable = [
        'bot_id',
        'user_id',
        'slug',
        'website_url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'user_id' => 'integer',
        'slug' => 'string',
        'website_url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'website_url' => 'required|string',
    ];

    /**
     * Add the user_id on create
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });
    }

    /**
     * Get the validation rules
     *
     * return array
     */
    public function getRules()
    {
        return self::$rules;
    }


    /**
     * @return BelongsTo
     **/
    public function bot()
    {
        return $this->belongsTo(Bot::class, 'bot_id');
    }

    /**
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {
        return BotAllowedSite::select([$this->table.'.*','users.email','bots.slug as bot'])
            ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
            ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('website_url')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }


}
