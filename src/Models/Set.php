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
 *      definition="Set",
 *      required={"user_id", "slug", "name", "description", "is_master"},
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
class Set extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use CascadeSoftDeletes;
    use HasPackageFactory;


    public $table = 'sets';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $cascadeDeletes = ['setValues'];

    public $fillable = [
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
        'user_id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string',
        'is_master' => 'boolean'
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
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:sets,name|string|max:255',
        'description' => 'required|string',

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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return HasMany
     **/
    public function setValues()
    {
        return $this->hasMany(SetValue::class, 'set_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

        return Set::select([$this->table.'.*','users.email'])
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
