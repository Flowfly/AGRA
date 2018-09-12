<?php

include_once("class/resize-class.php");
include_once("class/Post.php");
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
        // Inserting the post in the database
        $time = date('Y-m-d') . ' ' . date('H:i:s');
        $post = new Post($db->GetPDO(), $text, $time, $time);
        $last_idpost_inserted = $post->InsertPost();
        if($last_idpost_inserted >= 0)
        {
            for ($i = 0; $i < count($_FILES['picture-post']['name']); $i++) {

                $date = new DateTime();
                $img = new Image($last_idpost_inserted . "_" . uniqid() . $date->getTimestamp() . sha1(basename($_FILES['picture-post']['name'][$i])) . "." . pathinfo($_FILES['picture-post']['name'][$i])['extension'], $last_idpost_inserted);
                $wasImageInserted = $img->InsertImage($db->GetPDO());

                if ($wasImageInserted) {
                    //If all the image are good, this code will upload them on the "uploads" dir
                    $tmp_name = $_FILES['picture-post']['tmp_name'][$i];
                    $name = $img->getName();
                    move_uploaded_file($tmp_name, "$upload_dir/$name");
                    $imgresized = new resize("$upload_dir/$name");
                    $imgresized->resizeImage(1024, 768, 'exact');
                    $imgresized->saveImage("$upload_dir/$name", 9);
                }
            }
        }
    }
}
?>