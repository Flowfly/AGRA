<?php

try{
    $user = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    $bdd = new PDO('mysql:host=localhost;dbname=facebook', $user, $password, $options);

}catch(Exception $e)
{
    $e->getMessage();
}
?>