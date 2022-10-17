<?php


namespace LemurEngine\LemurBot\Services;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\BotProperty;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class BotPropertyUploadService
{


    /**
     * @param $file
     * @param $input
     * @return int
     */
    public function bulkInsertFromFile($file, $input)
    {

        if ($input['processingOptions']=='append') {
            return $this->appendAndBulkInsertFromFile($file);
        } else {
            return $this->deleteAndBulkInsertFromFile($file);
        }
    }

    /**
     * @param $file
     * @return int
     */
    public function appendAndBulkInsertFromFile($file)
    {

        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        //ignore the first line
        array_shift($data);

        $loggedInUser = Auth::user();

        $botIds = [];
        $insertedRecords = 0;

        foreach ($data as $index => $record) {
            $botSlug = $record[0];
            if (!isset($botIds[$botSlug])) {
                $botIds[$botSlug] = $this->getBotId($botSlug, $loggedInUser);
            }

            $this->createOrUpdateBotProperty($botIds[$botSlug], $record[1], $record[2], $loggedInUser);
            $insertedRecords++;
        }

        //return the total records added
        return $insertedRecords;
    }

    /**
     *
     * delete the values under the existing set
     * and then run the bulk insert
     *
     * @param $file
     * @return int
     */
    public function deleteAndBulkInsertFromFile($file)
    {

        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        //ignore the first line
        array_shift($data);

        $loggedInUser = Auth::user();

        //delete the items linked to this record
        foreach ($data as $index => $record) {
            $botSlug = $record[0];
            if (!isset($botIds[$botSlug])) {
                //set this so it is excluded on the next loop
                $botIds[$botSlug] = $this->getBotId($botSlug, $loggedInUser);
                //delete all the values in this set that belong to this user
                BotProperty::where('bot_id', $botIds[$botSlug])->where('user_id', $loggedInUser->id)->delete();
            }
        }

        //now to insert
        return $this->appendAndBulkInsertFromFile($file);
    }


    /**
     * Get the bot id
     *
     * @param $botSlug
     * @param $loggedInUser
     * @return mixed
     */
    public function getBotId($botSlug, $loggedInUser)
    {


        //admins can update any
        if(LemurPriv::isAdmin($loggedInUser)){
            $bot = Bot::where('slug', $botSlug)->first();
        } else {
            //others can only update their own
            $bot = Bot::where('slug', $botSlug)->where('user_id', $loggedInUser->id)->first();
        }



        //set doesnt exist...
        if ($bot == null) {
            throw new ModelNotFoundException("This bot does not exist");
        }

        return $bot->id;
    }

    /**
     * Create the BotProperty if it doesnt exist or restore if deleted
     *
     * @param $botId
     * @param $name
     * @param $value
     * @param $loggedInUser
     * @return mixed
     */
    public function createOrUpdateBotProperty($botId, $name, $value, $loggedInUser)
    {

        //get the property by bot_id created by user..
        $botProperty = BotProperty::where('bot_id', $botId)->where('name', $name)->withTrashed()->first();

        //botProperty_value doesnt exist...
        if ($botProperty == null) {
            $botProperty = new BotProperty;
            $botProperty->bot_id = $botId;
            $botProperty->name = $name;
            $botProperty->value = $value;
            $botProperty->user_id = $loggedInUser->id;
            $botProperty->save();
        } else {
            $botProperty->deleted_at=null;
            $botProperty->value = $value;
            $botProperty->save();
        }

        return $botProperty->id;
    }
}
