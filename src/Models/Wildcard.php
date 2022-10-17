<?php

namespace LemurEngine\LemurBot\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use LemurEngine\LemurBot\Traits\HasPackageFactory;

/**
 * @SWG\Definition(
 *      definition="Wildcard",
 *      required={"conversation_id", "slug", "type"},
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
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="value",
 *          description="value",
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
class Wildcard extends Model
{
    use SoftDeletes;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'wildcards';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'conversation_id',
        'slug',
        'type',
        'value'
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
        'type' => 'string',
        'value' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'conversation_id' => 'required',
        'slug' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'value' => 'nullable|string|max:255',
        'deleted_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
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

        return Wildcard::select([$this->table.'.*',
            'bots.slug as bot', 'conversations.slug as conversation'])
            ->leftJoin('conversations', 'conversations.id', '=', 'wildcards.conversation_id')
            ->leftJoin('bots', 'bots.id', '=', 'conversations.bot_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['type','value'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
