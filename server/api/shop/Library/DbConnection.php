<?php
class DbConnection
{
    private $pdo;
    private static $instance;

    private function __construct()
    {
        try{
            $this->pdo = new PDO('mysql:host='.HOST.';dbname='.DBNAME, USER, PSW);  
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }catch(PDOException $err){
            //file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            //throw new PDOException($err);
        }
    }

    private function __clone(){}

        public function getLink()
        {
            return $this->pdo;
        }

    public function __wakeUp()
    {
        throw new Exception();
    }

    public static  function getInstance()
    {
        if(self::$instance == null){
            self::$instance = new DbConnection();
        }
        return self::$instance;
    }
}

