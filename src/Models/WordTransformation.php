<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="WordTransformation",
 *      required={"language_id", "slug", "first_person_form", "second_person_form", "third_person_form",
 *                  "third_person_form_female", "third_person_form_male", "is_master"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="language_id",
 *          description="language_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_person_form",
 *          description="first_person_form",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="second_person_form",
 *          description="second_person_form",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="third_person_form",
 *          description="third_person_form",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="third_person_form_female",
 *          description="third_person_form_female",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="third_person_form_male",
 *          description="third_person_form_male",
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
class WordTransformation extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'word_transformations';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'language_id',
        'slug',
        'first_person_form',
        'second_person_form',
        'third_person_form',
        'third_person_form_female',
        'third_person_form_male',
        'is_master'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'language_id' => 'integer',
        'slug' => 'string',
        'first_person_form' => 'string',
        'second_person_form' => 'string',
        'third_person_form' => 'string',
        'third_person_form_female' => 'string',
        'third_person_form_male' => 'string',
        'is_master' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'language_id' => 'required|integer',
        'first_person_form' => 'required|string|max:50',
        'second_person_form' => 'required|string|max:50',
        'third_person_form' => 'required|string|max:50',
        'third_person_form_female' => 'nullable|string|max:50',
        'third_person_form_male' => 'nullable|string|max:50',
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
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return WordTransformation::select([$this->table.'.*','users.email','languages.name as language'])
                ->leftJoin('languages', 'languages.id', '=', $this->table.'.language_id')
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_person_form','second_person_form'])
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }
}
