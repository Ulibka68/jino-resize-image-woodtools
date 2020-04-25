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

function resizeImage( $img_path ) {

    $fnameA = pathinfo($img_path);
    // print_r($fnameA);
    // die;
    $name_1600 = $fnameA['dirname'] . "/" . $fnameA['basename'] . '-1600.jpg';
    $name_350 = $fnameA['dirname'] . "/" . $fnameA['basename'] . '-350.jpg';

    Image::hook($img_path)->resize(1600)->save($name_1600, 70);
    Image::hook($img_path)->resize(350)->save($name_350, 90);

    return ['1600' => $name_1600, '350' => $name_350];

}

function test()
{
    $start = microtime(true);
    Image::hook('./img/20200201_185628.jpg')->resize(1600)->save('./img/_1.jpg', 70);
    $end = microtime(true);
    $runtime = $end - $start;
    echo "Время : " . number_format($runtime, 2) . " для " . ' ' . '<br>' . PHP_EOL;
}

function resizeDir() {
    $directory = './img/';

    $scanned_directory = array_diff(scandir($directory),["..", "."]);
    foreach( $scanned_directory as $fileT ) {
        $findme = "jpg-350";
//        print_r($fileT);
        if (strpos($fileT,$findme) !== false) {
            continue;
        }
        $findme = "jpg-1600";
        if (strpos($fileT,$findme) !== false) {
            continue;
        }

        $start = microtime(true);
        resizeImage( $directory . $fileT );

        $end = microtime(true);
        $runtime = $end - $start;
        echo "Время : " . number_format($runtime,2) .  " для " . $fileT . '<br>' . PHP_EOL;
    }
}


resizeDir();