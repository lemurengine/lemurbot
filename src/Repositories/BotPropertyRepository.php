<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\BotProperty;
use LemurEngine\LemurBot\Models\Section;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BotPropertyRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 12:49 pm UTC
*/

class BotPropertyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'user_id',
        'section_id',
        'slug',
        'name',
        'value'
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
        return BotProperty::class;
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return Model
     */
    public function update($input, $id)
    {

        //we never update the bot or the property name ...
        //we can only update the value or the section id
        $newInput['value']=$input['value'];
        $newInput['section_id']=$input['section_id'];

        return parent::update($newInput, $id);
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
        //firstly we will just blank all existing....
        BotProperty::where('bot_id', $input['bot_id'])->update(['value'=>'']);

        foreach ($input['name'] as $name => $value) {

            $botProperty = BotProperty::where('bot_id', $input['bot_id'])->where('name', $name)
                    ->withTrashed()
                    ->first();

            //do we have a sectionId?
            $sectionId = $this->getSectionId($name, $input['bot_id']);

            if ($botProperty === null && $value != '') {

                $botProperty = new BotProperty([
                    'bot_id' => $input['bot_id'],
                    'section_id' => $sectionId,
                    'name' => $name,
                    'value' => $value
                ]);
                $botProperty->save();

            } elseif ($botProperty !== null && $botProperty->trashed()) {
                $botProperty->restore();
                if($botProperty->section_id === null){
                    $botProperty->section_id = $sectionId;
                }
                $botProperty->value = $value;
                $botProperty->save();
            } elseif ($botProperty !== null) {

                if($botProperty->section_id === null){
                    $botProperty->section_id = $sectionId;
                }

                if($sectionId === null){
                    dd($botProperty, $name);
                }

                $botProperty->value = $value;
                $botProperty->save();
            }



        }


        return true;
    }


    /**
     * Returns the botproperty section
     * It will check the DB first and if it doesnt exist it will check the config
     * @param $name
     * @param null $botId
     * @return int $sectionId
     */
    public function getSectionId($name, $botId = null){

        $sectionId = null;
        $botProperty = BotProperty::where('bot_id', $botId)->where('name', $name)->first();
        if($botProperty === null || $botProperty->section_id ===  null){
            $sectionSlug = config('lemurbot.section.bot_properties.fields.'.$name, null);
            $section = Section::where('slug', $sectionSlug)->where('type', 'BOT_PROPERTY')->first();
            if($section !== null){
                $sectionId = $section->id;
            }
        }else{
            $sectionId = $botProperty->section_id;
        }
        return $sectionId;


    }
}
