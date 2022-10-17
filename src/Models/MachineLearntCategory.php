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
 *      definition="MachineLearntCategory",
 *      required={"client_id", "bot_id", "turn_id", "slug", "pattern", "template", "topic", "that","example_input", "example_output", "category_group_slug", "occurrences"},
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
 *          property=""topic",
 *          description="topic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="that",
 *          description="that",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="example_input",
 *          description="example_input",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="example_output",
 *          description="example_output",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="category_group_slug",
 *          description="category_group_slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="occurrences",
 *          description="occurrences",
 *          type="integer",
 *          format="int32"
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
class MachineLearntCategory extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'machine_learnt_categories';

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
        'topic',
        'that',
        'example_input',
        'example_output',
        'category_group_slug',
        'occurrences'
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
        'topic' => 'string',
        'that' => 'string',
        'example_input' => 'string',
        'example_output' => 'string',
        'category_group_slug' => 'string',
        'occurrences' => 'integer',
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
        'topic' => 'string|max:255',
        'that' => 'string',
        'example_input' => 'required|string|max:255',
        'example_output' => 'required|string',
        'category_group_slug' => 'string',
        'occurrences' => 'integer',
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

            return MachineLearntCategory::select([$this->table.'.*',
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
        MachineLearntCategory::find($input['machine_learnt_category_id'])->delete();
        Flash::success('Machine learnt category deleted successfully.');
    }
}
