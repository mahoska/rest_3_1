<?php

class Auth extends Controller
{
    
    public function getAuth($params = false)
    {
    }
    
    public function postAuth($params = false)
    {
        if(count($params) != 4){
            return ['status'=>400, 'data'=>[]];
        }

        $params['status'] = 1;
        $params['time_life'] =  time()+LIFE_ACTIVE_LOGIN;//1507554709
        $pass = md5($params['password']);
        $str= '123';
        $str = md5($str);
        $pass_db = md5($params['password'].$str);
        $params['password'] = $pass_db;

        $model = new AuthModel();
        return $model->createUser($params);
    }
    
    //active+life
     public function putAuth($params = false)
    {
            if(count($params) != 2){
                return ['status'=>400, 'data'=>[]];
            }
            $params['time_life'] =  time()+LIFE_ACTIVE_LOGIN;
            $pass = md5($params['password']);
            $str= '123';
            $str = md5($str);
            $pass_db = md5($params['password'].$str);
            $params['password'] = $pass_db;

            $model = new AuthModel();
            return $model->setLogin($params);
    }
    
    
    //don't work!
     public function deleteAuth($params = false)
    {
         return ['status'=>200, 'data'=>$params];
         /*if(count($params) != 1){
                return ['status'=>400, 'data'=>[]];
         }*/
        // $model = new AuthModel();
        // return $model->deleteLogin((int)$params);
    }
}
