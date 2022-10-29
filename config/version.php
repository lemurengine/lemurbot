<?php

return [
    /*
    |--------------------------------------------------------------------------
    | THE CURRENT RELEASE VERSION
    |--------------------------------------------------------------------------
    |
    */
    'portal' =>
        ['id' => '9.0.4'],
    'bot' =>
        ['id' => '9.0.4'],
    'release' => [
        'version' => '9.0.4',
        'name' => 'Operation',
        'description' => 'Fix paths and symlinks. This allows for the management of custom widgets.
        If you are upgrading from >9.0.3 to this version then please;
         1. delete the public/widgets folder
         2. delete the public/avatars folder
         3. run the following commands
            php artisan vendor:publish --tag=lemurbot-config --force
            php artisan vendor:publish --tag=lemurbot-assets --force
            php artisan vendor:publish --tag=lemurbot-widgets --force
            php artisan storage:link',
        'url' => 'https://github.com/lemurengine/lemurbot/releases/tag/9.0.3',
    ],

];
