<?php
namespace LemurEngine\LemurBot\LemurTag;


Interface AimlTagInterface
{

    public static function getAimlTagType();

    public function setAttributes(array $attributes);

    public function setAttribute(string $attribute, $value);

    public function getAttributes(): array;

    public function hasAttributes(): bool;

    public function checkAttribute($attribute_name, $value);

    public function getAttribute($attribute_name, $default = '');

    public function hasAttribute($attribute_name);

    public function openTag($tagSettings);

    public function closeTag();

    public function processContents($contents);

    public function getTagContentsCompact();

    public function getTagStack();

    public function getUnknownValueStr($field);
}
