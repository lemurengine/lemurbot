<?php

namespace LemurEngine\LemurBot\Services;

use Exception;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\BotUserRole;

class LemurPrivilegeService
{

    const BOT_ADMIM = "bot_admin";
    const BOT_AUTHOR = "bot_author";

    public function isAdmin($user){
        try{
            BotUserRole::where('user_id', $user->id)->where('role', self::BOT_ADMIM)->firstOrFail();
            return true;
        }catch (Exception $e){
            return false;
        }

    }

    public function isAuthor($user){
        try{
            BotUserRole::where('user_id', $user->id)->where('role', self::BOT_AUTHOR)->firstOrFail();
            return true;
        }catch (Exception $e){
            return false;
        }

    }

    public function getRole($userId){

        try{
            $botUserRole = BotUserRole::where('user_id', $userId)->firstOrFail();
            return $botUserRole->role;
        }catch (Exception $e){
            return false;
        }

    }

    public function assignRole($userId, $which){

        $privStr = ($which==='admin'?self::BOT_ADMIM:self::BOT_AUTHOR);

        return BotUserRole::firstOrCreate(['user_id' => $userId,
            'role' => $privStr]);


    }

}
