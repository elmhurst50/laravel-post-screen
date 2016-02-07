# Laravel Post Screen Package

Scans through a users input and checks for unwanted words, phrases, links, and emails. If the list of words you have are long I would recommend having the response back to the user, queue the post screening for later and publish once scanned.

### Installation

```sh
$ composer install samjoyce777/laravel-post-screen --save
```

Add the service provider to the config.php

```sh
$ \samjoyce777\LaravelPostScreen\ScreenServiceProvider::class,
```

Add the facade as well to make it all pretty

```sh
$ 'Map' => \samjoyce777\LaravelPostScreen\Facades\Screen::class,
```

Move the config file to make your customizations

```sh
$ php artisan vendor:publish --tag=config
```

### Usage

This will check to see if an email is in the text
```sh
Screen::hasEmail($post);
```

This will check to see if text has an external link
```sh
Screen::hasExternalLink($post);
```

This run through your configuration to see if the text passes all methods required.
```sh
Screen::isClean($post);
```

You can also set different levels of screening depending on requirements by setting more levels in the config file and passing level name on the second argument.
```sh
Screen::isClean($post, 'review');
```
