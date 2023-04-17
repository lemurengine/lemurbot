<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="Client",
 *      required={"bot_id", "slug", "is_banned"},
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
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_banned",
 *          description="is_banned",
 *          type="boolean"
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
class Client extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use CascadeSoftDeletes;
    use HasPackageFactory;

    public $table = 'clients';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['conversations', 'clientCategories'];

    public $fillable = [
        'bot_id',
        'slug',
        'is_banned',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'slug' => 'string',
        'is_banned' => 'boolean',
        'image' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'is_banned' => 'required|boolean',
        'image' => 'nullable|url',
    ];

    /**
     * @return void
     */
    protected static function booted()
    {

        //You cant update some of the items
        static::updating(function ($model) {

            $originalAttributes = $model->getOriginal();
            $model->bot_id = $originalAttributes['bot_id'];
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
     * @return HasMany
     **/
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'client_id');
    }

    /**
     * @return HasMany
     **/
    public function clientCategories()
    {
        return $this->hasMany(ClientCategory::class, 'client_id');
    }
    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return Client::select([$this->table.'.*',
                    'users.email',
                    'bots.slug as bot',
                    'clients.slug as client'])
                ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
                ->leftJoin('users', 'users.id', '=', 'bots.user_id');
    }

    /**
     * @param $slug
     * @param $bot
     * @return Client
     */
    public static function getClientBySlugOrCreate($bot, $slug = '')
    {

        //if the request does not include an id then we will create one and return it...
        if ($slug=='') {
            $slug = Str::uuid()->toString();
        }

        $client = Client::where('slug', $slug)->first();
        $bot = Bot::where('slug', $bot->id)->first();

        if ($client == null) {
            $client = new Client();
            $client->bot_id=$bot->id;
            $client->user_id=$bot->user_id;
            $client->slug=$slug;
            $client->save();
        }

        return $client;
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Str::uuid()->toString();
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }


    public static function getImageUrlAttribute()
    {

        return asset('widgets/images/missing.png');
    }
}
