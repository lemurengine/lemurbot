<?php
namespace LemurEngine\LemurBot\LemurTag;

/**
 * Class EvalTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class EvalTag extends EvaluateTag
{
    protected string $tagName = "Eval";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;
}
