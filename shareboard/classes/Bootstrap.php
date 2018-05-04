<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Bootstrap{
    private $controller;
    private $action;
    private $request;
    
    public function __construct($request) {
        $this->request = $request ;
        if($this->request['controller'] == ""){
            $this->controller = "home";
        }else{
            $this->controller = $this->request['controller'];
        }
        if($this->request['action'] == ""){
            $this->action = "index";
        }else{
            $this->action = $this->request['action'];
        }
    }
    
    public function createController(){
        // check Class
        if(class_exists($this->controller)){
           $parents = class_parents($this->controller);
           // check extend
           if(in_array("controller", $parents)){
              if(method_exists($this->controller, $this->action)){
                  return new $this->controller($this->action, $this->request);
              }else{
                  // Method does not exist
                  echo "<h1> method does not exist</h1>";
                  return;
              } 
           }else{
               //Base controller does not found
                echo "<h1>Base controller not found</h1>";
                return; 
           }
        }else{
             //Controller class does not exist
                echo "<h1>controller class does not exist</h1>";
                return; 
        }
    }
}