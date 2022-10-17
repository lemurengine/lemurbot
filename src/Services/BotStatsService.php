<?php


namespace LemurEngine\LemurBot\Services;

use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Models\Turn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BotStatsService
{


    public function getAllTimeConversationStats($bot){
        return Conversation::where('bot_id',$bot->id)->count();
    }

    public function getAllTimeTurnStats($bot){
        return Turn::where('conversations.bot_id',$bot->id)
            ->where('turns.source', 'human')
            ->join('conversations', 'conversations.id', '=', 'turns.conversation_id')
            ->count();
    }

    public function getTodayConversationStats($bot){
        return Conversation::where('bot_id',$bot->id)
            ->whereDate('updated_at', '=', Carbon::now()->toDateString())
            ->count();
    }

    public function getTodayTurnStats($bot){
        return Turn::where('conversations.bot_id',$bot->id)
            ->join('conversations', 'conversations.id', '=', 'turns.conversation_id')
            ->whereDate('turns.created_at', '=', Carbon::now()->toDateString())
            ->where('turns.source', 'human')
            ->count();
    }

   public function getYearByMonthConversationStats($bot){
       $yearlyConversationStat = Conversation::selectRaw('year(updated_at) year, month(updated_at) month, count(*) data')
           ->where('bot_id',$bot->id)
           ->whereDate('updated_at', '>=', Carbon::now()->subMonths(11)->firstOfMonth()->toDateString())
           ->groupBy('year', 'month')
           ->orderBy('year', 'asc')
           ->orderBy('month', 'asc')
           ->get();

       return $this->fillYearByMonthData($yearlyConversationStat);

   }

    public function getYearByMonthTurnStats($bot){


        $yearlyTurnStat = Turn::selectRaw('year(turns.created_at) year, month(turns.created_at) month, count(*) data')
            ->join('conversations', 'conversations.id', '=', 'turns.conversation_id')
            ->where('conversations.bot_id',$bot->id)
            ->whereDate('turns.created_at', '>=', Carbon::now()->subMonths(11)->firstOfMonth()->toDateString())
            ->where('turns.source', 'human')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return $this->fillYearByMonthData($yearlyTurnStat);

    }

    public function getMonthByDayConversationStats($bot){
        $monthlyConversationStat = Conversation::selectRaw('day(updated_at) day, count(*) data')
            ->where('bot_id',$bot->id)
            ->whereDate('updated_at', '>=', Carbon::now()->firstOfMonth()->toDateString())
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->pluck('data','day');

        return $this->fillMonthByDayData($monthlyConversationStat);

    }

    public function getMonthByDayTurnStats($bot){
        $monthlyTurnStat = Turn::selectRaw('day(turns.created_at) day, count(*) data')
            ->join('conversations', 'conversations.id', '=', 'turns.conversation_id')
            ->where('conversations.bot_id',$bot->id)
            ->whereDate('turns.created_at', '>=', Carbon::now()->firstOfMonth()->toDateString())
            ->where('turns.source', 'human')
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->pluck('data','day');
        return $this->fillMonthByDayData($monthlyTurnStat);

    }

    public function getTurnByBotByConversationStats($botId, $fromDate, $toDate){
        return Turn::select(DB::raw('count(turns.id) as turns_total'),'bots.slug as bot_slug', 'bots.name as bot_name', 'clients.slug as client_slug', 'conversations.slug as conversation_slug', 'conversation_sources.referer', 'conversation_sources.ip')
            ->join('conversations', 'conversations.id', '=', 'turns.conversation_id')
            ->join('bots', 'bots.id', '=', 'conversations.bot_id')
            ->join('clients', 'clients.id', '=', 'conversations.client_id')
            ->join('conversation_sources', 'conversations.id', '=', 'conversation_sources.conversation_id')
            ->where('conversations.bot_id', $botId)
            ->where('turns.source', 'human')
            ->whereDate('turns.created_at', '>=', $fromDate )
            ->whereDate('turns.created_at', '<=', $toDate )
            ->groupBy('bots.id', 'clients.id', 'conversations.id', 'conversation_sources.id')
            ->get();
    }

    public function fillYearByMonthData($yearByMonthStat){

        $cleanDates=[];
        $cleanStatArr=[];

        //make the month the key (so that we can easily fill in the blanks
        $statArr = $yearByMonthStat->toArray();
        foreach($statArr as $stat){
            $cleanStatArr[$stat['month']]=$stat;
        }


        for($i=11;$i>=0;$i--){
            $month = ltrim(Carbon::now()->subMonths($i)->format('m'),0);
            $year = Carbon::now()->subMonths($i)->format('Y');
            if(isset($cleanStatArr[$month])){

                $clean["year"] =$cleanStatArr[$month]['year'];
                $clean["month"] =$cleanStatArr[$month]['month'];
                $clean["data"] =$cleanStatArr[$month]['data'];
            }else{
                $clean["year"] =$year;
                $clean["month"] =$month;
                $clean["data"] =0;
            }

            $cleanDates[]=$clean;
        }


        return $cleanDates;

    }

    public function getMonthsInYearKey(){

        $key=[];

        for($i=11;$i>=0;$i--){
            $month = Carbon::now()->subMonths($i)->format('M');
            $year = Carbon::now()->subMonths($i)->format('Y');

            $key[] =$month." ".$year;

        }

        return $key;

    }

   public function fillMonthByDayData($monthlyConversationStat){

       $cleanDates=[];
       $daysInMonth = Carbon::now()->daysInMonth;
       for($i=1;$i<=$daysInMonth;$i++){
           if(isset($monthlyConversationStat[$i])){
               $cleanDates[$i] =$monthlyConversationStat[$i];
           }else{
               $cleanDates[$i] =0;
           }
       }
       return $cleanDates;

   }

    public function getDaysInMonthKey(){

        $key=[];

        $daysInMonth = Carbon::now()->daysInMonth;

        for($i=1;$i<=$daysInMonth;$i++){

            $key[]=$i;

        }
        return $key;

    }
}
