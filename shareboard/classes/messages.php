<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messages
 *
 * @author yassi
 */
class messages {
    public static function setMsg($text,$type){
        if($type == 'error'){
            $_SESSION['errorMsg'] = $text;
        }else{
            $_SESSION['successMsg'] = $text; 
        }
    }
    public static function display(){
        if(isset($_SESSION['errorMsg'])){
           echo '<div class="alert alert-danger">'.$_SESSION['errorMsg'].'</div>';
           unset($_SESSION['errorMsg']);
        }
        if(isset($_SESSION['successMsg'])){
           echo '<div class="alert alert-succes">'.$_SESSION['successMsg'].'</div>';
           unset($_SESSION['successMsg']);
        }
    }
    
    
}
