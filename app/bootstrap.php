<?php

// for developing only
// $app['debug'] = true;

$app['config'] = [
    'userName'          => 'arge1234@superrito.com', // @TODO move to login form
    'password'          => 'GaSq7=t!', // @TODO move to login form
    'videoManagerId'    => 97, // @TODO probably should to move to parameters of request
    'baseUri'           => 'https://api-qa1.video-cdn.net/v1/vms/',
    'imagesFolder'      => __DIR__.'/../web/images/'
];