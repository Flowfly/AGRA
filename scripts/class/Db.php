<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 12.09.2018
 * Time: 14:37
 */

class Db
{
    private static $dbinstance;
    private $dbname;
    private $host;
    private $user;
    private $password;
    private $options;

    /**
     * @return mixed
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * @param mixed $dbname
     */
    public function setDbname($dbname)
    {
        $this->dbname = $dbname;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function __construct($dbname, $host, $user, $password, array $options)
    {
        $this->setDbname($dbname);
        $this->setHost($host);
        $this->setUser($user);
        $this->setPassword($password);
        $this->setOptions($options);
    }

    public function GetPDO()
    {
        if(self::$dbinstance === null)
        {
            try{
                $dsn = 'mysql:host=' . $this->getHost() . ';dbname=' . $this->getDbname();
                self::$dbinstance = new PDO($dsn, $this->getUser(), $this->getPassword(), $this->getOptions());

            }catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        return self::$dbinstance;

    }
}