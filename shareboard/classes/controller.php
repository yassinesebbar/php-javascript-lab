<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author yassi
 */
abstract class controller {
    protected $request;
    protected $action;
    
    public function __construct($action, $request) {
        $this->action = $action;
        $this->request = $request;
    }
    
    public function executeAction(){
        return $this->{$this->action}();
    }
    
    protected function returnView($viewmodel, $fullview){
       $view = 'views/'. get_class($this). '/' . $this->action. '.php'; 
       
       if($fullview){
           require('views/main.php');  
       }else{
           require($view);
       }
    }
}
