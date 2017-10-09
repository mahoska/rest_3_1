<?php

class CarsModel extends Model{


/*
 get a list of cars (returns only ID, make and model)
 */
public  function listCars() 
{
    try{
        if(!$this->pdo)throw new PDOException();
        $sth = $this->pdo->query("SELECT cars.id, brands.name as brand, models.name as model, cars.img"
            . " FROM brands JOIN"
            . " (cars JOIN models ON models.id = cars.id_model)"
            . " ON brands.id = cars.id_brand");
        $collection['cars'] = $this->getFetchAccoss($sth);
        $sth = $this->pdo->query("SELECT id, name FROM brands"); 
        $collection ['brands'] = $this->getFetchAccoss($sth);
        $sth = $this->pdo->query("SELECT id, name FROM models"); 
        $collection ['models'] =  $this->getFetchAccoss($sth);
        $sth = $this->pdo->query("SELECT id, name FROM colors"); 
        $collection ['colors'] =  $this->getFetchAccoss($sth);
        $result['data'] = $collection;
        $result['status'] = 200;
        return $result;

    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}

 /*
     get detailed information on ID (returns complex type
     from model name, year of manufacture, engine size, color, max speed, price)
  */
public function fullInfoCar($id)
{
    try{
        $sth = $this->pdo->prepare("SELECT cars.id, models.name, cars.year_of_issue, "
            . "cars.engine_capacity, cars.max_speed,cars.price, cars.img "
            . " FROM models JOIN cars ON models.id = cars.id_model"
            . " WHERE cars.id = :car_id");
        $sth->execute(['car_id' => $id]);
        $info = $sth->fetch(\PDO::FETCH_ASSOC);
        $sth = $this->pdo->prepare("SELECT  colors.name "
            . "FROM colors JOIN (color_car JOIN cars ON color_car.id_car=cars.id) "
            . "ON color_car.id_color = colors.id "
            . "WHERE cars.id = :car_id");
        $sth->execute(['car_id' => $id]);
        $colors = [];
        while($res = $sth->fetch(\PDO::FETCH_NUM)){
            $colors[] = $res[0];
        }
        $info['colors']= $colors;
        $car['data'] = $info;
        $car['status'] = 200;
        return  $car;
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}

 /*
 search by parameters (the same complex type as parameters
     as in the previous query. The field "year of issue" is obligatory)
 * $model, $year, $engine_capacity, $color, $max_speed, $price
 */
public function filter($params)
{
    //var_dump($params);
    //    die();
    try{
        if($params['year_from']==0){
            return ['status'=>500, 'data'=>[]];
        }
        $query = "SELECT cars.id, brands.name as brand, models.name as model, cars.img"
            . " FROM brands JOIN"
            . " (cars JOIN models ON models.id = cars.id_model)"
            . " ON brands.id = cars.id_brand WHERE (year_of_issue BETWEEN :year_from AND :year_to)";
        if($params['id_model']!=0){
             $query .= " AND cars.id_model = :id_model";
        }else{
             unset($params['id_model']);
        }
        if($params['id_brand']!=0){
             $query .= " AND cars.id_brand = :id_brand";
        }else{
             unset($params['id_brand']);
        }
        if($params['speed_from']!=0 && $params['speed_to']!=0){
             $query .= " AND (max_speed BETWEEN :speed_from AND :speed_to)";
        }else{
             unset($params['speed_from']);
             unset($params['speed_to']);
        }
        if($params['engine_capacity_from']!=0 && $params['engine_capacity_to']!=0){
             $query .= " AND (engine_capacity BETWEEN :engine_capacity_from AND :engine_capacity_to)";
        }else{
             unset($params['engine_capacity_from']);
             unset($params['engine_capacity_to']);
        }
        if($params['price_from']!=0 && $params['price_to']!=0){
             $query .= " AND (price BETWEEN :price_from AND :price_to)";
        }else{
             unset($params['price_from']);
             unset($params['price_to']);
        }
        $color_id = 0;
        if($params['id_color']!=0){
            $color_id = $params['id_color'];  
        } 
        unset($params['id_color']);
       $sth = $this->pdo->prepare($query);
        $sth->execute($params);
        $collection = [];
        $arr_id = []; 
        $temp = [];
         while($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $temp[] = $res;
            $arr_id[] = $res['id'];
        }
         $collection['data'] = $temp;
         $str_id = implode(",", $arr_id);

       if($color_id!=""){
             $query = "SELECT cars.id, brands.name as brand, models.name as model, cars.img"
            . " FROM brands JOIN"
            . " (cars JOIN models ON models.id = cars.id_model)"
            . " ON brands.id = cars.id_brand "
            . "WHERE cars.id IN "
            . "( SELECT id_car FROM color_car WHERE id_color = :id_color AND id_car IN ($str_id))";
            $sth = $this->pdo->prepare($query);
            $sth->execute(['id_color'=>$color_id]);
            $collection['data'] = $this->getFetchAccoss($sth);
        }else{
             unset($params['id_color']);
        }

        $collection ['status'] = 200;
        return $collection;

    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}


}
