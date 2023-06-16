<?php

namespace LemurEngine\LemurBot\Classes;

use LemurEngine\LemurBot\Models\Conversation;

interface LemurPlugin
{

    public function __construct(Conversation $conversation, String $sentence);

    public function apply();

}
