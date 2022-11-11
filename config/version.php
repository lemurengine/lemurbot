<?php

return [
    /*
    |--------------------------------------------------------------------------
    | THE CURRENT RELEASE VERSION
    |--------------------------------------------------------------------------
    |
    */
    'portal' =>
        ['id' => '9.0.5'],
    'bot' =>
        ['id' => '9.0.5'],
    'release' => [
        'version' => '9.0.5',
        'name' => 'Operation',
        'description' => 'Fixing more paths for the people using s3.
        If you have already installed the LemurBots package are upgrading from >9.0.0 <=9.0.3 then please perform the additional steps;
         1. delete the public/widgets folder
         2. delete the public/avatars folder
         3. run the following commands
            php artisan vendor:publish --tag=lemurbot-config --force
            php artisan vendor:publish --tag=lemurbot-assets --force
            php artisan vendor:publish --tag=lemurbot-widgets --force
            php artisan storage:link',
        'url' => 'https://github.com/lemurengine/lemurbot/releases/tag/9.0.5',
    ],

];
