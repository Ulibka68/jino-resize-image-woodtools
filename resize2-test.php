<?php

require_once "resize2.php";

function test()
{
    $start = microtime(true);
    Image::hook('./img/20200201_185628.jpg')->resize(1600)->save('./img/_1.jpg', 70);
//    Image::hook('./img/20200201_185628.jpg')->resize(1600)->save('./img/1/1/22/_1.jpg', 70);
    $end = microtime(true);
    $runtime = $end - $start;
    echo "Время : " . number_format($runtime, 2) . " для " . ' ' . '<br>' . PHP_EOL;
}
//test();

function test_create_folder() {
    $rst = createWritableFolder('./img/2/1/a.txt');
    echo 'результат : ' . $rst;
}
test_create_folder();

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


//resizeDir();