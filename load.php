<?php
//загрузка изображения
if (isset($_FILES["filename"]["tmp_name"])) {
  move_uploaded_file($_FILES["filename"]["tmp_name"],"img/".$_FILES["filename"]["name"]);
  echo "Файл успешно загружен";
} else {
  echo "Ошибка загрузки";
}

//путь
$filename = "img/".$_FILES["filename"]["name"];

//исходные размеры
$imgSize = getimagesize($filename);
$imgWidth = $imgSize[0];
$imgHeight = $imgSize[1];

if ($imgWidth > 500 && $imgHeight > 500) {
    //выбор новых размеров
    if ($imgWidth > $imgHeight) {
        $k = $imgHeight/$imgWidth;
        $newWidth = 500;
        $newHeight = 500*$k;
    } else {
        $k = $imgWidth/$imgHeight;
        $newHeight = 500;
        $newWidth = 500*$k;
    }

    //расширение
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if ($ext == "png") {

        $res = imageCreateFromPng($filename);

        $tmp = imageCreateTrueColor($newWidth, $newHeight);

        //альфа-канал (png)
        imageAlphaBlending($tmp, false);
        imageSaveAlpha($tmp, true);

        //уменьшение размера
        imageCopyResampled($tmp, $res, 0, 0, 0, 0, $newWidth, $newHeight, $imgWidth, $imgHeight);

        //сохранение файла
        imagejpeg($tmp, $filename, 100);
        imagedestroy($tmp);
        imagedestroy($res);
    } else {
        //jpg

        $image_p = imagecreatetruecolor($newWidth, $newHeight);
        $image = imagecreatefromjpeg($filename);
        //уменьшение размера
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imgWidth, $imgHeight);

        // вывод
        imagejpeg($image_p, $filename, 100);
        imagedestroy($image_p);
        imagedestroy($image);
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <p><img src="<?=$filename?>" alt=""></p>
    </body>
</html>
