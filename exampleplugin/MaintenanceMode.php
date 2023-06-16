<?php
namespace App\LemurPlugin;

use LemurEngine\LemurBot\Classes\LemurPlugin;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class MaintenanceMode
 *
 * example of a pre apply plugin
 * if linked to a bot this plugin will return the apply phrase
 *
 * If you want to use this example then you will have to add the following values to the plugins table (/plugins/create)
 *
 * 'title' => "Maintenance Mode",
 * 'description' => "An example plugin that makes your bot respond with a maintenance message",
 * 'classname' => 'MaintenanceMode',
 * 'apply_plugin' => 'pre',
 * 'return_onchange' => true,
 * 'is_master' => true,
 * 'is_active' => true,
 * 'priority' => 1
 *
 */
class MaintenanceMode implements LemurPlugin
{

    protected $conversation;
    protected $sentence;
    protected $botname;

    /**
     * @param Conversation $conversation
     * @param String $sentence
     */
    public function __construct(Conversation $conversation, String $sentence)
    {
        $this->conversation = $conversation;
        $this->sentence = $sentence;
        $this->botname = $conversation->getBotPropertyValue('name');
    }

    public function apply(){

        return "Sorry ".$this->botname." is in maintenance mode";

    }
}
