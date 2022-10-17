<?php

namespace LemurEngine\LemurBot\Classes;

use Illuminate\Support\Facades\Log;

class LemurLog
{

    public static function sql($sql, $bindings, $time)
    {

        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$sql;
        $contextArr['bindings']=$bindings;
        $contextArr['time']=$time;
        $contextArr['info']=self::extractAllInfo($calling);

        Log::info('', $contextArr);

        self::display($contextArr);
    }


    public static function info($message, $context = [])
    {

        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractAllInfo($calling);

        Log::info('', $contextArr);

        self::display($contextArr);
    }

    public static function debug($message, $context = [])
    {


        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractAllInfo($calling);

        Log::debug('', $contextArr);

        self::display($contextArr);
    }

    public static function warn($message, $context = [])
    {


        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractAllInfo($calling);

        Log::warning('', $contextArr);

        self::display($contextArr);
    }

    public static function error($message, $context = [])
    {


        $calling=debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 5);

        $contextArr['message']=$message;
        $contextArr['context']=$context;
        $contextArr['info']=self::extractAllInfo($calling);

        Log::error('', $contextArr);

        self::display($contextArr);
    }


    public static function extractAllInfo($calling):array
    {
        $d=[];
        $i=0;
        $d = self::extractInfo($d, $i, $calling);
        $i=1;
        return self::extractInfo($d, $i, $calling);
    }

    public static function extractInfo($d, $i, $calling):array
    {
        if (isset($calling[$i]['file'])) {
            $d[$i]['file']=basename($calling[$i]['file']);
            $d[$i]['line']=$calling[$i]['line'];
        } elseif (isset($calling[$i]['function'])) {
            $d[$i]['function']=$calling[$i]['function'];
            $d[$i]['class']=$calling[$i]['class'];
        }
        return $d;
    }


    public static function display($contextArr)
    {

        /*echo "<pre>";
        print_r($contextArr);
        echo "</pre>";
*/
    }
}
