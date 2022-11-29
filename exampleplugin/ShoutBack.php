<?php
namespace App\LemurPlugin;

use LemurEngine\LemurBot\Classes\LemurPlugin;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ShoutingMode
 *
 * example of a post apply plugin
 * if linked to a bot this plugin will shout (capitalize) the response back
 *
 * If you want to use this example then you will have to add the following values to the plugins table (/plugins/create)
 *
 * 'title' => "Shout Back",
 * 'description' => "An example plugin that makes your bot shout back the response",
 * 'classname' => 'ShoutBack',
 * 'apply_plugin' => 'post',
 * 'return_onchange' => true,
 * 'is_master' => true,
 * 'is_active' => true,
 * 'priority' => 1
 *
 */
class ShoutBack implements LemurPlugin
{

    protected $conversation;
    protected $sentence;

    /**
     * @param Conversation $conversation
     * @param String $sentence
     */
    public function __construct(Conversation $conversation, String $sentence)
    {
        $this->conversation = $conversation;
        $this->sentence = $sentence;
    }

    public function apply(){

        if($this->sentence==''){
            $this->sentence = 'nothing';
        }
        return strtoupper($this->sentence);

    }
}
