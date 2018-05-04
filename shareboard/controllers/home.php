<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author yassi
 */
class home extends controller {
    protected function Index(){
        $viewmodel = new homeModel();
        $this->returnView($viewmodel->Index(), true);
    }
}
