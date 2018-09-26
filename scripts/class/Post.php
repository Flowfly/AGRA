<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 10.09.2018
 * Time: 11:36
 */

class Post
{
    private $id;


    private $text;
    private $date;
    private $dateLastUpdate;
    private $images = array();


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($value)
    {
        $this->text = $value;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($value)
    {
        $this->date = $value;
    }

    public function getDateLastUpdate()
    {
        return $this->dateLastUpdate;
    }

    public function setDateLastUpdate($value)
    {
        $this->dateLastUpdate = $value;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;
    }

    public function __construct($text, $date, $dateLastUpdate, array $img)
    {
        $this->setText($text);
        $this->setDate($date);
        $this->setDateLastUpdate($dateLastUpdate);
        $this->setImages($img);

    }

    //Methods
}