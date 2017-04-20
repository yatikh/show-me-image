<?php

use Silex\Application;
use MovingImage\Client\VMPro\Entity\ApiCredentials;
use MovingImage\Client\VMPro\ApiClientFactory;
use Yartikh\Api\Client;
use Yartikh\Image\Generator;

require_once __DIR__.'/../vendor/autoload.php';


$app = new Application();

// $app['debug'] = true;

$app->get('/image/{videoId}/{timestamp}', function (Application $app, $videoId, $timestamp) {
    // @TODO move to login form or config
    $userName = 'arge1234@superrito.com';
    $password = 'GaSq7=t!';

    // @TODO move to parameters or config
    $videoManagerId = 97;

    // @TODO move to config
    $baseUri     = 'https://api-qa1.video-cdn.net/v1/vms/';

    $credentials = new ApiCredentials($userName, $password);
    $factory     = new ApiClientFactory();

    $apiClient = new Client($factory->createSimple($baseUri, $credentials));

    $videoUrls = json_decode($apiClient->getDownloadVideoUrls($videoManagerId, $videoId), true);

    // get the first mp4 video
    $mp4Urls = array_filter($videoUrls, function ($item) {
        return $item['fileExtension'] === 'mp4';
    });

    $mp4Url = current($mp4Urls);

    // time for a still
    $time = gmdate("H:i:s", $timestamp);

    // generate the image the path for generated image
    $generator = new Generator(__DIR__.'/images/');

    try {
        $imagePath = $generator->generateFromVideoUrl($mp4Url['url'], $time);

        $stream = function () use ($imagePath) {
            readfile($imagePath);
        };
    } catch (\Exception $e) {
        return $app->abort(400, "Can't generate the still image from video $videoId");
    }

    return $app->stream($stream, 200, ['Content-Type' => 'image/jpeg']);
})
->assert('timestamp', '\d+')
->value('timestamp', 5);


$app->run();
