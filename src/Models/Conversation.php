<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Traits\ConversationHelper;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="Conversation",
 *      required={"slug", "bot_id", "client_id", "allow_html"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bot_id",
 *          description="bot_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="client_id",
 *          description="client_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="allow_html",
 *          description="allow_html",
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
class Conversation extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use ConversationHelper;
    use CascadeSoftDeletes;
    use HasPackageFactory;


    public $table = 'conversations';

    //local variables get and set in the var tags
    public $vars = [];
    //a way of storing non-response conversational features
    public $features = [];
    //a way of storing debug information
    public $debug = [];
    //a way of storing admin readable only information
    public $adminDebug = [];


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $cascadeDeletes = ['turns','conversationProperties','wildcards'];

    public $fillable = [
        'slug',
        'bot_id',
        'client_id',
        'allow_html'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'bot_id' => 'integer',
        'client_id' => 'integer',
        'allow_html' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'client_id' => 'required',
        'allow_html' => 'boolean',
    ];

    /**
     * @return void
     */
    protected static function booted()
    {

        //You cant update some of the items
        static::updating(function ($model) {

            $originalAttributes = $model->getOriginal();
            $model->client_id = $originalAttributes['client_id'];
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
     * @return BelongsTo
     **/
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * @return HasMany
     **/
    public function turns()
    {
        return $this->hasMany(Turn::class, 'conversation_id');
    }

    /**
     * @return HasMany
     **/
    public function conversationSources()
    {
        return $this->hasMany(ConversationSource::class, 'conversation_id');
    }

    /**
     * @return HasMany
     **/
    public function humanTurns()
    {
        return $this->hasMany(Turn::class, 'conversation_id')->where('source', 'human');
    }

    /**
     * @return HasMany
     **/
    public function wildcards()
    {
        return $this->hasMany(Wildcard::class, 'conversation_id');
    }

    /**
     * @return HasMany
     **/
    public function conversationHumanLogs()
    {
        return $this->hasMany(Turn::class, 'conversation_id')->human();
    }


    /**
     * @return HasMany
     **/
    public function openSraiTags()
    {

        return $this->HasMany(Turn::class, 'conversation_id')->openRecursiveTags();
    }

    /**
     **/
    public function countOpenSraiTags($parentTagId)
    {
        return $this->openSraiTags->where('parent_turn_id', $parentTagId)->count();
    }

    /**
     * @return HasMany
     **/
    public function botRatings()
    {
        return $this->hasMany(BotRating::class, 'conversation_id');
    }



    /**
     * @return HasMany
     **/
    public function conversationProperties()
    {
        return $this->hasMany(ConversationProperty::class, 'conversation_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return Conversation::select([$this->table.'.*','users.email','bots.slug as bot','clients.slug as client'])
                ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
                ->leftJoin('clients', 'clients.id', '=', $this->table.'.client_id')
                ->leftJoin('users', 'users.id', '=', 'bots.user_id');
    }

    public function isFirstTurn() :bool
    {
        return !$this->turns()->exists();
    }

    public static function totalInDays($days)
    {

        return Conversation::whereDate('created_at', '>', Carbon::now()->subDays($days))
            ->count();
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
}
