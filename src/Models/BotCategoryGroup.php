<?php

namespace LemurEngine\LemurBot\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LemurEngine\LemurBot\Traits\HasPackageFactory;
use LemurEngine\LemurBot\Traits\UiValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 *
 *  * @SWG\Definition(
 *      definition="BotCategoryGroupRequest",
 *      required={"bot", "category_group"},
 *      @SWG\Property(
 *          property="bot_id",
 *          description="The slug of the bot (bots.slug)",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="category_group_id",
 *          description="The slug of the category_group (category_groups.slug)",
 *          type="string"
 *      ),
 * )
 *
 * @SWG\Definition(
 *      definition="BotCategoryGroupResponse",
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="bot_id",
 *          description="The slug of the bot (bots.slug)",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="category_group_id",
 *          description="The slug of the category_group (category_groups.slug)",
 *          type="string"
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
class BotCategoryGroup extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;



    public $table = 'bot_category_groups';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'slug',
        'user_id',
        'bot_id',
        'category_group_id'
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
        'category_group_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'category_group_id' => 'required',
    ];

    /**
     * Add the user_id on create
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
    public function categoryGroup()
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return BotCategoryGroup::select([$this->table.'.*','users.email',
                                            'bots.slug as bot','category_groups.name as category_group'])
                ->leftJoin('category_groups', 'category_groups.id', '=', $this->table.'.category_group_id')
                ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }

    /**
     * return a list of all available category groups
     * and mark if they are enabled for the current bot or not
     * order by name
     *
     * @param $botId
     * @param $botLanguageId
     * @return mixed
     */
    public static function getAllCategoryGroupsForBot($botId, $botLanguageId)
    {
        $cleanAllCategoryGroup = [];

        //below is a list of categories linked to this bot
        $ids = BotCategoryGroup::select([
            'category_groups.slug'])
            ->join('category_groups', 'category_groups.id', '=', 'bot_category_groups.category_group_id')
            ->leftjoin('sections', 'sections.id', '=', 'category_groups.section_id')
            ->join('bots', 'bots.id', '=', 'bot_category_groups.bot_id')
            ->where('bots.id', $botId)
            ->where('category_groups.language_id', $botLanguageId)
            ->orderBy('sections.order')
            ->orderBy('category_groups.name')
            ->pluck('category_groups.slug')
            ->toArray();

        $linkedCategoryGroupIds = array_flip($ids);


        //this is a list of ALL categories excluding the ones we have accounted for above.
        $allCategoryGroups = CategoryGroup::select([
            'category_groups.id as category_group_id',
            'category_groups.user_id',
            'category_groups.section_id',
            'category_groups.language_id',
            'category_groups.slug as category_group_id',
            'category_groups.name',
            'category_groups.description',
            'sections.order',
            'category_groups.is_master'])
            ->leftjoin('sections', 'sections.id', '=', 'category_groups.section_id')
            ->where('category_groups.language_id', $botLanguageId)
            ->where(function($q) {
                $q->where('category_groups.user_id', Auth::user()->id)
                    ->orWhere('category_groups.is_master', 1);
            })
            ->orderBy('sections.order')
            ->orderBy('category_groups.name')
            ->get();


        foreach ($allCategoryGroups as $index => $categoryGroup) {

            $cleanAllCategoryGroup[$categoryGroup->section_id][$index]=$categoryGroup;


            if (isset($linkedCategoryGroupIds[$categoryGroup->category_group_id])) {
                $cleanAllCategoryGroup[$categoryGroup->section_id][$index]->is_linked = 1;
            } else {
                $cleanAllCategoryGroup[$categoryGroup->section_id][$index]->is_linked = 0;
            }
        }

        return $cleanAllCategoryGroup;
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
