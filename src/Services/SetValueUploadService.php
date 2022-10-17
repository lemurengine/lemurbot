<?php


namespace LemurEngine\LemurBot\Services;

use LemurEngine\LemurBot\Models\Set;
use LemurEngine\LemurBot\Models\SetValue;
use Illuminate\Support\Facades\Auth;

class SetValueUploadService
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

        $loggedInUser = Auth::user()->id;

        $setIds = [];
        $insertedRecords = 0;

        foreach ($data as $index => $record) {
            $setName = $record[0];
            if (!isset($setIds[$setName])) {
                $setIds[$setName] = $this->getSetId($setName, $loggedInUser);
            }
            $this->createSetValue($setIds[$setName], $record[1], $loggedInUser);
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

        $loggedInUser = Auth::user()->id;

        //delete the items linked to this record
        foreach ($data as $index => $record) {
            $setName = $record[0];
            if (!isset($setIds[$setName])) {
                //set this so it is excluded on the next loop
                $setIds[$setName] = $this->getSetId($setName, $loggedInUser);
                //delete all the values in this set that belong to this user
                SetValue::where('set_id', $setIds[$setName])->where('user_id', $loggedInUser)->delete();
            }
        }

        //now to insert
        return $this->appendAndBulkInsertFromFile($file);
    }


    /**
     * Get the set id
     * if it doesnt exist - create it
     * or if it is deleted - restore it
     *
     * @param $setName
     * @param $userId
     * @return mixed
     */
    public function getSetId($setName, $userId)
    {

        //get the set_id by name and created by user..
        $set = Set::where('name', 'like', $setName)->where('user_id', $userId)->withTrashed()->first();

        //set doesnt exist...
        if ($set == null) {
            $set = new Set;
            $set->name = ucwords($setName);
            $set->user_id = $userId;
            $set->description = "Set created when uploading CSV";
            $set->is_master = false;
            $set->save();
        } elseif ($set->deleted_at !== null) {
            $set->restore();
        }

        return $set->id;
    }

    /**
     * Create the setvalue if it doesnt exist or restore if deleted
     *
     * @param $setId
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function createSetValue($setId, $value, $userId)
    {

        //get the set_id by name and created by user..
        $setValue = SetValue::where('set_id', $setId)->where('value', $value)
            ->where('user_id', $userId)->withTrashed()->first();

        //set_value doesnt exist...
        if ($setValue == null) {
            $setValue = new SetValue;
            $setValue->set_id = $setId;
            $setValue->value = $value;
            $setValue->user_id = $userId;
            $setValue->save();
        } elseif ($setValue->deleted_at !== null) {
            $setValue->restore();
        }

        return $setValue->id;
    }
}
