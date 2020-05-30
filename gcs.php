<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json');


# Подключаем autoloader для GCS
require_once 'resize2.php';

function GUID()
{
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

// if(! isset($_FILES)) {
//     echo 'Файл не вложен в запрос';
//     die;
// }

# Проверяем, загрузил ли пользователь файл
if(isset($_FILES) &&  $_FILES['file']['error'] == 0) {
    $allowed = array('png', 'jpg', 'gif', 'jpeg','webp');

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if(!in_array(strtolower($ext), $allowed)) {
        echo 'Ваш файл не является изображением. ';
        die;
    }

//    сделать ресайз
//    $root_dir = "/domains/ggvlasov.myjino.ru/";
//    $root_dir = "/";
    $root_dir = './';

    $GCP_name ='images/' . date("Y-m-d") . '/' . GUID() ;


    $resize_files =  resizeImage($_FILES['file']['tmp_name'],$root_dir . $GCP_name . '-350.jpg',$root_dir . $GCP_name . '-1600.jpg') ;

    $shared_URL = "https://woodtoolsimg.ru/resize/" ;

    $ret_name_1600 = $shared_URL . $GCP_name . '-1600.jpg';
    $ret_name_350 = $shared_URL . $GCP_name . '-350.jpg';

    $fin_ret = ['1600'=>$ret_name_1600,'350'=>$ret_name_350];

    echo  json_encode ( $fin_ret) ;
}
else{
    echo 'Extension not allowed - No File Uploaded';
}