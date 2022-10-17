<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="BotRating",
 *      required={"slug", "bot_id", "rating"},
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
 *          property="conversation_id",
 *          description="conversation_id",
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
 *          property="rating",
 *          description="rating",
 *          type="number",
 *          format="number"
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
class BotRating extends Model
{
    use SoftDeletes;
    use HasSlug;
    use UiValidationTrait;
    use HasPackageFactory;


    public $table = 'bot_ratings';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'slug',
        'conversation_id',
        'bot_id',
        'rating'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'conversation_id' => 'integer',
        'bot_id' => 'integer',
        'rating' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'slug' => 'required|string|max:255',
        'conversation_id' => 'nullable',
        'bot_id' => 'required',
        'rating' => 'required|numeric',
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
    public function bot()
    {
        return $this->belongsTo(Bot::class, 'bot_id');
    }

    /**
     * @return BelongsTo
     **/
    public function conversation()
    {
        return $this->belongsTo(User::class, 'conversation_id');
    }


    /**
     * the query that is run in the datatable
     *
     * @return mixed
     */
    public function dataTableQuery()
    {

        return BotRating::select([$this->table.'.*',
            'users.email',
            'bots.slug as bot',
            'conversations.slug as conversation'])
            ->leftJoin('conversations', 'conversations.id', '=', $this->table.'.conversation_id')
            ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
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
}
