<?php

class AuthModel extends Model{

    //registration - work
    public function createUser($params)
    {
       try{
        //if login exists -?
        $sth = $this->pdo->prepare('SELECT  COUNT(*) FROM   users WHERE login=:login');
        $sth->execute(['login' => $params['login']]);    
        $res = $sth->fetch(\PDO::FETCH_NUM);
        if($res[0]>0) return ['status'=>500, 'data'=>[]];
        
        $sth = $this->pdo->prepare('INSERT INTO users( name, sname, login, password, status, time_life) '
                . 'VALUES ( :name, :sname, :login, :password, :status, :time_life)');
        $sth->execute($params);
        if($this->pdo->lastInsertId()>0)
             return ['status'=>200, 'data'=>$this->pdo->lastInsertId()];//return id - for cookie
         else 
             return ['status'=>500, 'data'=>[]];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'data'=>[]];
        } 
    } 
    
    //login - work
    public function setLogin($params)
    {
        try{
            $sth = $this->pdo->prepare("UPDATE `users` SET `status` = 1, time_life = :time_life  WHERE login = :login AND password =:password");
            $sth->execute($params);
            $count =  $sth->rowCount();
            if($count>0)
                return ['status'=>200, 'data'=>1];
             else 
                 return ['status'=>500, 'data'=>[]];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'data'=>[]];
        }
    }
    
    //logout -????
    public function deleteLogin($id=false)
    {
        return ['status'=>200, 'data'=>1];
       /* try{
            
            $sth = $this->pdo->prepare("UPDATE `users` SET `status` = 0  WHERE id = :id ");
            $sth->execute(['id'=>$id]);
            $count =  $sth->rowCount();
            if($count>0)
                return ['status'=>200, 'data'=>1];
             else 
                 return ['status'=>500, 'data'=>[]];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'data'=>[]];
        }*/
    }
    
}



