<?php

namespace LemurEngine\LemurBot\Classes;

use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\BotCategoryGroup;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Models\Client;
use LemurEngine\LemurBot\Models\ClientCategory;
use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Models\SetValue;
use LemurEngine\LemurBot\Models\Turn;

class AimlMatcher
{

    protected Conversation $conversation;
    protected Turn $currentTurn;
    protected $bot;
    protected $client;

    public function setConversationParts(Conversation $conversation)
    {
        $this->conversation = $conversation;
        $this->currentTurn = $this->conversation->currentConversationTurn;
        $this->bot = $this->conversation->bot;
        $this->client = $this->conversation->client;
    }

    public function replaceInputTags($categories){


        foreach ($categories as $category) {

            $pattern = LemurStr::convertStrToRegExp($category->pattern);
            $pattern = $this->replaceSets($pattern, 'set', $this->conversation->bot);
            $pattern = $this->replaceBotProperty($pattern, $this->conversation->bot);


            if ($pattern != $category->pattern) {
                $this->conversation->debug(
                    'filtering.pattern.replaced.' . $category->slug,
                    $pattern
                );

                $category->fill(['regexp_pattern' => $pattern]);
            }

            $topic = LemurStr::convertStrToRegExp($category->topic);
            $topic = $this->replaceSets($topic, 'set', $this->conversation->bot);

            if($topic!=$category->topic){
                $this->conversation->debug(
                    'filtering.topic.replaced.'.$category->slug,
                    $topic
                );
                $category->fill(['regexp_topic' => $topic]);
            }


            $that = LemurStr::convertStrToRegExp($category->that);
            $that = $this->replaceSets($that, 'set', $this->conversation->bot);
            $that = $this->replaceBotProperty($that, $this->conversation->bot);

            if($that!=$category->that){
                $this->conversation->debug(
                    'filtering.that.replaced.'.$category->slug,
                    $that
                );
                $category->fill(['regexp_that' => $that]);
            }
        }

        return $categories;
    }

    /**
     * filter categories by removing the ones which are obv. wrong
     * @param $categories
     * @return mixed
     */
    public function filter($categories): mixed
    {

        $totalPotentials = $categories->count();
        $inputNormalised = $this->conversation->normalisedInput();
        $pluginTransformedInputNormalised = $this->conversation->normalisedPluginTransformedInput();
        $topicNormalised = $this->conversation->normalisedTopic();
        $thatNormalised = $this->conversation->normalisedThat();

        $this->conversation->debug('categories.filtering.result', $totalPotentials);
        $this->conversation->debug('categories.filtering.input', $inputNormalised);
        $this->conversation->debug('categories.matches.parts.plugin.input', $pluginTransformedInputNormalised);
        $this->conversation->debug('categories.filtering.topic', $topicNormalised);
        $this->conversation->debug('categories.filtering.that', $thatNormalised);


        //there is only one category so lets return this...
        if ($totalPotentials<1) {
            return false;
        } elseif ($totalPotentials==1) {
            return $categories->first();
        }

        /**
         * $key = $c->search(function($item) {
        return $item->id == 2;
        });

        $c->pull($key);
         */

        foreach ($categories as $category) {
            $removed = false;

            $pattern = $category->regexp_pattern;

            $pattern = str_replace("\B","\b", $pattern);

            //if this is NOT a exact match.... and the regexp does not match
            if ($pattern!=='$'.$pluginTransformedInputNormalised &&
                !preg_match_all("~^" . $pattern . "$~is", $pluginTransformedInputNormalised, $matches)) {
                    $categories = $this->removeFromCollection($categories, $category->id);
                    $removed=true;
                    $this->conversation->debug(
                        'filtering.result.removed.'.$category->slug,
                        'Patterns do not match - '.$pattern
                    );
            }

            if (!$removed && !empty($category->topic)) {
                $topic = $category->regexp_topic;


                if (!preg_match_all("~^" . $topic . "$~is", $topicNormalised, $matches)) {
                    $categories = $this->removeFromCollection($categories, $category->id);
                    $removed=true;
                    $this->conversation->debug(
                        'filtering.result.removed.'.$category->slug,
                        'Topics do not match - '.$topic
                    );
                }
            }

            if (!$removed && !empty($category->that)) {

                $that = $category->regexp_that;



                if (!preg_match_all("~^" . $that . "$~is", $thatNormalised, $matches)) {
                    $categories = $this->removeFromCollection($categories, $category->id);
                    $this->conversation->debug(
                        'filtering.result.removed.'.$category->slug,
                        'Thats do not match - '.$that
                    );
                }
            }
        }

        return $categories;
    }


