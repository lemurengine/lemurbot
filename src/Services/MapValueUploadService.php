<?php


namespace LemurEngine\LemurBot\Services;

use LemurEngine\LemurBot\Models\Map;
use LemurEngine\LemurBot\Models\MapValue;
use Illuminate\Support\Facades\Auth;

class MapValueUploadService
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

        $mapIds = [];
        $insertedRecords = 0;

        foreach ($data as $index => $record) {
            $mapName = $record[0];
            if (!isset($mapIds[$mapName])) {
                $mapIds[$mapName] = $this->getMapId($mapName, $loggedInUser);
            }
            $this->createMapValue($mapIds[$mapName], $record[1], $record[2], $loggedInUser);
            $insertedRecords++;
        }

        //return the total records added
        return $insertedRecords;
    }

    /**
     *
     * delete the values under the existing map
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
            $mapName = $record[0];
            if (!isset($mapIds[$mapName])) {
                //set this so it is excluded on the next loop
                $mapIds[$mapName] = $this->getMapId($mapName, $loggedInUser);
                //delete all the values in this map that belong to this user
                MapValue::where('map_id', $mapIds[$mapName])->where('user_id', $loggedInUser)->delete();
            }
        }

        //now to insert
        return $this->appendAndBulkInsertFromFile($file);
    }


    /**
     * Get the map id
     * if it doesnt exist - create it
     * or if it is deleted - restore it
     *
     * @param $mapName
     * @param $userId
     * @return mixed
     */
    public function getMapId($mapName, $userId)
    {

        //get the map_id by name and created by user..
        $map = Map::where('name', 'like', $mapName)->where('user_id', $userId)->withTrashed()->first();

        //map doesnt exist...
        if ($map == null) {
            $map = new Map;
            $map->name = ucwords($mapName);
            $map->user_id = $userId;
            $map->description = "Map created when uploading CSV";
            $map->is_master = false;
            $map->save();
        } elseif ($map->deleted_at !== null) {
            $map->restore();
        }

        return $map->id;
    }

    /**
     * Create the mapvalue if it doesnt exist or restore if deleted
     *
     * @param $mapId
     * @param $word
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function createMapValue($mapId, $word, $value, $userId)
    {

        //get the map_id by name and created by user..
        $mapValue = MapValue::where('map_id', $mapId)->where('word', $word)
            ->where('value', $value)->where('user_id', $userId)->withTrashed()->first();

        //map_value doesnt exist...
        if ($mapValue == null) {
            $mapValue = new MapValue;
            $mapValue->map_id = $mapId;
            $mapValue->word = $word;
            $mapValue->value = $value;
            $mapValue->user_id = $userId;
            $mapValue->save();
        } elseif ($mapValue->deleted_at !== null) {
            $mapValue->restore();
        }

        return $mapValue->id;
    }
}
