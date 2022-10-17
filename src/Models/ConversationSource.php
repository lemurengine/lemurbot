<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="ConversationSource",
 *      required={"conversation_id", "slug", "params", "user", "ip", "user_agent", "referer"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="conversation_id",
 *          description="conversation_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="params",
 *          description="params",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user",
 *          description="user",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ip",
 *          description="ip",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="params",
 *          description="params",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_agent",
 *          description="user_agent",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="referer",
 *          description="referer",
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
class ConversationSource extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasPackageFactory;

    public $table = 'conversation_sources';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'conversation_id',
        'slug',
        'params',
        'user',
        'ip',
        'user_agent',
        'referer'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'conversation_id' => 'integer',
        'slug' => 'string',
        'params' => 'string',
        'user' => 'string',
        'ip' => 'string',
        'user_agent' => 'string',
        'referer' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'conversation_id' => 'required',
        'slug' => 'string',
        'params' => 'string',
        'user' => 'string',
        'ip' => 'string',
        'user_agent' => 'string',
        'referer' => 'string',
    ];

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
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {
            return ConversationSource::select([$this->table.'.*',
                'users.email',
                'bots.slug as bot',
                'conversations.slug as conversation',
                'clients.slug as client'])
                ->join('conversations', 'conversations.id', '=', $this->table.'.conversation_id')
                ->leftJoin('bots', 'bots.id', '=', 'conversations.bot_id')
                ->leftJoin('clients', 'clients.id', '=', 'conversations.client_id')
                ->leftJoin('users', 'users.id', '=', 'bots.user_id');
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return mt_rand();
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }



    public static function createConversationSource($conversationId)
    {
        $source = new ConversationSource();
        $source->conversation_id=$conversationId;
        $source->params = json_encode(request()->all());
        $source->user = request()->user()->email ?? '';
        $source->ip = request()->ip();
        $source->user_agent = request()->userAgent();
        $source->referer =  request()->headers->get('referer');

        $source->save();
    }


}
