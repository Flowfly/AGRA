<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 10.09.2018
 * Time: 11:26
 */

class Image
{
    //Variables
    private $name;
    private $idPost;

    //Properties
    public function getIdPost(){
        return $this->idPost;
    }
    public function setIdPost($value){
        $this->idPost = $value;
    }

    public function getName(){
        return $this->name;
    }
    public function setName($value){
        $this->name = $value;
    }

    //Constructors
    public function __construct($name, $idPost)
    {
        $this->setName($name);
        $this->setIdPost($idPost);
    }

    //Methods
    public function InsertImage(PDO $db)
    {
        try{
            $db->beginTransaction();
            $query_image = $db->prepare("INSERT INTO image (nameImage, idPost) VALUES (:nameImage, :idPost)");
            $query_image->bindValue(":nameImage", $this->name, PDO::PARAM_STR);
            $query_image->bindValue(":idPost", $this->idPost, PDO::PARAM_INT);
            $result = $query_image->execute();
            $db->commit();
            return $result;
        }catch(Exception $e)
        {
            echo $e->getMessage();
            $db->rollBack();
            return false;
        }

    }
}