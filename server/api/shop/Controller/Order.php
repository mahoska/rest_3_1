<?php

class Order extends Controller
{
    //work + client
    public function getOrder($id_user)
    {
        //???проверка на авторизацию
        if(($id_user  = (int) $id_user) > 0){
            $model = new OrderModel();
            $data = $model->getOrders($id_user);
            return $data;
        }else{
            return ['status'=>400, 'data'=>[]];
        }
    }
    
    //work
    public function postOrder($params){
        //???проверка на авторизацию
        //id_car, color, id_user, payment_method
        if(count($params) != 4){
            return ['status'=>400, 'data'=>[]];
        }
       
        $model = new OrderModel();
        return $model->createOrder($params); 
    }
    
    //work  //
    public function putOrder($params){
        //???можно сделать доп проверку - юзера ли это заказ -- 
        if(count($params) != 2 || !($params['status_order']== 0 || $params['status_order']== 1))
            return ['status'=>400, 'data'=>[]];
        $model = new OrderModel();
        return $model->updatetOrder($params); 
    }
}

