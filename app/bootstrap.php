<?php

$env = getenv('APP_ENV') ?: 'prod';

$app->register(
    new Igorw\Silex\ConfigServiceProvider(
        __DIR__."/config/$env.json",
        [
            'docroot' => __DIR__.'/../web/',
            'images_folder' => __DIR__.'/../web/images/',
        ]
    )
);