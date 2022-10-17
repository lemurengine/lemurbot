<?php

use LemurEngine\LemurBot\Models\CategoryGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * This migration has been created to correct the incorrect mapping which occurred
 * between the categories and category_groups
 * this will affect anyone that installed the program before v1.4.0
 * If a single thing doesnt match then this will exit with an error letting the user to perform a manual check
 * Below there is a list of the records which need to be remapped..
 * e.g. "std-religion"=>"std-happybirthday",
 * std-religion should actually be std-happybirthday
 * (becuase std-happybirthday categories are linked to std-religion category_group)
 *
 * Class CorrectBadlyGroupedCategories
 */
class CorrectBadlyGroupedCategories extends Migration
{

    protected $mappingArr =
        [
            "std-religion"=>"std-happybirthday",
            "std-that"=>"std-religion",
            "std-jokes"=>"std-that",
            "std-sales"=>"std-jokes",
            "std-robot"=>"std-horoscope",
            "std-sports"=>"std-howmany",
            "std-sextalk"=>"std-knockknock",
            "std-srai"=>"std-learn",
            "std-suffixes"=>"std-robot",
            "std-turing"=>"std-sales",
            "std-yesno"=>"std-sextalk",
            "std-learn"=>"std-shutup",
            "std-happybirthday"=>"std-sports",
            "std-horoscope"=>"std-srai",
            "std-howmany"=>"std-suffixes",
            "std-knockknock"=>"std-turing",
            "std-yomama"=>"std-yesno",
            "std-shutup"=>"std-yomama"
         ];
    /**
     * @var array
    /*
     *     23 => "std-religion"
    24 => "std-robot"
    25 => "std-sports"
    26 => "std-sales"
    27 => "std-sextalk"
    28 => "std-srai"
    29 => "std-that"
    30 => "std-suffixes"
    31 => "std-turing"
    32 => "std-yesno"
    33 => "std-learn"
    34 => "std-happybirthday"
    35 => "std-horoscope"
    36 => "std-howmany"
    37 => "std-jokes"
    38 => "std-knockknock"
    39 => "std-yomama"
    40 => "std-shutup"
     */
    protected $idChecker = [
            "std-religion" => 23,
            "std-robot" => 24,
            "std-sports"=> 25,
            "std-sales"=> 26,
            "std-sextalk"=> 27,
            "std-srai"=> 28,
            "std-that"=> 29,
            "std-suffixes"=> 30,
            "std-turing"=> 31,
            "std-yesno"=> 32,
            "std-learn"=> 33,
            "std-happybirthday"=> 34,
            "std-horoscope"=> 35,
            "std-howmany"=> 36,
            "std-jokes"=> 37,
            "std-knockknock"=> 38,
            "std-yomama"=> 39,
            "std-shutup"=> 40
        ];


