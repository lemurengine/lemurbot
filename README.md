[<img src="https://docs.lemurengine.com/assets/images/lemur-med.png" width="100"/>](https://docs.lemurengine.com/assets/images/lemur-med.png)
# lemurengine/lemurbot


An AIML chatbot written for the Laravel Framework. Add a bot to your website and administer it from the portal.

### Demo
http://lemurengine.com

### Docs
http://docs.lemurengine.com

### Screenshots

#### Frontend Animated Widget
[<img src="https://docs.lemurengine.com/assets/images/screenshots/demo1.png" width="150"/>](https://docs.lemurengine.com/assets/images/screenshots/demo1.png)

#### Frontend Popup Widget
[<img src="https://docs.lemurengine.com/assets/images/screenshots/demo2.png" width="150"/>](https://docs.lemurengine.com/assets/images/screenshots/demo2.png)

#### Admin Dashboard
[<img src="https://docs.lemurengine.com/assets/images/screenshots/dashboard.png" width="250"/>](https://docs.lemurengine.com/assets/images/screenshots/dashboard.png)

#### Admin Portal Chat
[<img src="https://docs.lemurengine.com/assets/images/screenshots/portalchat.png" width="250"/>](https://docs.lemurengine.com/assets/images/screenshots/portalchat.png)

#### Admin Bot Properties
[<img src="https://docs.lemurengine.com/assets/images/screenshots/properties.png" width="250"/>](https://docs.lemurengine.com/assets/images/screenshots/properties.png)

#### Admin Link Bot Knowledge
[<img src="https://docs.lemurengine.com/assets/images/screenshots/knowledge.png" width="250"/>](https://docs.lemurengine.com/assets/images/screenshots/knowledge.png)

#### Admin Create Bot Knowledge
[<img src="https://docs.lemurengine.com/assets/images/screenshots/addcategory.png" width="250"/>](https://docs.lemurengine.com/assets/images/screenshots/addcategory.png)

#### Admin Bot Stats
[<img src="https://docs.lemurengine.com/assets/images/screenshots/stats.png" width="250"/>](https://docs.lemurengine.com/assets/images/screenshots/stats.png)

## Versioning
This package is built for Laravel 9.x \
Compatible Laravel versions are reflected in the LemurBot versions. \
LemurBot 9.x versions are compatible with Laravel 9.x versions

## Migrating From theramenrobotdiscocode/lemur-engine
The original version of this library was released as an entire application which included a checked in version of Laravel 7.\
It is easy to migrate your original version of theramenrobotdiscocode/lemur-engine to this version.\
There are NO database schema changes just some minor data changes and file location changes.\
We suggest you install new fresh copy of Laravel 9 and configure it to connect to your existing database. \
You will need to still follow the fresh installation instructions below. \
And anyway "Additional Upgrade Steps" as well.

## Fresh Installation
LemurEngine LemurBots is written for the Laravel framework.\
The first step is to install and setup your version of Laravel.\
For more information on how to install Laravel check out: https://laravel.com/docs/9.x/installation

### Install with Lemur Engine with composer
```php
composer require lemurengine/lemurbot
```

### Additional Upgrade Step: Run the command to update a few filenames in the migrations folder
Only do this is you are migrating from the old project \
There are 4 files which need reverting to their original Laravel Migration file names \
As soon as you have installed Laravel 9 and connected your database \
Run this command to update the 4 filenames in the migrations table
```php
php artisan lemur:upgrade 9.0.0
```


### Publish Template (recommended)
This will create the default layout for the portal.\
If you are installing the Lemur Engine into a fresh application then run this command.\
If you already have a layout then you might want to skip this step\
As it will overwrite your existing layouts/app.blade.php, auth templates and homepage\
``` php
php artisan vendor:publish --tag=lemurbot-template --force
```

### Publish Auth Templates/Controllers (recommended)
This will create the customized authentication layout for the portal. \
If you are installing the Lemur Engine into a fresh application then run this command. \
If you already have created your authentication layouts/controllers then you might want to skip this step \
As it will overwrite your existing resources/views/auth and app/Http/Controllers/Auth 
``` php
php artisan vendor:publish --tag=lemurbot-auth --force
```

### Publish Public Assets (required)
This will copy the required asset files to your public folder \
And the example HelloWorldTag to the LemurTag folder  \
This is required to make forms and validation features to work correctly
``` php
php artisan vendor:publish --tag=lemurbot-assets
```

### Publish Public Widgets (required)
This will copy the widgets to the public widgets' folder  \
``` php
php artisan vendor:publish --tag=lemurbot-widgets
```

### Publish Config (required)
This will copy the lemur bot config files to config/lemurbot 
* config/lemurbot/lemur.php
* config/lemurbot/properties.php
``` php
php artisan vendor:publish --tag=lemurbot-config
```

### Publish Datatables Config And Assets (required)
This application uses the Yajra datatables plugin. \
https://yajrabox.com/docs/laravel-datatables \
If you need to publish the config fun the following command
```php 
php artisan vendor:publish --tag=datatables
php artisan vendor:publish --tag=datatables-buttons
php artisan vendor:publish --tag=datatables-html
php artisan vendor:publish --tag=datatables-fractal
```

### Publish Database Migrations (optional)
This will copy the lemur engine migration files to database/migration/lemurbot\
You don't really need to do this.\
But you can if you want to.
``` php
php artisan vendor:publish --tag=lemurbot-migrations
```

### Run the database migrations (required)
The following command will create or update your existing database table schema.
```php
php artisan migrate
```

### Symlink public folders (required)
The following command will create or update your existing database table schema.
```php
php artisan storage:link
```


### Additional Upgrade Step: Run the command to update the user to an admin user
Only do this is you are migrating from the old project \
If you have existing data and want to give a user bot_admin privileges you can run this command.
```php
php artisan lemur:install-admin --admin=[admin_email]
```

### Run the command to install a user, a bot and the AIML data
If you are migrating or already have this data you probably will not need to run this (see section at top of page about migrating)
```php
php artisan lemur:install-all --admin=[admin_email] --bot=[bot_name] --data=[none|min|max]
```
-`admin_email` is the email address you will log in with.
Your password will be 'password' which you should change immediately.

-`bot_name` is the name of the bot you will create

-`data` choose either none|min|all. This will instruct the installer to install either 
* none - no data
* min - 4 datasets (testing, hello, the critical and rating)
* max - all available datasets

example:
```php
php artisan lemur:install-all --admin=admin@lemurengine.local --bot=mybot --data=max
```

You do not have to install a user or bot, you can do these things at a later date, but we recommend you do this now. \
If your user already exists it will be given bot admin privileges and will be linked to your bot.

### Additional Upgrade Step: Custom Tag
Only do this is you are migrating from the old project \
Move any custom tags which you have created to a folder called in the main app folder called LemurTag
like so.

app/\
├── LemurTag/\
│   ├── YourTag.php\
│   ├── HelloWorldTag.php\
└── Models/

You will need to change the namespace of your custom tags to `namespace App\LemurTag;`\
Please look at the HelloWorldTag for an example

## Start the Application
You can start the application now and log in using the username you entered above and the password 'password'. \
Don't forget to change your password.
```php
php artisan serve
```

### More information
Check out the docs for more information about how to use the portal and talk to the bot.

http://docs.lemurengine.com
