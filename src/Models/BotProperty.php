<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

/**
 * @SWG\Definition(
 *      definition="BotProperty",
 *      required={"bot_id","section_id", "slug", "name", "value", "section"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="section_id",
 *          description="section_id",
 *          type="integer",
 *          format="int32"
 *      }
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
class BotProperty extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'bot_properties';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'bot_id',
        'user_id',
        'section_id',
        'slug',
        'name',
        'value',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'user_id' => 'integer',
        'section_id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'value' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'section_id' => 'required',
        'name' => 'required',
        'value' => 'required'
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
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Scope a query a specific property.
     *
     * @param Builder $query
     * @param $name
     * @return Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
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

            return BotProperty::select([$this->table.'.*','users.email','bots.slug as bot','sections.name as sname'])
                ->leftJoin('sections', 'sections.id', '=', $this->table.'.section_id')
                ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
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

    /**
     * return a list of all available properties
     * and mark if they are enabled for the current bot or not
     * order by name
     *
     * @param $botId
     * @return mixed
     */
    public static function getFullPropertyList($botId)
    {
        $cleanProperties = [];
        $recommendedProperties = config('lemurbot.section.bot_properties.fields');
        foreach ($recommendedProperties as $property => $sectionSlug) {
            if(!isset($sectionSlugIds[$sectionSlug])){
                $section = Section::where('slug',$sectionSlug)->where('type', 'BOT_PROPERTY')->first();
                $sectionSlugIds[$sectionSlug]['id']=($section->id??null);
                $sectionSlugIds[$sectionSlug]['name']=($section->name??'Misc');
            }
            $cleanProperties[$sectionSlug][$property]['name'] = '';
            $cleanProperties[$sectionSlug][$property]['value'] = '';
            $cleanProperties[$sectionSlug][$property]['slug'] = '';
        }

        $savedProperties = BotProperty::with('section')->where('bot_id', $botId)->get();
        foreach ($savedProperties as $property) {
            if(empty($property->section->slug)){
                $sectionSlug = null;
            }else{
                $sectionSlug = $property->section->slug;
            }
            $cleanProperties[$sectionSlug][$property->name]['name'] = $property->name;
            $cleanProperties[$sectionSlug][$property->name]['value'] = $property->value;
            $cleanProperties[$sectionSlug][$property->name]['slug'] = $property->slug;
        }
        return $cleanProperties;
    }
}