    public function replaceSets($str, $tag, $bot)
    {

        $data = $this->extractDataBetweenTags($str, $tag);
        if (!empty($data)) {
            foreach ($data[2] as $setName) {
                $setName = strtolower($setName);

                $setValues = SetValue::select('set_values.value')
                    ->join('sets', 'sets.id', '=', 'set_values.set_id')
                    ->where(function ($query) use ($bot) {
                        $query->where('sets.user_id', $bot->user_id)
                            ->orWhere('sets.is_master', 1);
                    })
                    ->whereRaw('lower(sets.name) = (?)', [$setName])->pluck('set_values.value')->toArray();

                if (!empty($setValues)) {
                    $str = preg_replace(
                        "'<$tag\s*(.*)\>$setName</$tag>'si",
                        "\b(". implode('|', $setValues) . ")\b",
                        $str
                    );
                }
            }
        }

        return $str;
    }

    public function replaceBotProperty($str, $bot)
    {

       preg_match_all("~<[^/>]+/>~i",$str,$m);
       if(!empty($m[0])) {

           foreach ($m[0] as $botPropertyTag) {

               preg_match('~"(.*)"~i', $botPropertyTag, $name);

               $propertyName = strtolower($name[1]);

               $property = $bot->botPropertyByName($propertyName);

               if (!empty($property)) {
                   $str = str_replace($m[0], $property->value, $str);
               }
           }


       }


        return $str;
    }


    public function extractDataBetweenTags($str, $tag)
    {

        preg_match_all("~<$tag\s*(.*)\>(.*?)</$tag>~si", $str, $matches);
        return (($matches[0])?$matches:false);
    }

    /**
     * @param $categories
     * @param $id
     * @return mixed
     */
    public function removeFromCollection($categories, $id)
    {

        $key = $categories->search(function ($item) use ($id) {
            return $item->id == $id;
        });
        $categories->pull($key);
        return $categories;
    }


    /**
     * score all the categories
     * @param $categories
     * @return mixed
     */
    public function score($categories)
    {

        LemurLog::debug(
            'score',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'categoryCounts'=>count($categories),
            ]
        );

        $totalPotentials = $categories->count();
        $inputNormalised = $this->conversation->normalisedInput();
        $pluginTransformedInputNormalised = $this->conversation->normalisedPluginTransformedInput();
        $topicNormalised = $this->conversation->normalisedTopic();
        $thatNormalised = $this->conversation->normalisedThat();

        //some debug to help us work things out
        $this->conversation->debug('categories.matches.parts.potentials', $totalPotentials);
        $this->conversation->debug('categories.matches.parts.input', $inputNormalised);
        $this->conversation->debug('categories.matches.parts.plugin.input', $pluginTransformedInputNormalised);
        $this->conversation->debug('categories.matches.parts.topic', $topicNormalised);
        $this->conversation->debug('categories.matches.parts.that', $thatNormalised);

        //there is only one category so lets return this...
        if ($totalPotentials==1) {
            return $categories->first();
        }

