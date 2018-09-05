<?php

include("class/resize-class.php");
include_once ('dbConnection.php');
define("FILE_MAX_SIZE", 3000000);

$upload_dir = "../img/uploads";
$accepted_extensions = array("image/png", "image/jpg", "image/jpeg");
$nbElements =  count($_FILES['picture-post']['name']);



//var_dump($_FILES);
for($i = 0; $i < $nbElements; $i++)
{
    $isFileOk = true;

    //Check if the file is to the correct extension
    if(!in_array($_FILES['picture-post']['type'][$i], $accepted_extensions))
        $isFileOk = false;
    //Check if the file doesn't exceed the size
    if($_FILES['picture-post']['size'][$i] > FILE_MAX_SIZE)
        $isFileOk = false;

    //If all the image is good, this code will upload it on the "uploads" dir
    if($isFileOk) {
        $tmp_name = $_FILES['picture-post']['tmp_name'][$i];
        $query = $bdd->prepare("SELECT MAX(idPost) as idMax FROM post");
        $query->execute();
        $idmax = $query->fetchAll(PDO::FETCH_ASSOC);
        if($idmax[0]['idMax'] == null)
            $idmax = 1;
        else
            $idmax = $idmax[0]['idMax']+1;
        $date = new DateTime();
        $name = $idmax . "_" . uniqid() . $date->getTimestamp() . sha1(basename($_FILES['picture-post']['name'][$i])) . "." . pathinfo($_FILES['picture-post']['name'][$i])['extension'];
        move_uploaded_file($tmp_name, "$upload_dir/$name");
        $imgresized = new resize("$upload_dir/$name");
        $imgresized->resizeImage(1024, 768, 'crop');
        $imgresized->saveImage("$upload_dir/$name", 9);
    }
}
/*
echo "<br>";
$fullpath = "C:\laragon\www\AGRA\img\\" . $_FILES['picture-post']['name'][0];
echo $fullpath . "<br>";
echo $_SERVER['SERVER_NAME'] . "\AGRA\img\\";*/
/*$imgresized = new resize($fullpath);
$imgresized->resizeImage(1024, 768, 'auto');
$imgresized->saveImage('test.png', 9);
*/
?>