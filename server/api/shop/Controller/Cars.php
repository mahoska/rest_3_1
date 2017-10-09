<?php 

class Cars extends Controller
{
    //work + client
    public function getCars($params = false)
    {
        if(count($params) == 1){
            if($params[0]==""){
                $model = new CarsModel();
                $data = $model->listCars();
                return $data;
            }else{
                $lastParam = $params[0];
                if (strripos($lastParam,'.')!==false){
                    $firstPart = substr($lastParam, 0, strripos($lastParam,'.'));
                    if($firstPart==""){
                        $model = new CarsModel();
                        $data = $model->listCars();
                        return $data;
                    }
                    else if(($id  = (int) $firstPart) > 0){
                        $model = new CarsModel();
                        $data = $model->fullInfoCar($id);
                        return $data;
                    }else{
                        return ['status'=>400, 'data'=>[]];
                    }
                }else{
                    return ['status'=>400, 'data'=>[]];
                }
            }
        }else{
            $lastParam = $params[count($params)-1];
            if ($lastParam=="" || (strripos($lastParam,'.')!==false &&  substr($lastParam, 0, strripos($lastParam,'.')=="")) ) {
                unset($params[count($params)-1]);
                foreach ($params as &$value) {
                    if($value=='-')$value="";
                }
                $keysFilterParams = [];
                $i=0;

                $KEYS_FILTER_PARAMS=[
                    'year_from', 
                    'year_to',
                    'id_model',
                    'id_brand',
                    'engine_capacity_from',
                    'engine_capacity_to',
                    'speed_from', 
                    'speed_to',
                    'price_from',
                    'price_to',
                    'id_color'
                    ];
                    if(count($params)!= count($KEYS_FILTER_PARAMS)){
                        return ['status'=>400, 'data'=>[]];
                    }
                foreach ($params as &$value) {
                    if($value == "") $value=0;
                    else if (!($value = (int)$value)>0){
                        return ['status'=>400, 'data'=>[]];
                    }
                    $keysFilterParams[$KEYS_FILTER_PARAMS[$i++]] = $value;
                }
                $model = new CarsModel();
                $data = $model->filter($keysFilterParams);
                return $data;
            }else{
                return ['status'=>400, 'data'=>[]];
            }
        } 
    }


    public function postCars($params  = false){}

        public function putCars($params  = false){}

        public function deleteCars($params  = false){}

}
