<?php

namespace LemurEngine\LemurBot\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * @return HasMany
     **/
    public function botPlugins()
    {
        return $this->hasMany(BotPlugin::class, 'plugin_id');
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
        if (LemurPriv::isAdmin(Auth::user())) {
            //admins can edit all ...
            return $query;
        } else {
            return $query->where(function ($query) {
                //it has to be owned by the bot author or be a master record
                $query->where('plugins.is_master', 1)
                    ->orWhere('plugins.user_id', Auth::user()->id);
            });
        }
    }

    /**
     * return a list of all available word spelling groups
     * and mark if they are enabled for the current bot or not
     * order by name
     *
     * @param $botId
     * @return mixed
     */
    public static function getAllPluginsForBot($botId)
    {

        //this is a list of ALL plugins excluding the ones we have accounted for above.
        return Plugin::select([
            'plugins.slug as plugin_slug',
            'plugins.title',
            'plugins.id',
            'plugins.description',
            'plugins.is_master',
            'bot_plugins.bot_id'])
            ->leftJoin('bot_plugins', function($join) use($botId){
                $join->on('plugins.id', '=', 'bot_plugins.plugin_id')
                    ->where('bot_plugins.plugin_id', $botId);
            })
            ->where(function ($query) {
                //it has to be owned by the bot author or be a master record
                $query->where('plugins.is_master', 1)->orWhere('plugins.user_id', Auth::user()->id);
            })
            ->orderBy('plugins.title')
            ->get();


    }


}
