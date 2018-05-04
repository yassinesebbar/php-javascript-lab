<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of shares
 *
 * @author yassi
 */
class shares extends controller{
    protected function Index(){
        $viewmodel = new shareModel();
        $this->returnView($viewmodel->Index(), true);
    }
    protected function add(){
        If(!isset($_SESSION['is_logged_in'])){
            header('Location: '.ROOT_URL.'shares');
        } else {
            $viewmodel = new shareModel();
             $this->returnView($viewmodel->add(), true);  
        }

    }
}
