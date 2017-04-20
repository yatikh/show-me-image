<?php

use Silex\Application;
use MovingImage\Client\VMPro\Entity\ApiCredentials;
use MovingImage\Client\VMPro\ApiClientFactory;
use Yartikh\Api\Client;
use Yartikh\Image\Generator;

$app->get('/image/{videoId}/{timestamp}', function (Application $app, $videoId, $timestamp) {

    // make an API client
    $credentials = new ApiCredentials($app['config']['userName'], $app['config']['password']);
    $factory     = new ApiClientFactory();

    $apiClient = new Client($factory->createSimple($app['config']['baseUri'], $credentials));

    // get the video download urls
    try {
        $videoUrls = json_decode(
            $apiClient->getDownloadVideoUrls(
                $app['config']['videoManagerId'],
                $videoId
            ),
            true
        );
    } catch (\Exception $e) {
        $app->abort(404, "Video can not be found.");
    }

    // get the first mp4 video
    $mp4Urls = array_filter($videoUrls, function ($item) {
        return $item['fileExtension'] === 'mp4';
    });

    $mp4Url = current($mp4Urls);

    // time for a still
    $time = gmdate("H:i:s", $timestamp);

    // generate the image
    $generator = new Generator($app['config']['imagesFolder']);

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