        $scoreArray =[];
        foreach ($categories as $category) {
            $i = $category->id;

            $scoreArray[$i]['category_id'] = $i;
            $scoreArray[$i]['id'] = $category->slug;
            $scoreArray[$i]['pattern'] = $category->pattern;
            $scoreArray[$i]['that'] = $category->that;
            $scoreArray[$i]['topic'] = $category->topic;
            $scoreArray[$i]['total'] = 0;

            //--------------------------------------------------------------
            //FIRST IF THIS IS JUST A STAR WITH NO TOPIC AND NO THAT
            //--------------------------------------------------------------
            //now there are some special cases...
            //if the pattern is = * and there is no that and no topic then this is the default category ...
            //we should only use this if nothing else exists...
            if (($category->pattern=='*') && ($category->that=='') && ($category->topic=='')) {
                $scoreArray[$i]['total'] = 0;
                $scoreArray[$i]['messages'][]='This is a default category - '.$category->that;
                continue;
            }


            if ($category->pattern === $pluginTransformedInputNormalised || preg_match('/^'.$category->regexp_pattern."$/i", $pluginTransformedInputNormalised, $matches) ) {
                $points = 2;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$category->pattern .'==='. $pluginTransformedInputNormalised;
                $scoreArray[$i]['messages'][]=$points.' points added for exact input match';
            } else {
                $scoreArray[$i]['messages'][]='no points not exact input match';
            }

            if ($category->topic==='') {
                $points = 20;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points added for empty topic match (both items are empty)';
            } elseif ( $category->topic === $topicNormalised ||
                            preg_match('/^'.$category->regexp_topic."$/i", $topicNormalised, $matches) ) {
                $points = 30;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$category->topic .'==='. $topicNormalised;
                $scoreArray[$i]['messages'][]=$points.' points added for exact actual topic match
                                                    (both items are  NOT empty and they are the SAME)';
            } else {
                $scoreArray[$i]['messages'][]='no points topic does not match and the category topic is not empty
                                                    (\''.$category->topic.'\' & \''.$topicNormalised.'\')';
            }


            if ($category->that==='') {
                $points = 20;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points added for empty that match (both items are empty)';
            } elseif ( $category->that === $thatNormalised ||
                            preg_match('/^'.$category->regexp_that."$/i", $thatNormalised, $matches)) {
                $points = 30;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$category->that .'==='. $thatNormalised;
                $scoreArray[$i]['messages'][]=$points.' points added for exact actual that match
                                                    (both items are  NOT empty and they are the SAME)';
            } else {
                $scoreArray[$i]['messages'][]='no points that does not match and the category that is not empty
                                                    (\''.$category->that.'\' & \''.$thatNormalised.'\')';
            }

            //--------------------------------------------------------------
            //WILDCARD MATCHING ...
            //--------------------------------------------------------------

            //# wildcard
            //one or more of these wildcards
            $count = substr_count($category->pattern, '#');
            if ($count>0) {
                $points = $count*6;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points added for '.$count.' # match(es)';
            }

            //_ wildcard
            //at least one these wildcards
            $count = substr_count($category->pattern, '_');
            if ($count>0) {
                //this has to be higher than an exact match
                $points = $count*5;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points added for '.$count.' _ match(es)';
            }

            //count the number of words in the string so that 'hello there *' will outperform 'hello *'
            $wordCount = str_word_count($category->pattern, 0, '*#^_');

            $scoreArray[$i]['total'] = $this->addScoreToCategory($wordCount, $scoreArray[$i]);
            $scoreArray[$i]['messages'][]=$wordCount.' points added for '.$wordCount.' tokens in string';

            //^ wildcard
            //one or more of these wildcards
            //this has a lower priority than an exact match
            $count = substr_count($category->pattern, '^');
            if ($count>0) {
                //this has to be higher than an exact match
                $points = $count*1;
                $scoreArray[$i]['total'] = $this->subtractScoreFromCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points subtracted for '.$count.' ^ match(es)';
            }

            //* wildcard
            //this has a lower priority than an exact match
            $count = substr_count($category->pattern, '*');
            if ($count>0) {
                //this has to be higher than an exact match
                $points = $count*2;
                $scoreArray[$i]['total'] = $this->subtractScoreFromCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points subtracted for '.$count.' * match(es)';
            }

            //--------------------------------------------------------------
            //FINALLY IF THIS IS AN EXACT MATCH THIS WILL WIN ABOVE ALL
            //--------------------------------------------------------------
            //$ Exact pattern match
            if ($category->pattern[0]=='$') {
                //this is the highest priority
                $points = 100;
                $scoreArray[$i]['total'] = $this->addScoreToCategory($points, $scoreArray[$i]);
                $scoreArray[$i]['messages'][]=$points.' points added $exact match';
            }
        }
        $this->conversation->debug('categories.matches.scores', $scoreArray);

        //we have big problems there should always be at least 1

        if(empty($scoreArray)){
            return false;
        }

        $totalScore = max(array_column($scoreArray, 'total'));

        $allTopScoreCategories = [];

        foreach ($scoreArray as $categoryId => $values) {
            if ($values['total']===$totalScore) {
                $allTopScoreCategories[$categoryId]=$scoreArray[$categoryId];
            }
        }

        //sort by id - as we want the newest added category (according to the aiml standard)
        sort($allTopScoreCategories);
        //return the
        $item = end($allTopScoreCategories);

        $this->conversation->debug('categories.matches.top.score', $item);

        LemurLog::debug(
            'score end',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'categoryCounts'=>count($categories),
            ]
        );


        return $categories->find($item['category_id']);
    }


