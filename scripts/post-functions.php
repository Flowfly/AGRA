<?php

function CheckImagesProperties(array $files, array $accepted_extensions)
{
    $isFileOk = true;
    $nbElements = count($files['picture-post']['name']);
    $finfo = finfo_open(FILEINFO_MIME);
    for ($i = 0; $i < $nbElements; $i++) {
        $isFileOk = true;
        $mime = finfo_file($finfo, $_FILES['picture-post']['tmp_name'][$i]);
        $extension = explode(';', $mime);

        //Check if the file is to the correct extension
        if (!in_array($_FILES['picture-post']['type'][$i], $accepted_extensions) || !in_array($extension[0], $accepted_extensions)) {
            $isFileOk = false;
            break;
        }
        //Check if the file doesn't exceed the size
        if ($_FILES['picture-post']['size'][$i] > FILE_MAX_SIZE) {
            $isFileOk = false;
            break;
        }
    }
    finfo_close($finfo);
    return $isFileOk;
}

?>