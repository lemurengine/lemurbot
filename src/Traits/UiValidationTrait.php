<?php

namespace LemurEngine\LemurBot\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;

trait UiValidationTrait
{

    /**
    /**
     * Parse the validation rules for the front end
     *
     * @return string
     * @var array
     */
    public static function getFormValidation($fieldName)
    {

        $theRules =  self::$rules;
        $validationArr = [];

        if (!isset($theRules[$fieldName])) {
            return 'data-validation=""';
        }

        $theRuleParts = explode("|", $theRules[$fieldName]);

        foreach ($theRuleParts as $rule) {
            if ($rule=='required') {
                $validationArr[]='data-validation="required"';
            } elseif (strpos($rule, "max:")!==false) {
                $lengthParts = explode("max:", $rule);
                $validationArr[]='data-validation-length="max'.$lengthParts[1].'"';
            } elseif (strpos($rule, "min:")!==false) {
                $lengthParts = explode("min:", $rule);
                $validationArr[]='data-validation-length="min'.$lengthParts[1].'"';
            }
        }

        if (!empty($validationArr)) {
            return implode(" ", $validationArr);
        } else {
            return 'data-validation=""';
        }
    }
}
