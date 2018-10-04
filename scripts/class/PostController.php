<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 17.09.2018
 * Time: 08:34
 */

class PostController
{
    private $db;

    public function setDb(PDO $db)
    {
        $this->db = $db;
    }

    public function __construct($db)
    {
        $this->setDb($db);
    }

    private function InsertPost(Post $post)
    {
        $query = $this->db->prepare("INSERT INTO post (textPost, datePost, dateLastUpdate) VALUES (:text, :dateInsert, :dateLastUpdate)");
        $query->bindValue(":text", $post->getText(), PDO::PARAM_STR);
        $query->bindValue(":dateInsert", $post->getDate(), PDO::PARAM_STR);
        $query->bindValue(":dateLastUpdate", $post->getDateLastUpdate(), PDO::PARAM_STR);
        $query->execute();
        $lastId = $this->db->lastInsertId();
        return $lastId;
    }

    private function InsertImage(Image $img)
    {
        $query_image = $this->db->prepare("INSERT INTO image (nameImage, idPost) VALUES (:nameImage, :idPost)");
        $query_image->bindValue(":nameImage", $img->getName(), PDO::PARAM_STR);
        $query_image->bindValue(":idPost", $img->getIdPost(), PDO::PARAM_INT);
        $result = $query_image->execute();
        return $result;
    }

    public function Insert(Post $post)
    {
        try {
            $this->db->beginTransaction();
            $last_idpost_inserted = $this->InsertPost($post);
            if ($last_idpost_inserted != 0) {
                for ($i = 0; $i < count($post->getImages()); $i++) {
                    $post->getImages()[$i]->setIdPost($last_idpost_inserted);
                    $wasInsertSuccessfull = $this->InsertImage($post->getImages()[$i]);
                    if (!$wasInsertSuccessfull)
                        break;
                }
            }
            $this->db->commit();
            return (int)$last_idpost_inserted;
        } catch (Exception $e) {
            $this->db->rollback();
            return -1;
        }
    }

    public function DeleteOneImage($name)
    {
        try{
            $this->db->beginTransaction();
            $query = $this->db->prepare("DELETE FROM image WHERE nameImage = :name");
            $query->bindValue(":name", $name, PDO::PARAM_STR);
            $query->execute();
            $this->db->commit();
            return 0;
        }catch (Exception $exception)
        {
            $this->db->rollback();
            return -1;
        }
    }

    public function Delete($id)
    {
        try{
            $this->db->beginTransaction();
            $query = $this->db->prepare("DELETE FROM post WHERE post.idPost = :id");
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->execute();
            $query = $this->db->prepare("DELETE FROM image WHERE image.idPost = :id");
            $query->bindValue(":id", $id, PDO::PARAM_INT);
            $query->execute();
            $this->db->commit();
            return 0;
        }catch(Exception $e)
        {
            $this->db->rollback();
            return -1;
        }
    }

    public function SelectAllPosts()
    {
        $idpost_list = array();
        $img_list = array();
        $post_list = array();
        $query = $this->db->prepare("SELECT idPost FROM post");
        $query->execute();
        while($result = $query->fetch(PDO::FETCH_ASSOC))
        {
            array_push($idpost_list, $result['idPost']);
        }
        for($i = 0; $i < count($idpost_list); $i++)
        {
            $query = $this->db->prepare("SELECT * FROM image WHERE idPost = :id");
            $query->bindValue(":id", $idpost_list[$i], PDO::PARAM_INT);
            $query->execute();
            while($result = $query->fetch(PDO::FETCH_ASSOC))
            {
                array_push($img_list, new Image($result['nameImage'],$idpost_list[$i]));
            }
            $query_post = $this->db->prepare("SELECT * FROM post WHERE idPost = :id");
            $query_post->bindValue(":id", $idpost_list[$i], PDO::PARAM_INT);
            $query_post->execute();
            while($result = $query_post->fetch(PDO::FETCH_ASSOC))
            {
                array_push($post_list, new Post($idpost_list[$i], $result['textPost'], $result['datePost'], $result['dateLastUpdate'], $img_list));
            }
            $img_list = array();
        }

        return $post_list;

    }

    public function SelectOnePost($id)
    {
        $img_list = array();

        $query = $this->db->prepare("SELECT nameImage FROM image WHERE idPost = :id");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        while($result = $query->fetch(PDO::FETCH_ASSOC))
        {
            array_push($img_list, new Image($result['nameImage'], $id));
        }

        $query = $this->db->prepare("SELECT * FROM post WHERE idPost = :id");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
         return empty($result) ?  null : new Post($id, $result[0]['textPost'], $result[0]['datePost'], $result[0]['dateLastUpdate'], $img_list);
    }
}