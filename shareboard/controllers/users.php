<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of users
 *
 * @author yassi
 */
class users extends controller{
    protected function register(){
       $viewmodel = new userModel();
       $this->returnView($viewmodel->register(), true);
    }
    protected function login(){
       $viewmodel = new userModel();
       $this->returnView($viewmodel->login(), true);
    }
    protected function logout(){
        unset($_SESSION['is_logged_in']);
        unset($_SESSION['user_data']);
        session_destroy();
        header('location: ' .ROOT_URL);
    }
}
