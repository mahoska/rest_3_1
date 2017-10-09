<?php

class OrderModel extends Model{



/*
function of pre-ordering a car
     (Car ID, name, surname of the buyer, payment method.
     Payment method enumeration of "credit card", "cash")
*/
public function createOrder($params)
{
    try{
        $sth = $this->pdo->prepare('INSERT INTO orders (id_car, color, id_user, payment_method) '
                . 'VALUES (:id_car, :color, :id_user, :payment_method)');
        $sth->execute($params);
        if($this->pdo->lastInsertId()>0)
             return ['status'=>200, 'data'=>1];
         else 
             return ['status'=>500, 'data'=>[]];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}

public function getOrders($id)
{
    try{
        $sth = $this->pdo->prepare('SELECT cars.id as id_car, orders.id as id_order,'
                . '  models.name, cars.year_of_issue, cars.engine_capacity, '
                . 'cars.max_speed,cars.price, cars.img, orders.color, orders.status '
                . 'FROM models JOIN (cars JOIN orders ON cars.id = orders.id_car) ON models.id = cars.id_model '
                . 'WHERE orders.id_user = :id_user');
        $sth->execute(['id_user' => $id]);
        $orders['data'] = $this->getFetchAccoss($sth);
        $orders ['status'] = 200;
        return $orders;
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}

//id_user, id_order
public function updatetOrder($params)
{
     try{
        $sth = $this->pdo->prepare("UPDATE `orders` SET `status` =". $params['status_order'] ." WHERE `orders`.`id` = :id_order");
        $sth->execute(['id_order' => $params['id_order']]);
        $count =  $sth->rowCount();
        if($count>0)
            return ['status'=>200, 'data'=>[]];
         else 
             return ['status'=>500, 'data'=>[]];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}

}
