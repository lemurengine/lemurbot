<?php


namespace LemurEngine\LemurBot\Services;

use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\WordSpelling;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use Exception;
use Illuminate\Support\Facades\Auth;

class WordSpellingUploadService
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
     * @throws Exception
     */
    public function appendAndBulkInsertFromFile($file)
    {

        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        //ignore the first line
        array_shift($data);

        $loggedInUser = Auth::user()->id;

        $wordSpellingGroupIds = [];
        $insertedRecords = 0;

        foreach ($data as $index => $record) {
            $wordSpellingGroupName = $record[0];
            $languageName = $record[1];

            if (empty($wordSpellingGroupIds[$wordSpellingGroupName][$languageName])) {
                $wordSpellingGroupIds[$wordSpellingGroupName][$languageName] = $this->getWordSpellingGroupId(
                    $wordSpellingGroupName,
                    $languageName,
                    $loggedInUser
                );
            }
            $this->createWordSpelling(
                $wordSpellingGroupIds[$wordSpellingGroupName][$languageName],
                $record[1],
                $record[2],
                $loggedInUser
            );
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
            $wordSpellingGroupName = $record[0];
            $languageName = $record[1];

            if (!isset($wordSpellingGroupIds[$wordSpellingGroupName][$languageName])) {
                //set this so it is excluded on the next loop
                $wordSpellingGroupIds[$wordSpellingGroupName][$languageName] = $this->getWordSpellingGroupId(
                    $wordSpellingGroupName,
                    $languageName,
                    $loggedInUser
                );
                //delete all the values in this map that belong to this user
                WordSpelling::where(
                    'word_spelling_group_id',
                    $wordSpellingGroupIds[$wordSpellingGroupName][$languageName]
                )
                    ->where('user_id', $loggedInUser)->delete();
            }
        }

        //now to insert
        return $this->appendAndBulkInsertFromFile($file);
    }


    /**
     * Get the map id
     * if it doesnt exist - throw an exception
     * or if it is deleted - restore it
     *
     * @param $wordSpellingGroupName
     * @param $languageName
     * @param $userId
     * @return mixed
     * @throws Exception
     */
    public function getWordSpellingGroupId($wordSpellingGroupName, $languageName, $userId)
    {

        //get the word_spelling_group_id by name and created by user..
        $languageId = $this->getLanguageId($languageName);

        //get the word_spelling_group_id by name and created by user..
        $wordSpellingGroup = WordSpellingGroup::where('name', 'like', $wordSpellingGroupName)
            ->where('language_id', $languageId)->where('user_id', $userId)->withTrashed()->first();

        //wordSpellingGroup doesnt exist...
        if ($wordSpellingGroup == null) {
            throw new Exception("Word spelling group ($wordSpellingGroupName - $languageName) does not exist");
        } elseif ($wordSpellingGroup->deleted_at !== null) {
            $wordSpellingGroup->restore();
        }

        return $wordSpellingGroup->id;
    }

    /**
     * Get the language id
     *
     * @param $languageName
     * @return mixed
     * @throws Exception
     */
    public function getLanguageId($languageName)
    {

        //get the language_id by name
        $language = Language::where('name', 'like', $languageName)->first();

        //wordSpellingGroup doesnt exist...
        if ($language == null) {
            throw new Exception("No such language ($languageName) exists");
        }

        return $language->id;
    }


    /**
     * Create the wordSpelling if it doesnt exist or restore if deleted
     *
     * @param $wordSpellingGroupId
     * @param $word
     * @param $value
     * @param $userId
     * @return mixed
     */
    public function createWordSpelling($wordSpellingGroupId, $word, $replacement, $userId)
    {

        //get the word_spelling_group_id by name and created by user..
        $wordSpelling = WordSpelling::where('word_spelling_group_id', $wordSpellingGroupId)
            ->where('word', $word)->where('replacement', $replacement)
            ->where('user_id', $userId)->withTrashed()->first();

        //map_value doesnt exist...
        if ($wordSpelling == null) {
            $wordSpelling = new WordSpelling;
            $wordSpelling->word_spelling_group_id = $wordSpellingGroupId;
            $wordSpelling->word = $word;
            $wordSpelling->replacement = $replacement;
            $wordSpelling->user_id = $userId;
            $wordSpelling->save();
        } elseif ($wordSpelling->deleted_at !== null) {
            $wordSpelling->restore();
        }

        return $wordSpelling->id;
    }
}
