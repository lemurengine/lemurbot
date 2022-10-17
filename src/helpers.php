<?php

use LemurEngine\LemurBot\Models\Bot;

if(!function_exists('getEloquentSqlWithBindings')){
    /**
     * Combines SQL and its bindings
     *
     * @return string
     */
    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            $binding = addslashes($binding);
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }
}

if(!function_exists('getHomePageBot')){
    /**
     * Gets the homepage bot
     * If the LEMUR_ENGINE_HOMEPAGE_BOT is not set in the .env
     * then we will just pick the first public bot
     *
     * @param $query
     * @return string
     */
    function getHomePageBot():string
    {
       if(!config('lemurbot::lemur.homepage_bot')){
           $bot = Bot::where('is_public', 1)->first();
           if($bot === null){
               return '';
           }else{
               return $bot->slug;
           }
       }


    }
}
