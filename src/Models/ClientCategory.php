<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="ClientCategory",
 *      required={"client_id", "bot_id", "turn_id", "slug", "pattern", "template", "tag"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="bot_id",
 *          description="bot_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="turn_id",
 *          description="turn_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="pattern",
 *          description="pattern",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="template",
 *          description="template",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tag",
 *          description="tag",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
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
 *      )
 * )
 */
class ClientCategory extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'client_categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'client_id',
        'bot_id',
        'turn_id',
        'slug',
        'pattern',
        'template',
        'tag'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'client_id' => 'integer',
        'bot_id' => 'integer',
        'turn_id' => 'integer',
        'slug' => 'string',
        'pattern' => 'string',
        'template' => 'string',
        'tag' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required',
        'bot_id' => 'required',
        'turn_id' => 'required',
        'pattern' => 'required|string|max:255',
        'template' => 'required|string',
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
     * @return void
     */
    protected static function booted()
    {

        //You cant update some of the items
        static::updating(function ($model) {

            $originalAttributes = $model->getOriginal();
            $model->client_id = $originalAttributes['client_id'];
            $model->bot_id = $originalAttributes['bot_id'];
            $model->turn_id = $originalAttributes['turn_id'];
        });
    }

    /**
     * @return BelongsTo
     **/
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
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
    public function turn()
    {
        return $this->belongsTo(Turn::class, 'turn_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return ClientCategory::select([$this->table.'.*',
                'users.email',
                'bots.slug as bot',
                'conversations.slug as conversation',
                'clients.slug as client'])
                ->leftJoin('clients', 'clients.id', '=', $this->table.'.client_id')
                ->leftJoin('turns', 'turns.id', '=', $this->table.'.turn_id')
                ->leftJoin('conversations', 'conversations.id', '=', 'turns.conversation_id')
                ->leftJoin('bots', 'bots.id', '=', 'conversations.bot_id')
                ->leftJoin('users', 'users.id', '=', 'bots.user_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('pattern')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public static function findAndDeleteFromInput($input)
    {
        //created from an empty response so delete it
        $model = ClientCategory::select('client_categories.id')
            ->join('bots', 'bots.id', '=', 'client_categories.bot_id')
            ->where('client_categories.id', $input['client_category_id'])
            ->where('bots.user_id', Auth::user()->id)
            ->first();

        if ($model !== null) {
            ClientCategory::find($model->id)->delete();
            Flash::success('Client category deleted successfully.');
        }
    }
}
