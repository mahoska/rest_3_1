<?php
define('br',"<br>");

class RestServer{
    
    use tConvertData;
    
    private $method;
    private $className;
    private $params;
    private $contentType;
    private $statusResponse;
    private $getParams;
    private $serverMethod;

    public function __construct(){
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //courses
        list($s, $a, $d, $db,$sv, $table, $path) = explode('/', $url, 7);
        //home
        //echo $url;
        //list($s, $a, $d, $db, $table, $path) = explode('/', $url, 6);
        
        $this->serverMethod = $_SERVER['REQUEST_METHOD'];
        $this->className = ucfirst($table);
        $this->getParams =  explode('/', $path);

        switch($this->serverMethod)
        {
        case 'GET':
 
            $this->params = explode('/', $path);
            // echo $path;
            $this->method = 'get'.ucfirst($table);
            break;
        case 'POST':
            $this->params = $_POST;
           
            //$json = file_get_contents('php://input'); 
            //$this->params = json_decode($json, JSON_BIGINT_AS_STRING);
            echo json_encode($this->params);die();
            $this->method = 'post'.ucfirst($table);
            break;   
        case 'DELETE':
            $this->params =$this->getParams; //$_GET;
            var_dump($this->params);die();
            $this->method = 'delete'.ucfirst($table);
             break;
        case 'PUT':

            parse_str(file_get_contents("php://input"), $this->params);
            echo json_encode($this->params);die();
            $this->method = 'put'.ucfirst($table);
            break;
        case 'OPTIONS':
            header("Access-Control-Allow-Origin:*");
            header("Access-Control-Allow-Methods:PUT, DELETE");
            header("Access-Control-Allow-Headers: Authorization,Content-Type");
            die();
            break;
        default:
            $this->statusResponse = 406;
        }
        
        if($this->statusResponse != 406){
           $data =  $this->setMethod($this->className,$this->method, $this->params);       
           $responseContent = ($this->setResponse($data))? $this->setResponse($data) : null ;
           $this->getResponse($responseContent);
        }else{
             $this->getResponse();
        }
    }
    
    public function setMethod($class, $action, $param)
    {
        $controller = new $class();
        
        if (!method_exists($class, $action)){
            $this->statusResponse = 405;
            $this->getResponse();
            die();
        }else{
                $dataResponse = $controller->$action($param); 
                $this->statusResponse = $dataResponse['status'];
                $data = $dataResponse['data'];
        }
        return $data;
    }
        
    private function setHeaders()
    {
        if ($this->statusResponse != 200)
        {
            $this->contentType = 'text/html';
        }
        header("HTTP/1.1 ".$this->statusResponse);
        header("Content-Type:".$this->contentType);
    }
    
    private function setResponse($data){
        $lastParam = $this->getParams[count($this->getParams)-1];
        if(strripos($lastParam,'.')===false){
            $this->contentType = DEFAULT_CONTENT_TYPE;
        }
        else{
            $this->contentType = substr($lastParam, strripos($lastParam,'.')+1);
        }
        switch ($this->contentType)
        {
            case 'json':
                $this->contentType = 'application/json';
                //$this->setHeaders();
                return $this->convertDataJson($data);
                break;
            case 'xml':
                $this->contentType = 'text/xml';
                return $this->convertDataXml($data);
                break;
            case 'txt':
                $this->contentType = 'text/plain';
                $this->setHeaders();
                echo $this->convertDataTxt($data);
                die();
                break;
            case 'html':
                $this->contentType = 'text/html';
                $this->setHeaders();
                echo $this->convertDataHtml($data);
                die();
                break;
            default:
                $this->statusResponse = 500;
                $this->getResponse();
                die();
                break;
        }     
    }

    
    public function getResponse($responseContent = null){
        $this->setHeaders();
        if(!is_null($responseContent)){
                echo $responseContent;
        }
        die();
    }

}
