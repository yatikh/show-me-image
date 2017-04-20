<?php

namespace Yartikh\Api;

use MovingImage\Client\VMPro\ApiClient\AbstractApiClient;

/**
 * Extended version of MovingImage Api Client.
 *
 * @author Yaroslav Tikhomirov <yatikh@gmail.com>
 */
class Client
{
    /**
     * The original MovingImage Api Client.
     *
     * @var AbstractApiClient
     */
    protected $apiClient;

    /**
     * @param AbstractApiClient $apiClient [description]
     */
    public function __construct(AbstractApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Delegate all standart methods calls to original client object.
     *
     * @param  string   $method
     * @param  array    $args
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function __call($method, $args)
    {
        if (method_exists($this->apiClient, $method)) {
            return call_user_func_array([$this->apiClient, $method], $args);
        }

        throw new \InvalidArgumentException("$method doesn't exist in class {self::class}");
    }

    /**
     * Get download urls for the video through MovingImage API.
     *
     * @param  string $videoManagerId
     * @param  string $videoId
     * @param  array  $metadata
     * @return string String represented Api response.
     */
    public function getDownloadVideoUrls($videoManagerId, $videoId, $metadata = [])
    {
        // extending client with new method
        $getDownloadVideoUrl = \Closure::bind(
            function ($videoManagerId, $videoId, $metadata = []) {
                $response = $this->makeRequest(
                    'GET',
                    sprintf('videos/%s/download-urls', $videoId),
                    [
                        self::OPT_VIDEO_MANAGER_ID => $videoManagerId,
                        'query' => $metadata,
                    ]
                );

                return $response->getBody()->getContents();
            },
            $this->apiClient,
            $this->apiClient
        );

        return $getDownloadVideoUrl($videoManagerId, $videoId);
    }
}