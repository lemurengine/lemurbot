<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="Category",
 *      required={"category_group_id", "slug", "pattern", "regexp_pattern", "first_letter_pattern", "topic",
 *          "regexp_topic", "first_letter_topic", "that", "regexp_that", "first_letter_that", "template", "status"},
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
 *          property="category_group_id",
 *          description="category_group_id",
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
 *          property="regexp_pattern",
 *          description="regexp_pattern",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_letter_pattern",
 *          description="first_letter_pattern",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="topic",
 *          description="topic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="regexp_topic",
 *          description="regexp_topic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_letter_topic",
 *          description="first_letter_topic",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="that",
 *          description="that",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="regexp_that",
 *          description="regexp_that",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_letter_that",
 *          description="first_letter_that",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="template",
 *          description="template",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
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
class Category extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'categories';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'category_group_id',
        'slug',
        'pattern',
        'regexp_pattern',
        'first_letter_pattern',
        'topic',
        'regexp_topic',
        'first_letter_topic',
        'that',
        'regexp_that',
        'first_letter_that',
        'template',
        'status',
        'category_group_slug'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'category_group_id' => 'integer',
        'slug' => 'string',
        'pattern' => 'string',
        'regexp_pattern' => 'string',
        'first_letter_pattern' => 'string',
        'topic' => 'string',
        'regexp_topic' => 'string',
        'first_letter_topic' => 'string',
        'that' => 'string',
        'regexp_that' => 'string',
        'first_letter_that' => 'string',
        'template' => 'string',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category_group_id' => 'required',
        'pattern' => 'required|string|max:255',
        'topic' => 'nullable|string|max:255',
        'that' => 'nullable|string|max:255',
        'template' => 'required|string',
        'status' => 'required|string',
    ];

    /**
     * Add the user_id on create
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {

            $model->user_id = Auth::id();
            $model = self::transformCategory($model);
        });

        static::updating(function ($model) {
            $model = self::transformCategory($model);
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
    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }

    /**
     * @return HasMany
     **/
    public function turns()
    {
        return $this->hasMany(Turn::class, 'category_id');
    }


    /**
     * the query that is run in the datatable
     *
     * @return mixed
     */
    public function dataTableQuery()
    {

        $thisUserId = Auth::user()->id;

        $query = Category::select([$this->table.'.*', 'cg.name as filename',
            'users.email'])
            ->leftJoin('users', 'users.id', '=', $this->table.'.user_id')
            ->leftJoin('category_groups as cg', 'cg.id', '=', 'categories.category_group_id');

        //this is not an admin only show their own and master data
        if (!LemurPriv::isAdmin(Auth::user())) {
            $query = $query->where('cg.is_master', 1)
                ->orWhere('cg.user_id', $thisUserId);
        }

        return $query;

    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('pattern')
            ->generateSlugsFrom(function($model) {
                $clean=LemurStr::replaceForSlug($model->pattern);
                return "{$clean}";
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public static function transformCategory($model)
    {

        if (isset($model->category_group_slug)) {

            $categoryGroup = CategoryGroup::where('slug', $model->category_group_slug)->first();
            if ($categoryGroup === null) {
                $categoryGroup = new CategoryGroup();
                $categoryGroup->user_id = Auth::user()->id;
                $categoryGroup->language_id = 1;
                $categoryGroup->name = $model->category_group_slug;
                $categoryGroup->status = $model->status;
                $categoryGroup->description = 'Created automatically when adding a new category';
                $categoryGroup->is_master = false;
                $categoryGroup->save();
            }
            $model->category_group_id = $categoryGroup->id;

            unset($model->category_group_slug);

        }

        //normalise the record
        $model->pattern = LemurStr::normalizeForCategoryTable($model->pattern, ['set', 'bot']);
        //replace the wildcards
        $model->regexp_pattern = LemurStr::replaceWildCardsInPattern($model->pattern);
        //get the first letter
        $model->first_letter_pattern = LemurStr::getFirstCharFromStr($model->regexp_pattern);

        $model->topic = LemurStr::normalizeForCategoryTable($model->topic);
        $model->regexp_topic = LemurStr::replaceWildCardsInPattern($model->topic);
        $model->first_letter_topic = LemurStr::getFirstCharFromStr($model->regexp_topic);

        $model->that = LemurStr::normalizeForCategoryTable($model->that, ['set', 'bot']);
        $model->regexp_that = LemurStr::replaceWildCardsInPattern($model->that);
        $model->first_letter_that = LemurStr::getFirstCharFromStr($model->regexp_that);

        return $model;
    }
}
