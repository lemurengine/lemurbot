<?php

namespace LemurEngine\LemurBot\Classes;

class LemurStr
{

    /**
     * @param $str
     * @return array[]|false|string[]
     */
    public static function splitIntoSentences($str):array|false
    {

        $str = str_replace("<br/>",".",$str);
        $str = str_replace("<br>",".",$str);
        $str = str_replace("<br />",".",$str);

        return preg_split('/(\s*,*\s*)*[.?!;:]+(\s*,*\s*)*/', $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public static function normalizeInput($str, $uppercase=true):string
    {

        //some common replacements
        foreach (config('lemurbot.tag.commonNormalizations') as $in => $out) {
            $str = str_replace($in, $out, $str);
        }
        foreach (config('lemurbot.tag.inputOnlyNormalizations') as $in => $out) {
            $str = str_replace($in, $out, $str);
        }
        //replace everything but numbers
        $str = preg_replace('/[^a-z0-9\pL]+/iu', ' ', $str);

        //remove multiple whitespaces
        $str = preg_replace('/\s+/', ' ', $str);
        //trim
        $str = trim($str);
        if ($uppercase) {
            //convert to upper
            $str = mb_strtoupper($str);
        }

        return $str;

    }


    /**
     * prepare the input
     * @param $str
     * @param bool $uppercase
     * @return string
     */
    public static function normalize($str, bool $uppercase = true):string
    {

        if($str===''){
            return '';
        }

        //replace everything but numbers
        $str = preg_replace('/[^a-z0-9\pL]+/iu', ' ', $str);
        //remove multiple whitespaces
        $str = preg_replace('/\s+/', ' ', $str);
        //trim
        $str = trim($str);
        if ($uppercase) {
            //convert to upper
            $str = mb_strtoupper($str);
        }

        return $str;
    }


    /**
     * remove non alphanumeric char from end of string
     *
     * @param $str
     * @return string
     */
    public static function removeSentenceEnders($str):string
    {
        return preg_replace('/[^a-z0-9\pL]+\Z/iu', '', $str);
    }


    public static function mbUpperFirst($str, $encoding = "UTF-8", $lower_str_end = false): string
    {
        $str = trim($str);
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        return $first_letter . $str_end;
    }


    /**
     * 1. the values come in denormalized
     *
     * @param $string - the user value - could be input, that, topic
     * @param $regExpItem - the value from the db - could be regexp_input, regexp_that, regexp_topic
     * @return array
     */
    public static function extractWildcardFromString($string, $regExpItem, $shift=true):array
    {


        //but ultimately we saved the DENORMALIZED value as well minus sentence-enders.
        //EXAMPLE OF CLIENT INPUT
        //is this jon's sandwich?
        //EXAMPLE OF THE PATTERN THIS MATCHES
        //IS THIS *
        //EXAMPLE OF A DENORMALIZED INPUT
        //is this jon's sandwich?
        //EXAMPLE OF A NORMALIZED INPUT
        //is this jon s sandwich
        //EXAMPLE OF A DENORMALIZED STAR
        //jon's sandwich (<- notice no closing punctuation)
        //EXAMPLE OF A NORMALIZED STAR
        //sandwich jon s sandwich


        //remember we may have a pattern which contains things like this
        //i like the color (\bblue\b|\bgreen\b|\bred\b)
        //in this case the entire reg exp bit should be replaced with star as it needs to be treated as a wildcard...
        $regExpItem = self::convertToRegExpPattern($regExpItem);

        $match=self::normalize($string, false);

        //we perform all our checks against the normalised value as these are the values stored in t h
        //if $regExpItem = $match then just return its a straight match with no extractable wildcards .. return blank
        if ($regExpItem == $match) {
            return [];
        }elseif(preg_match($regExpItem, $match, $matches)){
            if($shift){
                array_shift($matches); //remove the first result
            }
            return $matches;
        }else{
            return []; //is this right //todo
        }
    }

    public static function convertToRegExpPattern($regexp):string
    {

        //handle consecutive wildcards first
        //if replacing direct from db
        $regexp = str_replace('% % ', '(\w+)\s(\w+)\s', $regexp);

        //if replacing after some other processing has taken place
        $regexp = str_replace('(.*) (.*) ', '(\w+)\s(\w+)\s', $regexp);

        $regexp = str_replace('%', '(.*)', $regexp);
        return '#'.$regexp.'#i';
    }






    public static function normalizeForCategoryTable($str = '', $allowedTags = []):string
    {

        //if empty return
        if (trim($str)=='') {
            return trim($str);
        }


        //some common replacements
        foreach (config('lemurbot.tag.commonNormalizations') as $in => $out) {
            //todo fix this at some point
            if($in!='/'){
                $str = str_replace($in, $out, $str);
            }
        }


        //remove sentence splitters
        foreach (config('lemurbot.tag.sentenceSplitters') as $splitter) {
            $str = str_replace($splitter, '', $str);

        }

        //strip any tags... except the allowed tags
        $str = strip_tags($str,$allowedTags);

        preg_match_all("~<[^/|^>]+>(.*?)</[^>]+>|<[^/>]+/>|[\pL0-9\^\*#\$_]+~ui",$str, $m);

        //trim
        $str = trim(implode(' ',$m[0]));

        return mb_strtoupper($str);
    }


    public static function replaceWildCardsInPattern($str = ''):string
    {

        //if empty return
        if (trim($str)=='') {
            return trim($str);
        }

        //these are the zero or more wildcards
        //so this "I want ^ noodles" will be replaced to "I want%noodles"
        //so that "i want spicy noodles" and "I want noodles" will be matched
        $str = str_replace(" ^ ", "%", $str);
        $str = str_replace(" # ", "%", $str);
        $str = str_replace("^ ", "%", $str);
        $str = str_replace("# ", "%", $str);
        $str = str_replace(" ^", "%", $str);
        $str = str_replace(" #", "%", $str);
        $str = str_replace("^", "%", $str);
        $str = str_replace("#", "%", $str);

        //these are one or more wildcards
        $str = str_replace("*", "%", $str);
        $str = str_replace("_", "%", $str);

        //this is for an exact match
        $str = str_replace("$", "", $str);

        //replace <tag>foobar</tag> with a %
        $str = preg_replace("~<[^/|^>]+>(.*?)</[^>]+>~i",'%',$str);
        //replace <tag /> with a %
        return preg_replace("~<[^/>]+/>~i",'%',$str);
    }



    public static function getFirstCharFromStr($str = ''):string
    {
        //if empty return
        if (trim($str) == '') {
            return trim($str);
        }
        return (string)$str[0];
    }


    //todo liz potentially remove
    public static function createRegExpFromString($str):string
    {

        //replace with one or more
        $str = str_replace("*", "(.*)", $str);
        $str = str_replace("_", "(.*)", $str);

        //replace with zero or more
        $str = str_replace("^", "(*+)", $str);
        $str = str_replace("#", "(*+)", $str);

        //remove
        return str_replace("$", "", $str);
    }

    public static function convertStrToRegExp($str):string
    {
        //these are one or more wildcards
        $str = str_replace("*", "(.*)", $str);
        $str = str_replace("_", "(.*)", $str);
        $str = str_replace(" ^ ", "(.*)?", $str);
        $str = str_replace(" # ", "(.*)?", $str);
        $str = str_replace("^ ", "(.*)?", $str);
        $str = str_replace("# ", "(.*)?", $str);
        $str = str_replace(" ^", "(.*)?", $str);
        $str = str_replace(" #", "(.*)?", $str);
        $str = str_replace("^", "(.*)?", $str);
        $str = str_replace("#", "(.*)?", $str);
        return str_replace(" (\\s", "(\\s", $str);
    }

    public static function cleanAndImplode($arr, $sourceTag=null): string
    {
        if (is_array($arr) && !empty($arr)) {
            $str = implode("", $arr);
        } elseif (is_string($arr)) {
            $str = $arr;
        } else {
            $str = '';
        }
        return self::cleanOutPutForResponse($str);
    }

    /**
     * remove multiple white
     *
     * @param $str
     * @return string
     */
    public static function cleanOutPutForResponse($str) :string
    {
        $str = str_replace(" !", "!", $str);
        $str = str_replace(" ?", "?", $str);
        $str = str_replace(" .", ".", $str);
        $str = str_replace(" ,", ",", $str);
        $str = str_replace(" :", ":", $str);
        $str = str_replace(" ;", ";", $str);
        $str = preg_replace('/\s+/', ' ', $str);

        return trim($str);
    }

    public static function replaceForSlug($str): string
    {
        $str = str_replace("*", "star", $str);
        $str = str_replace("#", "hash", $str);
        $str = str_replace("_", "uscore", $str);
        $str = str_replace("^", "hat", $str);
        return str_replace("$", "dollar", $str);
    }


    public static function removeTrailingKeepSpace($str): string
    {
        $str = rtrim($str, '_keepspace_');
        return ltrim($str, '_keepspace_');
    }

    public static function cleanForFinalOutput($str): string
    {

        $str = preg_replace('/\.([A-Z])/',". $1",$str);
        $str = preg_replace('/\?([A-Z])/',"? $1",$str);
        $str = preg_replace('/\!([A-Z])/',"1 $1",$str);
        $str = str_replace("_keepspace_", " ", $str);
        $str = str_replace("keepspace", " ", $str);
        $str = str_replace(", .", ". ", $str);
        $str = str_replace(", ,", ", ", $str);
        $str = str_replace(":", ": ", $str);
        $str = str_replace(": //", "://", $str);
        return trim(str_replace("  ", " ", $str));
    }

    public static function cleanKeepSpace($str): string
    {
        $str = str_replace("_keepspace_", " ", $str);
        $str = str_replace("keepspace", " ", $str);
        return trim($str);
    }

}
