<?php

namespace Yartikh\Image;

use Yartikh\Exception\ImageGenerationException;

/**
 * Class which used for generation images from video file.
 *
 * @author Yaroslav Tikhomirov <yatikh@gmail.com>
 */
class Generator
{
    /**
     * The folder where images will store.
     *
     * @var string
     */
    protected $saveDir;

    /**
     * @param string $saveDir
     */
    public function __construct($saveDir)
    {
        $this->saveDir = $saveDir;
    }

    /**
     * Path for new generated image.
     *
     * @param  string $imageName
     * @return string
     */
    protected function getImagePath($imageName)
    {
        return $this->saveDir.$imageName.'.jpeg';
    }

    /**
     * Generating image throug ffmpeg.
     *
     * @param  string $videoUrl
     * @param  string $time
     * @return string Path for the new image.
     */
    public function generateFromVideoUrl($videoUrl, $time)
    {
        $imagePath = $this->getImagePath(sha1($videoUrl.$time));

        if (file_exists($imagePath)) {
            return $imagePath;
        }

        exec(
            sprintf(
                'ffmpeg -ss %s -i "%s" -vframes 1 -f image2 "%s"',
                $time,
                $videoUrl,
                $imagePath
            ),
            $output,
            $result
        );

        if ($result !== 0) {
            throw new ImageGenerationException("ffmpeg can't generate image from $videoUrl");
        }

        return $imagePath;
    }
}