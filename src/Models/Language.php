<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="Language",
 *      required={"name", "description"},
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
class Language extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'languages';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'slug',
        'name',
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

        'name' => 'required|unique:languages,name|string|max:10',
        'description' => 'required|string|max:50',
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
     * @return HasMany
     **/
    public function bots()
    {
        return $this->hasMany(Bot::class, 'language_id');
    }

    /**
     * @return HasMany
     **/
    public function categories()
    {
        return $this->hasMany(Category::class, 'language_id');
    }

    /**
     * @return HasMany
     **/
    public function categoryGroups()
    {
        return $this->hasMany(CategoryGroup::class, 'language_id');
    }

    /**
     * @return HasMany
     **/
    public function normalizations()
    {
        return $this->hasMany(Normalization::class, 'language_id');
    }

    /**
     * @return HasMany
     **/
    public function wordSpellingGroups()
    {
        return $this->hasMany(WordSpellingGroup::class, 'language_id');
    }

    /**
     * @return HasMany
     **/
    public function wordTransformations()
    {
        return $this->hasMany(WordTransformation::class, 'language_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return Language::select([$this->table.'.*']);
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
