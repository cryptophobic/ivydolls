<?php

namespace app\models\BO;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/18/2017
 * Time: 5:00 PM
 */
class ResampleImages
{
    private $width = 0;

    private $height = 0;

    private $whiteImage = null;

    public function __construct()
    {
        ini_set('memory_limit', '128M');
        /*$this->width = $width;
        $this->height = $height;

        $this->$whiteImage = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate ($this->whiteImage, 255, 255, 255 );
        imagefilledrectangle($this->whiteImage,0,0,$this->width,$this->height,$bg);*/
    }

    /**
     * @param $fileSource
     * @param string $fileDestination
     * @return string
     */
    public function resample($fileSource, $fileDestination = '', $width = 700, $height = 1050)
    {
        $whiteImage = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($whiteImage, 255, 255, 255);
        imagefilledrectangle($whiteImage, 0, 0, $width, $height, $bg);

        if (empty($fileDestination)) {
            $fileDestination = $fileSource . 'resampled.jpg';
        }

        list($widthSource, $heightSource) = getimagesize($fileSource);

        $ratioSource = $widthSource / $heightSource;
        $widthResult = $width;
        $heightResult = $height;


        if ($widthResult / $heightResult > $ratioSource) {
            //$heightResult = $widthResult / $ratioSource;

//            $heightSource = $heightResult;
            $widthResult = $heightResult * $ratioSource;
        } else {
            $heightResult = $widthResult / $ratioSource;
        }

        $x = ($width - $widthResult) / 2;
        $y = ($height - $heightResult) / 2;
        $image = imagecreatefromjpeg($fileSource);
        imagecopyresampled($whiteImage, $image, $x, $y, 0, 0, $widthResult, $heightResult, $widthSource, $heightSource);

        imagejpeg($whiteImage, $fileDestination);
        return $fileDestination;
    }
}