    /**
     * Run the migrations.
     *
     * cross referenced https://github.com/russellhaering/ansr8r/tree/master/standard
     *
     *
     * there has been a mess up with the original linking of cagetories to groups
     * this sql fixes that in the safest way possible
     *         /**
     * [{"id":1,"slug":"dev-testcases"},    <-- checked ok
     * {"id":2,"slug":"std-critical"},      <-- checked ok
     * {"id":3,"slug":"std-rating"},        <-- checked ok
     * {"id":4,"slug":"std-hello"},         <-- checked ok
     * {"id":5,"slug":"std-65percent"},     <-- checked ok
     * {"id":6,"slug":"std-atomic"},        <-- checked ok
     * {"id":7,"slug":"std-botmaster"},     <-- checked ok
     * {"id":8,"slug":"std-brain"},         <-- checked ok
     * {"id":9,"slug":"std-dictionary"},    <-- checked ok
     * {"id":10,"slug":"std-howto"},        <-- checked ok
     * {"id":11,"slug":"std-inventions"},   <-- checked ok
     * {"id":12,"slug":"std-gender"},       <-- checked ok
     * {"id":13,"slug":"std-geography"},    <-- checked ok
     * {"id":14,"slug":"std-german"},       <-- checked ok
     * {"id":15,"slug":"std-gossip"},       <-- checked ok
     * {"id":16,"slug":"std-knowledge"},    <-- checked ok
     * {"id":17,"slug":"std-lizards"},      <-- checked ok
     * {"id":18,"slug":"std-numbers"},      <-- checked ok
     * {"id":19,"slug":"std-personality"},  <-- checked ok
     * {"id":20,"slug":"std-pickup"},       <-- checked ok
     * {"id":21,"slug":"std-politics"},     <-- checked ok
     * {"id":22,"slug":"std-profile"},      <-- checked ok
     * {"id":23,"slug":"std-religion"},     <-- checked this looks like std-happybirthday
     * {"id":24,"slug":"std-robot"},        <-- checked this looks like std-horoscope
     * {"id":25,"slug":"std-sports"},       <-- checked this looks like std-howmany
     * {"id":26,"slug":"std-sales"},        <-- checked this looks like std-jokes
     * {"id":27,"slug":"std-sextalk"},      <-- checked this looks like std-knockknock
     * {"id":28,"slug":"std-srai"},         <-- checked this looks like std-learn
     * {"id":29,"slug":"std-that"},         <-- checked this looks like std-religion
     * {"id":30,"slug":"std-suffixes"},     <-- checked this looks like std-robot
     * {"id":31,"slug":"std-turing"},       <-- checked this looks like std-sales
     * {"id":32,"slug":"std-yesno"},        <-- checked this looks like std-sextalk
     * {"id":33,"slug":"std-learn"},        <-- checked this looks like std-shutup
     * {"id":34,"slug":"std-happybirthday"},<-- checked this looks like std-sports
     * {"id":35,"slug":"std-horoscope"},    <-- checked this looks like std-srai
     * {"id":36,"slug":"std-howmany"},      <-- checked this looks like std-suffixes
     * {"id":37,"slug":"std-jokes"},        <-- checked this looks like std-that
     * {"id":38,"slug":"std-knockknock"},   <-- checked this looks like std-turing
     * {"id":39,"slug":"std-yomama"},       <-- checked this looks like std-yesno
     * {"id":40,"slug":"std-shutup"}]       <-- checked this looks like std-yomama
     */
     /*
     *
     * @return void
     */
    public function up()
    {
        //start the transaction
        DB::beginTransaction();

        try {

            $mismatchedFiles = ["std-religion","std-robot","std-sports","std-sales","std-sextalk","std-srai",
                "std-that","std-suffixes","std-turing","std-yesno","std-learn","std-happybirthday","std-horoscope",
                "std-howmany","std-jokes","std-knockknock","std-yomama","std-shutup"];

            $all = CategoryGroup::whereIn('slug',$mismatchedFiles)->withTrashed();

            if($all->count()<=0){
                //Fresh install - nothing to correct
                return true;
            }

            foreach($all->get() as $item) {


                echo "\nChecking: ".$this->idChecker[$item->slug] ." == ".$item->id;
                if($this->idChecker[$item->slug]!==$item->id){
                    throw new Exception("Exiting cannot guarantee that the IDs are the 100% correct. This migration should be checked and performed manually");
                }

                $id = $item->slug;
                $original[$id]['id'] = $item->id;
                $original[$id]['slug'] = $item->slug;
                $original[$id]['user_id'] = $item->user_id;
                $original[$id]['language_id'] = $item->language_id;
                $original[$id]['name'] = $item->name;
                $original[$id]['description'] = $item->description;
                $original[$id]['status'] = $item->status;
                $original[$id]['is_master'] = $item->is_master;
                $original[$id]['deleted_at'] = $item->deleted_at;

            }


            foreach($this->mappingArr as $mapFrom => $mapTo) {


                $groupToUpdate = CategoryGroup::where('id', $original[$mapFrom]['id'])->withTrashed()->first();
                echo "\nUpdating: ".$groupToUpdate->name ." to ".$original[$mapTo]['name'];

                $slugChecker = CategoryGroup::where('slug',$original[$mapTo]['slug'])->withTrashed()->first();

                //echo "\nOriginal Record\n";
                //print_r($groupToUpdate->toArray());

                if($slugChecker!==null){
                    $groupToUpdate->slug = $original[$mapTo]['slug'].'---remapped';
                    $groupToUpdate->name = $original[$mapTo]['name'].'---remapped';
                }else{
                    $groupToUpdate->slug = $original[$mapTo]['slug'];
                    $groupToUpdate->name = $original[$mapTo]['name'];
                }



                //echo "\nUpdating Record To\n";

                $groupToUpdate->description = $original[$mapTo]['description'];
                $groupToUpdate->language_id = $original[$mapTo]['language_id'];
                $groupToUpdate->status = $original[$mapTo]['status'];
                $groupToUpdate->is_master = $original[$mapTo]['is_master'];
                $groupToUpdate->deleted_at = $original[$mapTo]['deleted_at'];

                //print_r($groupToUpdate->toArray());

                $groupToUpdate->save();
                //echo "\nGot\n";
                //print_r($groupToUpdate->toArray());

                //echo "\n---- end -----\n";

            }

            $typo = CategoryGroup::where('slug','like','%---remapped')->get();
            foreach($typo as $item) {

                $item->name = str_replace('---remapped','',$item->name);
                $item->slug = str_replace('---remapped','',$item->slug);
                $item->save();

            }


            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error(["Rolled back INSPECT THIS manually and update if required", $e]);
            dd(["Rolled back INSPECT THIS manually and update if required", $e]);
        }

    }

    public function down()
    {

    }


}