    public function addScoreToCategory($score, $scoreArr)
    {

        if (!empty($scoreArr['total'])) {
            return (int)$scoreArr['total']+(int)$score;
        }

        return (int)$score;
    }

    public function subtractScoreFromCategory($score, $scoreArr)
    {

        if (!empty($scoreArr['total'])) {
            return (int)$scoreArr['total']-(int)$score;
        }

        return (int)$score;
    }


    public function getListOfAllowedCategoryGroups(){

        //the bot has a user id
        $botId = $this->bot->id;
        $botAuthorId = $this->bot->user_id;

        return BotCategoryGroup::join('category_groups', 'category_groups.id','=','bot_category_groups.category_group_id')
            ->where('bot_id',$botId)
            ->where('category_groups.status', 'A')
            ->where(function ($query)  use($botAuthorId) {
                //it has to be owned by the bot author or be a master record
                $query->where('category_groups.user_id',$botAuthorId)
                    ->orWhere('category_groups.is_master', 1);
            })->pluck('category_groups.id')->toArray();

    }

    /**
     * match the pattern
     * @param $preparedSentence
     * @return mixed
     */
    public function match($preparedSentence)
    {
        LemurLog::debug(
            'match',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'preparedSentence'=>$preparedSentence,
            ]
        );

        $allowedCategoryGroupIds = $this->getListOfAllowedCategoryGroups();

        $topicNormalised = $this->conversation->normalisedTopic();
        $thatNormalised = $this->conversation->normalisedThat();


        $this->conversation->flow('searching_categories_sentence_pattern', $preparedSentence);
        $this->conversation->flow('searching_categories_topic', $topicNormalised);
        $this->conversation->flow('searching_categories_that', $thatNormalised);

        //if this is a single word....
        /**
         * we are searching for a matching pattern we do this by doing a regex search on pattern/topic/that
         * and refine it by limiting the the first_letter_*
         *
         * pattern: I REMAINED *
         * regexp_pattern: I REMAINED %
         * first_letter_pattern: I
         * topic:
         * regexp_topic:
         * first_letter_topic:
         * that:
         * regexp_that:
         * first_letter_that:
         *
         */


        $sqlBuilder = Category::select(
            'categories.id',
            'categories.slug',
            'categories.pattern',
            'categories.regexp_pattern',
            'categories.first_letter_pattern',
            'categories.topic',
            'categories.regexp_topic',
            'categories.first_letter_topic',
            'categories.that',
            'categories.regexp_that',
            'categories.first_letter_that',
            'categories.template',
            'category_groups.name as category_group_name',
            'category_groups.slug as category_group_slug'
        )
            ->whereIn('category_group_id', $allowedCategoryGroupIds)
            ->join('category_groups', 'category_groups.id', '=', 'categories.category_group_id')
            ->where('categories.status', 'A');


