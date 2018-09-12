<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 10.09.2018
 * Time: 11:36
 */

class Post
{
    private $db;
    private $text;
    private $date;
    private $dateLastUpdate;
    private $images;

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
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

    public function __construct(PDO $db, $text, $date, $dateLastUpdate)
    {
        $this->setDb($db);
        $this->setText($text);
        $this->setDate($date);
        $this->setDateLastUpdate($dateLastUpdate);
    }

    //Methods
    public function InsertPost()
    {
        try {
            $this->getDb()->beginTransaction();
            $query = $this->getDb()->prepare("INSERT INTO post (textPost, datePost, dateLastUpdate) VALUES (:text, :dateInsert, :dateLastUpdate)");
            $query->bindValue(":text", $this->getText(), PDO::PARAM_STR);
            $query->bindValue(":dateInsert", $this->getDate(), PDO::PARAM_STR);
            $query->bindValue(":dateLastUpdate", $this->getDateLastUpdate(), PDO::PARAM_STR);
            $query->execute();
            $lastId = $this->getDb()->lastInsertId();
            $this->getDb()->commit();
            return $lastId;
        } catch (Exception $e) {
            $this->getDb()->rollBack();
            echo $e->getMessage();
            return -1;
        }
    }

    private function FillImagesVar()
    {
        try{
            $this->getDb()->beginTransaction();
            $query = $this->getDb()->prepare("SELECT nameImage FROM image, post WHERE image.idPost = post.idPost AND image.idPost = :idpost");
            $query->bindValue(":idpost", $this->get);

        }catch(Exception $e)
        {
            $e->getMessage();
        }
    }
}