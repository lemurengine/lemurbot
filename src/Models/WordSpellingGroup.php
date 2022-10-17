<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="WordSpellingGroup",
 *      required={"language_id", "slug", "name", "description", "is_master"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="language_id",
 *          description="language_id",
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
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_master",
 *          description="is_master",
 *          type="boolean"
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
class WordSpellingGroup extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use CascadeSoftDeletes;
    use HasPackageFactory;


    public $table = 'word_spelling_groups';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $cascadeDeletes = ['botWordSpellingGroups','wordSpellings'];


    public $fillable = [
        'language_id',
        'user_id',
        'slug',
        'name',
        'description',
        'is_master'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'language_id' => 'integer',
        'user_id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string',
        'is_master' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'language_id' => 'required',
        'name' => 'required||unique:word_spelling_groups,name|string|max:255',
        'description' => 'required|string',

    ];

    /**
     * Add the user_id on create
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $loggedInUser = Auth::user();
            //set the user to the current logged in user
            $model->user_id = $loggedInUser->id;
            //if the user is not an admin overwrite is master with 0
            if(!LemurPriv::isAdmin($loggedInUser)){
                $model->is_master = 0;
            }
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     **/
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * @return HasMany
     **/
    public function botWordSpellingGroups()
    {
        return $this->hasMany(BotWordSpellingGroup::class, 'word_spelling_group_id');
    }

    /**
     * @return HasMany
     **/
    public function wordSpellings()
    {
        return $this->hasMany(WordSpelling::class, 'word_spelling_group_id');
    }


    /**
     * the query that is run in the datatable
     *
     * @return mixed
     */
    public function dataTableQuery()
    {

        return WordSpellingGroup::select([$this->table.'.*',
            'users.email',
            'languages.name as language'])
            ->leftJoin('languages', 'languages.id', '=', $this->table.'.language_id')
            ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
