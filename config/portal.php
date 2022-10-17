<?php
return [
    /*
    |--------------------------------------------------------------------------
    | DEFAULT SETTINGS
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Show Detailed Error Message
    |--------------------------------------------------------------------------
    |
    | When this is set to true, the admin users will see a more detailed error message
    | e.g. detailed SQL errors
    | This is not recommended as it could display more information than you require to your admin users
    | But it can be useful when it is hard for admin users to access logs
    | All detailed error message are hidden from non-admins
    |
    | true or false
    */

    'show_detailed_error_messages' => env('LEMUR_ENGINE_ADMIN_ERRORS', false),

    /*
    |--------------------------------------------------------------------------
    | Avatar URL
    |--------------------------------------------------------------------------
    |
    | Displayed in the bot admin
    |
    | true or false
    */

    'lemurtar_url' => env('LEMUR_ENGINE_AVATAR_URL', 'https://lemurtar.com'),

    /*
    |--------------------------------------------------------------------------
    | Homepage Image
    |--------------------------------------------------------------------------
    |
    | Location of the homepage image
    |
    */
    'homepage_image' => env('LEMUR_ENGINE_HOMEPAGE_IMAGE', 'lemur-med.png'),


    /*
    |--------------------------------------------------------------------------
    | Max User Input
    |--------------------------------------------------------------------------
    |
    | Set a maximum amount of chars that the user is allowed to send in a single input
    |
    */

    'max_chars' => env('LEMUR_ENGINE_MAX_CHARS', 255),

    /*
    |--------------------------------------------------------------------------
    | Default Bot Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'default_bot_image'  => env('LEMUR_ENGINE_DEFAULT_BOT_IMAGE', 'widgets/robot.png'),

    /*
    |--------------------------------------------------------------------------
    | Default Client Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'default_client_image' => env('LEMUR_ENGINE_DEFAULT_CLIENT_IMAGE', 'widgets/user.png'),

    /*
    |--------------------------------------------------------------------------
    | Default Client Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'homepage_bot' => env('LEMUR_ENGINE_HOMEPAGE_BOT', FALSE),
];
