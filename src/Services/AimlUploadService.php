<?php


namespace LemurEngine\LemurBot\Services;

use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Exceptions\AimlUploadException;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Models\CategoryGroup;
use LemurEngine\LemurBot\Models\Language;
use Exception;
use Illuminate\Support\Facades\Auth;
use LemurEngine\LemurBot\Models\SetValue;
use Throwable;

class AimlUploadService
{

    /**
     * @param $file
     * @param $input
     * @return int
     */
    public function bulkInsertFromFile($file, $input)
    {

        $type = $file->getClientOriginalExtension();
        $input['language_id'] = $this->getLanguageId($input['language_id']);

        $loggedInUser = Auth::user()->id;

        //if we need to delete items from this group then do this here.....
        if ($input['processingOptions']==='delete') {
            if (strtolower($type) === 'aiml') {
                $fileName = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $categoryGroupNames[$fileName] = $fileName;
            } else {
                $categoryGroupNames = $this->getAllCategoryGroupNames($file);
            }

            $this->deleteAllCategoriesFromThisGroup($categoryGroupNames, $loggedInUser);
        }

        //now start the import
        if (strtolower($type) === 'aiml') {
            return $this->parseAimlFile($file, $input, $loggedInUser);
        } else {
            return $this->appendAndBulkInsertFromFile($file, $input, $loggedInUser);
        }
    }

    /**
     * @param $categoryGroupNames
     * @param $loggedInUser
     */
    public function deleteAllCategoriesFromThisGroup($categoryGroupNames, $loggedInUser)
    {

        $categoryGroupIds = CategoryGroup::whereIn('name', $categoryGroupNames)
            ->where('user_id', $loggedInUser)->pluck('id', 'id');

        if (count($categoryGroupIds)>0) {
            Category::whereIn('category_group_id', $categoryGroupIds)->where('user_id', $loggedInUser)->delete();
        }
    }

    /**
     *
     * @param $file
     * @return array
     */
    public function getAllCategoryGroupNames($file)
    {

        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        //ignore the first line
        array_shift($data);

        $categoryGroupNameArr = [];

        //delete the items linked to this record
        foreach ($data as $index => $record) {
            $filename = strtolower($record[0]);
            $categoryGroupNameArr[$filename] = $filename;
        }

        //return a list of category group names
        return $categoryGroupNameArr;
    }

    /**
     * Get the language id
     *
     * @param $languageSlug
     * @return mixed
     * @throws Exception
     */
    public function getLanguageId($languageSlug)
    {

        //get the language_id by name
        $language = Language::where('slug', 'like', $languageSlug)->first();

        //wordSpellingGroup doesnt exist...
        if ($language == null) {
            throw new Exception("No such language ($languageSlug) exists");
        }

        return $language->id;
    }

