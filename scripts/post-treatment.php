<?php

include_once("class/resize-class.php");
include_once("class/Post.php");
include_once("class/PostController.php");
include_once("class/Image.php");
include_once("post-functions.php");
include_once('class/Db.php');
define("FILE_MAX_SIZE", 3000000);

$db = new Db('Facebook', 'localhost', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$upload_dir = "../img/uploads";
$accepted_extensions = array("image/png", "image/jpg", "image/jpeg");


if (isset($_POST['text-post']) && isset($_FILES['picture-post'])) {
    $text = filter_input(INPUT_POST, 'text-post');
    $isFileOk = CheckImagesProperties($_FILES, $accepted_extensions);
    if ($isFileOk) {
        $time = date('Y-m-d') . ' ' . date('H:i:s');
        $post = new PostController($db->GetPDO());

        // Inserting the post in the database
        $listImage = array();
        $date = new DateTime();
        for ($i = 0; $i < count($_FILES['picture-post']['name']); $i++) {
            $img = new Image(uniqid() . $date->getTimestamp() . sha1(basename($_FILES['picture-post']['name'][$i])) . "." . pathinfo($_FILES['picture-post']['name'][$i])['extension']);
            array_push($listImage, $img);
        }
        $wasInsertSuccessfull = $post->Insert(new Post($text, $time, $time, $listImage));
        //var_dump($wasInsertSuccessfull);
        if ($wasInsertSuccessfull != -1) {
            //If all the image are good, this code will upload them on the "uploads" dir
            for ($i = 0; $i < count($_FILES['picture-post']['name']); $i++) {
                $name = $listImage[$i]->getName();
                $tmp_name = $_FILES['picture-post']['tmp_name'][$i];
                $wasImageUploaded = move_uploaded_file($tmp_name, "$upload_dir/$name");

                if ($wasImageUploaded) {
                    $imgresized = new resize("$upload_dir/$name");
                    $imgresized->resizeImage(1024, 768, 'exact');
                    $imgresized->saveImage("$upload_dir/$name", 9);
                } else {
                    $post->Delete($wasInsertSuccessfull);
                    break;
                }
            }

        }
    }
}
?>