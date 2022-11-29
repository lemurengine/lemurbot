<?php

namespace LemurEngine\LemurBot\Repositories;

use Illuminate\Database\Eloquent\Model;
use LemurEngine\LemurBot\Models\BotPlugin;
use LemurEngine\LemurBot\Models\BotWordSpellingGroup;
use LemurEngine\LemurBot\Models\Plugin;
use LemurEngine\LemurBot\Models\WordSpellingGroup;

/**
 * Class BotPluginRepository
 * @package App\Repositories
 * @version November 28, 2022, 7:57 pm UTC
*/

class BotPluginRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'user_id',
        'bot_id',
        'plugin_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BotPlugin::class;
    }


    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     * @return Model
     */
    public function bulkCreate($input)
    {

        $totalBotPlugins = count($input['plugin_id']);

        for ($i=0; $i<$totalBotPlugins; $i++) {
            //find the word spelling group
            $plugin = Plugin::where('slug', $input['plugin_id'][$i])->first();

            $linked = $input['linked'][$i];

            //we want to link this bot to this plugin
            if ($linked == 1) {
                $botPlugin = BotPlugin::where('bot_id', $input['bot_id'])->where('plugin_id', $plugin->id)->first();
                if($botPlugin === null){
                    $this->create(['bot_id'=> $input['bot_id'], 'plugin_id'=> $plugin->id]);
                }
            } else {
                //if its not linked but it exists then delete it
                BotPlugin::where('bot_id', $input['bot_id'])
                    ->where('plugin_id', $plugin->id)
                    ->forceDelete();
            }
        }

        return true;
    }

}
