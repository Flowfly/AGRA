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
        if($idPost == 0)
            $this->setIdPost(0);
        else
            $this->setIdPost($idPost);
    }

    //Methods

}