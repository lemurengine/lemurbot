<?php

namespace LemurEngine\LemurBot\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class BotPlugin
 * @package App\Models
 * @version November 28, 2022, 7:57 pm UTC
 *
 * @property string $slug
 * @property integer $user_id
 * @property integer $bot_id
 * @property integer $plugin_id
 */
class BotPlugin extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;

    public $table = 'bot_plugins';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'slug',
        'user_id',
        'bot_id',
        'plugin_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'user_id' => 'integer',
        'bot_id' => 'integer',
        'plugin_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required|integer',
        'plugin_id' => 'required|integer',
    ];

    /**
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });
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
    public function bot()
    {
        return $this->belongsTo(Bot::class, 'bot_id');
    }

    /**
     * @return BelongsTo
     **/
    public function plugin()
    {
        return $this->belongsTo(Plugin::class, 'plugin_id');
    }


    /**
     * the query that is run in the datatable
     *
     * @return mixed
     */
    public function dataTableQuery()
    {

        return BotPlugin::select([$this->table.'.*','users.email','bots.slug as bot',
            'plugins.title as plugin'])
            ->leftJoin('plugins', 'plugins.id', '=', $this->table.'.plugin_id')
            ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
            ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }


    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function($model) {
                return $model->bot->name.' '.$model->plugin->title;
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

}
