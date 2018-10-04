<?php

include_once("class/resize-class.php");
include_once("class/Post.php");
include_once("class/PostController.php");
include_once("class/Image.php");
include_once("post-functions.php");
include_once('class/Db.php');
require_once('../vendor/autoload.php');

define("FILE_MAX_SIZE", 3000000);
define("IMAGE_WIDTH", 1024);
define("IMAGE_HEIGHT", 768);

$db = new Db('Facebook', 'localhost', 'florian', '6B8X7BzRfLUFyOrF', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$upload_dir = "../img/uploads";
$accepted_extensions = array("image/png", "image/jpg", "image/jpeg");

//Code about the insert treatment

if (isset($_POST['text-post']) && isset($_FILES['picture-post'])) {

    //Filtering the post var
    $text = filter_input(INPUT_POST, 'text-post');

    //Checking if the files use the right extensions
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



        $wasInsertSuccessfull = $post->Insert(new Post(0, $text, $time, $time, $listImage));

        if ($wasInsertSuccessfull != -1) {
            //If all the image are good, this code will upload them on the "uploads" dir
            for ($i = 0; $i < count($_FILES['picture-post']['name']); $i++) {
                $name = $listImage[$i]->getName();
                $tmp_name = $_FILES['picture-post']['tmp_name'][$i];
                $wasImageUploaded = move_uploaded_file($tmp_name, "$upload_dir/$name");

                if ($wasImageUploaded) {
                    $image = new \Gumlet\ImageResize("$upload_dir/$name");
                    $image->resizeToBestFit(IMAGE_WIDTH, IMAGE_HEIGHT, false);
                    $image->save("$upload_dir/$name");
                } else {
                    $post->Delete($wasInsertSuccessfull);
                    break;
                }
            }
            header("Location: ../index.php");

        }
        else{
            echo "pas upload";
        }
    } else
        echo "non";
}

// Code about the delete treatment
// This code is called by Ajax on the index view
if (isset($_POST['idpost']) && isset($_POST['modal-delete-submit'])) {
    //Filtering the id var
    $idpost = filter_input(INPUT_POST, 'idpost');

    $postController = new PostController($db->GetPDO());
    //trying to retrieve the post with the id
    $post = $postController->SelectOnePost($idpost);

    //If the post exists
    if ($post != null) {
        //We're trying to delete it
        $wasPostDeleted = $postController->Delete($post->getId());
        //if it succeed :
        if ($wasPostDeleted != -1) {
            $wasImageDeleted = true;
            $wasCopySuccessfull = true;
            for ($i = 0; $i < count($post->getImages()); $i++) {
                //Creating a copy of the files in case the deletion of the images would not succeed
                if (copy($upload_dir . "/" . $post->getImages()[$i]->getName(), $upload_dir . "/tmp/" . $post->getImages()[$i]->getName())) {
                    $oldDir = getcwd();
                    chdir($upload_dir);
                    $wasImageDeleted = unlink($post->getImages()[$i]->getName());
                    if (!$wasImageDeleted)
                        break;
                    else
                        chdir($oldDir);
                } else {
                    $wasCopySuccessfull = false;
                    break;
                }

            }
            //If the copy fails, we reinsert the post in the database
            if (!$wasCopySuccessfull) {
                $postController->Insert($post);
            }
            //Else, we check if the images were correctly deleted
            else {
                if (!$wasImageDeleted) {
                    $oldDir = getcwd();
                    chdir($upload_dir);
                    $files = glob("tmp" . '/*');
                    foreach ($files as $file) {
                        //We retrieve the files copied in the tmp dir and copy them in the upload file
                        $filename = explode("/", $file);
                        copy($file, $filename[1]);
                        unlink($file);
                    }
                    //Then we reinsert the post
                    chdir($oldDir);
                    $postController->Insert($post);

                }
                //If the images were deleted correctly, we just delete the content of the tmp dir
                else {
                    $oldDir = getcwd();
                    chdir($upload_dir);
                    $files = glob("tmp" . '/*');
                    foreach ($files as $file) {
                        unlink($file);
                    }
                    chdir($oldDir);
                }
            }

        }
    }
}

// Code about the update treatment
// This code is called by Ajax on the index view
if (isset($_POST['imageName'])) {
    $imageName = filter_input(INPUT_POST, "imageName");
    $controller = new PostController($db->GetPDO());
    $wasImageDeleteFromDb = $controller->DeleteOneImage($imageName);

    if($wasImageDeleteFromDb)
    {
        if(copy($upload_dir . "/" . $imageName, $upload_dir . "/tmp/" . $imageName))
        {
            $oldDir = getcwd();
            chdir($upload_dir);
            $wasImageDeleted = unlink($imageName);
            chdir($oldDir);
            if($wasImageDeleted)
            {
                chdir($upload_dir . "/tmp");
                unlink($imageName);
                chdir($oldDir);
            }
            else
            {
                copy($upload_dir . "/tmp/" . $imageName, $upload_dir . "/" . $imageName);
            }
        }
    }
    else
    {
        throw new Exception("Image non supprimée");
    }
}
?>