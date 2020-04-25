<?php

// include composer autoload
require 'vendor/autoload.php';

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

function resizeImage( $img_path ) {

    $fnameA = pathinfo($img_path);
    // print_r($fnameA);
    // die;
    $name_1600 = $fnameA['dirname'] . "/" . $fnameA['basename'] . '-1600.jpg';
    $name_350 = $fnameA['dirname'] . "/" . $fnameA['basename'] . '-350.jpg';

    $manager = new ImageManager();

    $img1600 = $manager->make($img_path)->widen(1600, function ($constraint) {$constraint->upsize();});
    $img1600->save($name_1600, 70, 'jpg');

//    $img400 = $manager->make($img_path)->widen(350, function ($constraint) {$constraint->upsize();});
//    $img400->save($name_350, 100, 'jpg');
    $img400 = $img1600->widen(350, function ($constraint) {$constraint->upsize();});
    $img400->save($name_350, 90, 'jpg');

    $img400->destroy();
    $img1600->destroy();
    return ['1600' => $name_1600, '350' => $name_350];

}

function resizeImageRes( $img_path, $res , $quality = 70) {
    $fnameA = pathinfo($img_path);
    $manager = new ImageManager();

    $img1600 = $manager->make($img_path)->widen($res, function ($constraint) {
        $constraint->upsize();
    });
    $img1600->save($fnameA['dirname'] . "/" . $fnameA['basename'] . '-' . $res . '.jpg', $quality, 'jpg');
    $img1600->destroy();
}

function resizeDir() {
    $directory = './img/';

    $scanned_directory = array_diff(scandir($directory),["..", "."]);
    foreach( $scanned_directory as $fileT ) {
        $start = microtime(true);
        resizeImage( $directory . $fileT );

        $end = microtime(true);
        $runtime = $end - $start;
        echo "Время : " . number_format($runtime,2) .  " для " . $fileT .  PHP_EOL;
    }
}

/* resizeImage('Y:\ospanel5-3-5\domains\google-storage\img\20161111_120141.jpg'); */
//resizeDir();
//resizeImage('pano_share.jpg');
//resizeImageRes('./img/wmpage.jpg',1600);
//resizeImageRes('./img/wmpage.jpg',1900);