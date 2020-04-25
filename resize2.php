<?php

//https://ru.stackoverflow.com/questions/232886/warning-imagedestroy-7-is-not-a-valid-image-resource

class Image
{
    private $source = false, $temp = false;

    /**
     * hook file
     */
    public function __construct($image)
    {
        if (!$info = getimagesize($image)) {
            throw new Exception("error image");
        }

        $createFunc = 'imagecreatefrom' . strtolower(str_replace('image/', '', $info['mime']));
        $this->source = $createFunc($image);

        return $this; //->source = $createFunc($image);
    }

    public function resize($width)
    {
        if (!$this->source) {
            throw new Exception("error image");
        }

        if (imagesx($this->source) < $width) {
            $width = imagesx($this->source);
        }

        $ratio = $width / imagesx($this->source);
        $height = imagesy($this->source) * $ratio;


        $this->temp = imagecreatetruecolor($width, $height);
        imagecopyresampled($this->temp, $this->source, 0, 0, 0, 0, $width, $height, imagesx($this->source), imagesy($this->source));
        $this->source = $this->temp;

        return $this;
    }

    /**
     * save
     */
    public function save($filename, $quality = 80)
    {
        $info = imagejpeg($this->source, $filename,$quality);

        if (is_resource($this->temp)) {
            imagedestroy($this->temp);
        }

        if (is_resource($this->source)) {
            imagedestroy($this->source);
        }

        return $info;
    }

    /**
     * hook
     */
    public static function hook($image)
    {
        $className = get_called_class();
        return new $className($image);
    }
}

function createWritableFolder($folder)
{
    $dir_root = dirname($folder);
    if($folder != '.' && $folder != '/' ) {
        createWritableFolder(dirname($folder));
    }
    if (file_exists($folder)) {
        return is_writable($folder);
    }
    return  mkdir($folder, 0777, true);
}

function resizeImage( $img_path,$name_350 , $name_1600) {
    createWritableFolder(dirname($name_350));

    Image::hook($img_path)->resize(1600)->save($name_1600, 70);
    Image::hook($img_path)->resize(350)->save($name_350, 90);
}

