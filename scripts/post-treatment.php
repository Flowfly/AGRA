<?php
$accepted_extensions = array("image/png", "image/jpg", "image/jpeg");
define("FILE_MAX_SIZE", 3000000);
$nbElements =  count($_FILES['picture-post']['name']);
var_dump($_FILES);
for($i = 0; $i < $nbElements; $i++)
{
    //Check if the file is to the correct extension
    if(in_array($_FILES['picture-post']['type'][$i], $accepted_extensions))
        echo "bonne extension ";
    else
        echo "mauvaise extension ";
    //Check if the file doesn't exceed the size
    if($_FILES['picture-post']['size'][$i] <= FILE_MAX_SIZE)
        echo "bonne taille ";
    else
        echo "mauvaise taille ";
    echo "<br>";
}
?>