    /**
     * @param $file
     * @param $input
     * @param $loggedInUser
     * @return int
     *
     */
    public function appendAndBulkInsertFromFile($file, $input, $loggedInUser)
    {

        $path = $file->getRealPath();

        //this has been adapted as people are just uploading badly formed csv's
        //i am going to leave the other csv uploads as they are
        //but if i need to update then i will
        $handle = fopen($path, "r");
        while (($dataItem = fgetcsv($handle, 0, ",", "\"", "\\"))!== false) {
            $data[] =  $dataItem;
        }
        //ignore the first line
        array_shift($data);

        $categoryGroupIds = [];
        $insertedRecords = 0;

        foreach ($data as $index => $record) {
            $categoryGroupName = $record[0];
            if (!isset($categoryGroupIds[$categoryGroupName])) {
                $categoryGroupIds[$categoryGroupName] = $this->getOrCreateCategoryGroupId(
                    $categoryGroupName,
                    $input['language_id'],
                    $loggedInUser,
                    $input['status']
                );
            }

            if (!isset($record[1])) {
                throw new AimlUploadException('Row: '.$index.' - Cannot read pattern (column 2), please check the data');
            }
            $pattern = trim($record[1]);

            $topic = trim($record[2]);
            $that = trim($record[3]);

            if (!isset($record[4])) {
                throw new AimlUploadException('Row: '.$index.' - Cannot read template (column 5), please check the data');
            }
            $template = trim($record[4]);
            $status = $input['status'];
            $categoryGroupId = $categoryGroupIds[$categoryGroupName];
            $this->createOrUpdateCategory($categoryGroupId, $pattern, $topic, $that, $template, $status);

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
     * @param $file
     * @param $input
     * @param $loggedInUser
     * @return int
     * @throws AimlUploadException
     */
    public function parseAimlFile($file, $input, $loggedInUser)
    {

        $status = $input['status'];
        $language_id = $input['language_id'];

        $content = $file->get();

        $categoryGroupId = $this->createOrGetCategoryGroupIdFromFile(
            $file,
            $language_id,
            $loggedInUser,
            $input['status']
        );

        try {
            $xml = simplexml_load_string($content);
        } catch (Throwable $e) {
            $message = str_replace("simplexml_load_string(): ", "", $e->getMessage());
            throw new AimlUploadException($message);
        }



        $total = 0;

        if (!empty($xml->topic)) {
            foreach ($xml->topic->category as $item) {
                $topic = (string)$xml->topic['name'];

                $patternString = trim($item->pattern->asXML());
                $patternLength = strlen($patternString)-strlen('</pattern>');
                $pattern = substr($patternString, 0, $patternLength);
                $pattern = substr($pattern, strlen('<pattern>'), $patternLength);
                $pattern = trim($pattern);

                $templateString = trim($item->template->asXML());
                $templateLength = strlen($templateString)-strlen('</template>');
                $template = substr($templateString, 0, $templateLength);
                $template = substr($template, strlen('<template>'), $templateLength);
                $template = trim($template);

                if (!empty($item->that->asXML())) {
                    $thatString = trim($item->that->asXML());
                    $thatLength = strlen($thatString)-strlen('</that>');
                    $that = substr($thatString, 0, $thatLength);
                    $that = substr($that, strlen('<that>'), $thatLength);
                    $that = trim($that);
                } else {
                    $that = '';
                }

                $this->createOrUpdateCategory($categoryGroupId, $pattern, $topic, $that, $template, $status);

                $total++;
            }
        }


        if (!empty($xml->category)) {
            foreach ($xml->category as $item) {
                $topic = '';

                $patternString = trim($item->pattern->asXML());
                $patternLength = strlen($patternString) - strlen('</pattern>');
                $pattern = substr($patternString, 0, $patternLength);
                $pattern = substr($pattern, strlen('<pattern>'), $patternLength);
                $pattern = trim($pattern);

                $templateString = trim($item->template->asXML());
                $templateLength = strlen($templateString)-strlen('</template>');
                $template = substr($templateString, 0, $templateLength);
                $template = substr($template, strlen('<template>'), $templateLength);
                $template = trim($template);


                if (!empty($item->that->asXML())) {
                    $thatString = trim($item->that->asXML());
                    $thatLength = strlen($thatString) - strlen('</that>');
                    $that = substr($thatString, 0, $thatLength);
                    $that = substr($that, strlen('<that>'), $thatLength);
                    $that = trim($that);
                } else {
                    $that = '';
                }

                $this->createOrUpdateCategory($categoryGroupId, $pattern, $topic, $that, $template, $status);

                $total++;
            }
        }

        return $total;
    }

    /**
     * @param $pattern
     * @param $template
     * @param $categoryGroupId
     * @param $that
     * @param $topic
     * @param $status
     */
    public function createOrUpdateCategory($categoryGroupId, $pattern, $topic, $that, $template, $status)
    {


        $data['user_id']=Auth::user()->id;
        $data['category_group_id']=$categoryGroupId;

        //normalise the record
        $data['pattern'] = LemurStr::normalizeForCategoryTable($pattern, ['set','bot']);
        //replace the wildcards
        $data['regexp_pattern'] = LemurStr::replaceWildCardsInPattern($data['pattern']);
        //get the first letter
        $data['first_letter_pattern'] = LemurStr::getFirstCharFromStr($data['pattern']);

        $data['topic'] = LemurStr::normalizeForCategoryTable($topic);
        $data['regexp_topic'] = LemurStr::replaceWildCardsInPattern($data['topic']);
        $data['first_letter_topic'] = LemurStr::getFirstCharFromStr($data['regexp_topic']);

        $data['that'] = LemurStr::normalizeForCategoryTable($that, ['set','bot']);
        $data['regexp_that'] = LemurStr::replaceWildCardsInPattern($data['that']);
        $data['first_letter_that'] = LemurStr::getFirstCharFromStr($data['regexp_that']);

        //get the category if it exists....
        $category = Category::where('pattern', $data['pattern'])
            ->where('that', $data['that'])
            ->where('topic', $data['topic'])
            ->where('user_id', $data['user_id'])
            ->where('category_group_id', $data['category_group_id'])
            ->withTrashed()
            ->first();

        $result = 'exists';


        if ($category === null) {
            $category = new Category;
            $category->pattern = $data['pattern'];
            $category->that = $data['that'];
            $category->topic = $data['topic'];
            $category->user_id = $data['user_id'];
            $category->category_group_id = $data['category_group_id'];
            $category->regexp_pattern = $data['regexp_pattern'];
            $category->first_letter_pattern = $data['first_letter_pattern'];

            $category->regexp_topic = $data['regexp_topic'];
            $category->first_letter_topic = $data['first_letter_topic'];

            $category->regexp_that = $data['regexp_that'];
            $category->first_letter_that = $data['first_letter_that'];
            $category->deleted_at=null;
            $category->status = $status;

            $result = 'new';
        }

        //update template
        $category->template = $template;
        $category->deleted_at=null;
        $category->save();

        return $result;
    }

    /**
     * @param $file
     * @param $languageId
     * @param $loggedInUser
     * @return mixed
     */
    public function createOrGetCategoryGroupIdFromFile($file, $languageId, $loggedInUser, $status)
    {

        $uploadedFile = $file->getClientOriginalName();
        $filename = pathinfo($uploadedFile, PATHINFO_FILENAME);

        return $this->getOrCreateCategoryGroupId($filename, $languageId, $loggedInUser, $status);
    }

    /**
     * Get the category group id id
     * if it doesnt exist - create it
     * or if it is deleted - restore it
     * all category groups are created with the status T for testing
     *
     * @param $categoryGroupName
     * @param $languageId
     * @param $userId
     * @param $status
     * @return mixed
     */
    public function getOrCreateCategoryGroupId($categoryGroupName, $languageId, $userId, $status)
    {

        //get the set_id by name and created by user..
        $categoryGroup = CategoryGroup::where('name', 'like', $categoryGroupName)
            ->where('language_id', $languageId)->where('user_id', $userId)->withTrashed()->first();

        //set doesnt exist...
        if ($categoryGroup == null) {
            $categoryGroup = new CategoryGroup;
            $categoryGroup->name = ucwords($categoryGroupName);
            $categoryGroup->user_id = $userId;
            $categoryGroup->language_id = $languageId;
            $categoryGroup->description = "Category Group created when uploading CSV";
            $categoryGroup->is_master = false;
            $categoryGroup->status = $status;
            $categoryGroup->save();
        } elseif ($categoryGroup->deleted_at !== null) {
            $categoryGroup->restore();
        }

        return $categoryGroup->id;
    }




    /**
     * prepare the input
     * @param $str
     * @return string|string[]|null
     */
    public function normalize($str)
    {

        $str = str_replace("'", " ", $str);
        $str = str_replace('"', " ", $str);
        $str = str_replace('.', "", $str);
        $str = str_replace('?', "", $str);
        $str = str_replace('!', "", $str);
        $str = str_replace(';', "", $str);
        $str = str_replace(':', "", $str);

        //remove multiple whitespaces
        $str = preg_replace('/\s+/', ' ', $str);
        //trim
        $str = trim($str);
        //convert to upper
        $str = strtoupper($str);
        return $str;
    }


    /**
     * @param $regexp
     * @return mixed
     */
    public static function createSqlRegExp($regexp)
    {
        //handle consecutive wildcards first
        $regexp = str_replace('* * * * *', '%', $regexp);
        $regexp = str_replace('* * * *', '%', $regexp);
        $regexp = str_replace('* * *', '%', $regexp);
        $regexp = str_replace('* *', '%', $regexp);
        $regexp = str_replace('*', '%', $regexp);
        return $regexp;
    }
}
