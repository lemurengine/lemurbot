<?php

namespace LemurEngine\LemurBot\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Plugin
 * @package App\Models
 * @version November 28, 2022, 7:56 pm UTC
 *
 * @property integer $user_id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $classname
 * @property string $apply_plugin
 * @property boolean $return_onchange
 * @property boolean $is_master
 * @property boolean $is_active
 */
class Plugin extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;

    public $table = 'plugins';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'slug',
        'title',
        'description',
        'classname',
        'priority',
        'apply_plugin',
        'return_onchange',
        'is_master',
        'is_active'
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
        'title' => 'string',
        'description' => 'string',
        'priority' => 'integer',
        'classname' => 'string',
        'apply_plugin' => 'string',
        'return_onchange' => 'boolean',
        'is_master' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'classname' => 'required|string|max:255',
        'priority' => 'required|integer',
        'apply_plugin' => 'required|string',
        'return_onchange' => 'boolean',
        'is_master' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });

        static::addGlobalScope(fn ($query) => $query->orderBy('priority'));
    }


    /**
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * the query that is run in the datatable
     *
     * @return mixed
     */
    public function dataTableQuery()
    {

        return Plugin::select([$this->table.'.*','users.email'])
            ->leftJoin('users', 'users.id', '=', $this->table.'.user_id')
            ->myEditableItems();
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getFullTitleAttribute()
    {
        return $this->title . ' (' . $this->slug.')';
    }

    /**
     * Scope a query a specific property.
     * Get the bots this user is allowed to edit
     *
     * @param Builder $query
     * @param $name
     * @return Builder
     */
    public function scopeMyEditableItems($query)
    {
        $thisLoggedInUser = Auth::user();
        if (LemurPriv::isAdmin(Auth::user())) {
            //admins can edit all ...
            return $query;
        } else {
            return $query->where('bots.user_id', Auth::user()->id)
                ->orWhere('plugins.is_master', 1)
                ->orWhere('bot_plugins.user_id', Auth::user()->id);

        }
    }

}
