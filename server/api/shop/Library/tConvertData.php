<?php

trait tConvertData
{
    public function convertDataJson($data){
        return json_encode($data);
    }

    public function convertDataTxt($data){
        return print_r($data);
    }
    
    public function convertDataXml($data){
        // creating object of SimpleXMLElement
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        // function call to convert array to xml
        $this->arrayToXml($data,$xml_data);
        return $xml_data->asXML();
    }
    
     public function convertDataHtml($data){
        ob_start();
        require_once "View/template.phtml";
        return ob_get_clean(); 
     }
    
    private function arrayToXml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            if( is_array($value) ) {
                $subnode = $xml_data->addChild($key);
                 $this->arrayToXml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

}
   
