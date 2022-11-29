<?php

namespace LemurEngine\LemurBot\Factories;

use Illuminate\Support\Str;
use LemurEngine\LemurBot\Classes\AimlMatcher;
use LemurEngine\LemurBot\Classes\AimlParser;
use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Exceptions\TagLoadingException;
use LemurEngine\LemurBot\Exceptions\TagNotFoundException;
use LemurEngine\LemurBot\LemurTag\AimlTagInterface;
use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Services\TalkService;
use LemurEngine\LemurBot\LemurTag\AimlTag;
use Error;
use Exception;

class AimlTagFactory
{

    public static function getTagClass($tagName) {

        $tagName ='\\'.$tagName.'Tag';
        $classes = include base_path('vendor').'/composer/autoload_classmap.php';

        LemurLog::info(
            'Looking for TagClass: '.$tagName
        );

        foreach ($classes as $classname => $filepath){
            if(!str_contains($classname, 'Test') && Str::endsWith(strtolower($classname),strtolower($tagName))){
                return $classname;
            }
        }

        return false;
    }

    /**
     * @param Conversation $conversation
     * @param string $tagName
     * @param array $attributes
     *
     * @return AimlTagInterface $tag
     * @throws TagNotFoundException
     */
    public static function create(Conversation $conversation, string $tagName = '', array $attributes = [])
    {

        try {

            $currentTagClass = self::getTagClass($tagName);


            if(!$currentTagClass || !class_exists($currentTagClass)){
                LemurLog::error(
                    'TagClass cannot be found: '.class_basename($currentTagClass),
                    [
                        'conversation_id' => $conversation->id,
                        'turn_id' => $conversation->currentTurnId(),
                        'tag_name' => $tagName,
                    ]
                );
                Throw New TagNotFoundException("Error finding tag: $tagName");
            }elseif(!method_exists($currentTagClass, 'getAimlTagType')){
                LemurLog::error(
                    'TagClass cannot be loaded: '.class_basename($currentTagClass),
                    [
                        'conversation_id' => $conversation->id,
                        'turn_id' => $conversation->currentTurnId(),
                        'tag_name' => $tagName,
                    ]
                );
                Throw New TagLoadingException("Error loading tag: ".$tagName);
            }

            $aimlTagType = $currentTagClass::getAimlTagType();

            if ($aimlTagType === AimlTag::TAG_RECURSIVE) {  //if this is a HTML tag......
                LemurLog::info(
                    'Loading recursive tag',
                    [
                        'conversation_id' => $conversation->id,
                        'turn_id' => $conversation->currentTurnId(),
                        'tag_name' => $tagName,
                    ]
                );
                $talkService = new TalkService(config('lemurbot.tag'), new AimlMatcher(), new AimlParser());
                $tag = new $currentTagClass($talkService, $conversation, $attributes);

            } else{  //if this is a HTML/STANDARD tag......
                LemurLog::info(
                    "Loading {$aimlTagType} tag",
                    [
                        'conversation_id' => $conversation->id,
                        'turn_id' => $conversation->currentTurnId(),
                        'tag_name' => $tagName,
                    ]
                );
                $tag = new $currentTagClass($conversation, $attributes);
            }

            if(!$tag instanceof AimlTagInterface) {
                LemurLog::error(
                    'TagClass is not an AIML tag: '.class_basename($currentTagClass),
                    [
                        'conversation_id' => $conversation->id,
                        'turn_id' => $conversation->currentTurnId(),
                        'tag_name' => $tagName,
                    ]
                );
                Throw New TagLoadingException("Not an AIML tag: $tagName");
            }
            return $tag;
        }catch(TagNotFoundException $e){
            Throw New TagNotFoundException($e->getMessage());
        }catch(Exception | Error $e){
            Throw New TagLoadingException($e->getMessage());
        }
    }

}