        //is this sentence with multiple words?
        if (strpos($preparedSentence, ' ') !== false) {
            $sqlBuilder = $sqlBuilder->where(function ($query) use ($preparedSentence) {
                $query->where('regexp_pattern', $preparedSentence)
                    ->orWhereRaw("'$preparedSentence' LIKE `regexp_pattern`")
                    ->orWhere('regexp_pattern', '%')
                    ->where(function ($query) use ($preparedSentence) {
                        $query->orWhere(function ($query) use ($preparedSentence) {
                            $query->where('first_letter_pattern', $preparedSentence[0])
                                ->where('regexp_pattern', 'LIKE', $preparedSentence[0] . '%')
                                ->whereRaw("'$preparedSentence' LIKE `regexp_pattern`");
                        })
                            ->orWhere(function ($query) use ($preparedSentence) {
                                $query->where('first_letter_pattern', '%')
                                    ->where('regexp_pattern', 'LIKE', '\%%')
                                    ->whereRaw("'$preparedSentence' LIKE `regexp_pattern`");
                            });
                    });
            });
        } else {
            $sqlBuilder = $sqlBuilder->where(function ($query) use ($preparedSentence) {
                $query->where('regexp_pattern', '%')
                    ->orWhere(function ($query) use ($preparedSentence) {
                        $query->where('regexp_pattern', $preparedSentence)
                            ->orWhere('regexp_pattern', '%')
                            ->orWhereRaw("'$preparedSentence' LIKE `regexp_pattern`");
                    });
            });
        }

        //is there a set that?
        if ($thatNormalised != '') {
            $sqlBuilder = $sqlBuilder->where(function ($query) use ($preparedSentence, $thatNormalised) {
                $query->where('regexp_that', '')
                    ->orWhere('that', '')
                    ->orWhere('regexp_that', $thatNormalised)
                    ->orWhere(function ($query) use ($thatNormalised) {
                        $query->where('first_letter_that', $thatNormalised[0])
                            ->where('regexp_that', 'LIKE', $thatNormalised[0] . '%')
                            ->whereRaw("'$thatNormalised' LIKE `regexp_that`");
                    })
                    ->orWhere(function ($query) use ($preparedSentence, $thatNormalised) {
                        $query->where('first_letter_that', '%')
                            ->where('regexp_that', 'LIKE', '\%%')
                            ->whereRaw("'$preparedSentence' LIKE `regexp_that`");
                    });
            });
        } else {
            $sqlBuilder = $sqlBuilder->where('that', '');
        }

        //is there a set topic?
        if ($topicNormalised != '') {
            $sqlBuilder = $sqlBuilder->where(function ($query) use ($preparedSentence, $topicNormalised) {
                $query->where('regexp_topic', '')
                    ->orWhere('topic', '')
                    ->orWhere('regexp_topic', $topicNormalised)
                    ->orWhere(function ($query) use ($topicNormalised) {
                        $query->where('first_letter_topic', $topicNormalised[0])
                            ->where('regexp_topic', 'LIKE', $topicNormalised[0] . '%')
                            ->whereRaw("'$topicNormalised' LIKE `regexp_topic`");
                    })
                    ->orWhere(function ($query) use ($preparedSentence, $topicNormalised) {
                        $query->where('first_letter_topic', '%')
                            ->where('regexp_topic', 'LIKE', '\%%')
                            ->whereRaw("'$preparedSentence' LIKE `regexp_topic`");
                    });
            });
        } else {
            $sqlBuilder = $sqlBuilder->where('topic', '');
        }

        LemurLog::debug('Potential categories found', [
            'conversation_id'=>$this->conversation->id,
            'turn_id'=>$this->currentTurn->id,
            'matched'=>$sqlBuilder->count(),
            'results'=>$sqlBuilder->get()->toArray()]);


        $this->conversation->setAdminDebug('sql', getEloquentSqlWithBindings($sqlBuilder));

        LemurLog::debug(
            'match end',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'preparedSentence'=>$preparedSentence,
            ]
        );


        return $sqlBuilder->get();
    }


    /**
     * match the pattern
     * @param $preparedSentence
     * @return mixed
     */
    public function matchClientCategory($preparedSentence)
    {


        $botId = $this->bot->id;
        $clientId = $this->client->id;

        $clientCategory = ClientCategory::where('pattern', $preparedSentence)
                ->where('bot_id', $botId)->where('client_id', $clientId)->first();
        if ($clientCategory!=null) {
            return $clientCategory->template;
        } else {
            return false;
        }
    }
}
