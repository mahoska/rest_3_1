<?php

abstract class Model{
    protected $pdo;

    public function __construct()
    {
        try{
            $this->pdo = DbConnection::getInstance()->getLink(); 
        }catch(PDOException $err){}
    }
    public function __destruct()
    {
        $this->pdo = null;
    }
    
    public function getFetchAccoss($sth){
        $collection = [];
        while($res = $sth->fetch(\PDO::FETCH_ASSOC)){
        $collection[] = $res;
        }
         return $collection;
    }
    
}

