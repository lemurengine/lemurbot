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
 * @SWG\Definition(
 *      definition="BotWordSpellingGroup",
 *      required={"bot_id", "word_spelling_group_id"},
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
 *          property="bot_id",
 *          description="bot_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="word_spelling_group_id",
 *          description="word_spelling_group_id",
 *          type="integer",
 *          format="int32"
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
class BotWordSpellingGroup extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;
    use HasPackageFactory;


    public $table = 'bot_word_spelling_groups';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'slug',
        'user_id',
        'bot_id',
        'word_spelling_group_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
        'id' => 'integer',
        'user_id' => 'integer',
        'bot_id' => 'integer',
        'word_spelling_group_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'bot_id' => 'required',
        'word_spelling_group_id' => 'required',
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
    public function wordSpellingGroup()
    {
        return $this->belongsTo(WordSpellingGroup::class, 'word_spelling_group_id');
    }


    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return BotWordSpellingGroup::select([$this->table.'.*','users.email','bots.slug as bot',
                                        'word_spelling_groups.name as word_spelling_group'])
                ->leftJoin(
                    'word_spelling_groups',
                    'word_spelling_groups.id',
                    '=',
                    $this->table.'.word_spelling_group_id'
                )
                ->leftJoin('bots', 'bots.id', '=', $this->table.'.bot_id')
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }

    /**
     * return a list of all available word spelling groups
     * and mark if they are enabled for the current bot or not
     * order by name
     *
     * @param $botId
     * @return mixed
     */
    public static function getAllWordSpellingsGroupsForBot($botId)
    {

        // get a nice list of word spelling groups

        //below is a list of word_spelling_groups linked to this bot
        $ids = BotWordSpellingGroup::select([
            'word_spelling_groups.id'])
            ->join(
                'word_spelling_groups',
                'word_spelling_groups.id',
                '=',
                'bot_word_spelling_groups.word_spelling_group_id'
            )
            ->join('bots', 'bots.id', '=', 'bot_word_spelling_groups.bot_id')
            ->Where('bots.id', $botId)
            ->pluck('word_spelling_groups.id')
            ->toArray();

        $linkedWordSpellingGroupIds = array_flip($ids);



        //this is a list of ALL word_spelling_groups excluding the ones we have accounted for above.
        $allWordSpellingGroups = WordSpellingGroup::select([
            'word_spelling_groups.id as word_spelling_group_id',
            'word_spelling_groups.user_id',
            'word_spelling_groups.language_id',
            'word_spelling_groups.slug as word_spelling_group_slug',
            'word_spelling_groups.name',
            'word_spelling_groups.description',
            'word_spelling_groups.is_master'])
            ->where('word_spelling_groups.user_id', Auth::user()->id)
            ->orWhere('word_spelling_groups.is_master', 1)
            ->orderBy('word_spelling_groups.name')
            ->get();


        foreach ($allWordSpellingGroups as $index => $wordSpellingGroup) {
            if (isset($linkedWordSpellingGroupIds[$wordSpellingGroup->word_spelling_group_id])) {
                $allWordSpellingGroups[$index]->is_linked = 1;
            } else {
                $allWordSpellingGroups[$index]->is_linked = 0;
            }
        }


        return $allWordSpellingGroups;
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
