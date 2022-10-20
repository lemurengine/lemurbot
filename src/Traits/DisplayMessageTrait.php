<?php
namespace LemurEngine\LemurBot\Traits;

use Web64\Colors\Facades\Colors;

trait DisplayMessageTrait
{

    /**
     * display a message to the console with colors
     *
     * @return bool
     */
    public function displayMessage($message, $type='info'):void{

        switch ($type) {
            case 'error':
                Colors::red($message);
                break;
            case 'success':
                Colors::green($message);
                break;
            case 'notice':
                Colors::yellow($message);
                break;
            case 'title':
                Colors::white("\n--------------------------------------\n$message\n--------------------------------------");
                break;
            default:
                Colors::white($message);
                break;
        }

    }
}
