<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json');

# Подключаем autoloader для GCS
require __DIR__ . '/vendor/autoload.php';
require_once 'resize.php';

# Подключаем необходимый класс для работы
use Google\Cloud\Storage\StorageClient;

function GUID()
{
    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

# Проверяем, загрузил ли пользователь файл
if(isset($_FILES) && $_FILES['file']['error'] == 0) {
    $allowed = array('png', 'jpg', 'gif', 'jpeg','webp', 'psd');

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if(!in_array(strtolower($ext), $allowed)) {
        echo 'The file is not an image.';
        die;
    }

//    сделать ресайз
    $resize_files =  resizeImage($_FILES['file']['tmp_name']) ;

    # Указываем название проекта
    $projectId = 'function-learn';

    # Создаем соединение с GCS
    $storage = new StorageClient([
        'projectId' => $projectId,
        'keyFilePath' =>  'function-learn.json'  # Наш ключ
    ]);
    
    # Наш сегмент
    $bucketName = 'woodtools';
    $bucket = $storage->bucket($bucketName);

    // 'predefinedAcl' => 'publicRead',
    $GCP_name = 'images/' . date("Y-m-d") . '/' . GUID() ;

    $options = [
        'predefinedAcl' => 'publicRead',
        'name' => $GCP_name . '-350.jpg'
    ];
  
    // Upload a file to the bucket.
    $bucket->upload(
        fopen($resize_files['350'], 'r'),
        $options
    );


    $options['name']=$GCP_name . '-1600.jpg';
    $bucket->upload(
        fopen($resize_files['1600'], 'r'),
        $options
    );
    $shared_URL = "https://storage.googleapis.com/woodtools/" ;

    $ret_name_1600 = $shared_URL . $GCP_name . '-1600.jpg';
    $ret_name_350 = $shared_URL . $GCP_name . '-350.jpg';

    unlink($resize_files['1600']);
    unlink($resize_files['350']);

    $fin_ret = ['1600'=>$ret_name_1600,'350'=>$ret_name_350];

    echo  json_encode ( $fin_ret) ;
}
else{
    echo 'Extension not allowed - No File Uploaded';
}