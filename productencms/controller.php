<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require 'dbcrudclass.php';
class view extends DBhandler{
    
  function postHandler($table,$alles){
              $connection = new DbHandler("localhost","mypdo","root","");

        if(isset($_POST['read'])){
           $id = $_POST['product_id'];       
           header("Location: /productencms/read.php?id=".$id); /* Redirect browser */
           exit();
        }
        if(isset($_POST['update'])){
           $id = $_POST['product_id'];       
           header("Location: /productencms/update.php?id=".$id); /* Redirect browser */
           exit();
        }
        if(isset($_POST['delete'])){
            $id = $_POST['product_id'];       
            $sqlupdate = "DELETE FROM $table WHERE product_id='$id'";
            $crud->DeleteData($sqlupdate);
        }
        if(isset($_POST['deletemore'])){
            if(!empty($_POST['deleteselected'])) {
                $selected = $_POST['deleteselected'];
                foreach($selected as $check) {
                    if($check == "all"){
                        foreach($alles as $key){
                           $sqlupdate = "DELETE FROM $table WHERE product_id='$key'";
                            $connection->DeleteData($sqlupdate); 
                        }
                    }else{
                      $sqlupdate = "DELETE FROM $table WHERE product_id='$check'";
                      $connection->DeleteData($sqlupdate);  
                    }
                    
                }
            }
        }

  }
    function deletemultiple(){
        
    }
    function paginationSearch($table){
        if(isset($_GET['page'])){
          if(isset($_GET['zoek'])){$zoekterm = $_GET['zoek'];}else{$zoekterm = "";}    
          if($_GET['page'] == 1){
             $sql = "SELECT * FROM $table WHERE product_name LIKE '%". $zoekterm."%' LIMIT 5 OFFSET 0 ";  
          }else{
              $pagination = $_GET['page'];     
              $begin = 5;
              $end = ($pagination  * 5) - 5;
              $sql = "SELECT * FROM $table WHERE product_name LIKE '%". $zoekterm."%' LIMIT $end,$begin";
          } 
        }else{
            if(isset($_GET['zoek'])){
                $sql = "SELECT * FROM $table  WHERE product_name LIKE '%". $_GET['zoek']."%' LIMIT 5 OFFSET 0";  
            }else{$sql = "SELECT * FROM $table LIMIT 5 OFFSET 0  ";}
        }
        return $sql;
    }
    
    function pagination($table){
        if(isset($_GET['zoek'])){
            $rowcount = "SELECT count(*) FROM $table  WHERE product_name LIKE '%". $_GET['zoek']."%' limit 1";  
        }else{
            $rowcount = "select count(*) from $table limit 1";
        } 
        $connection = new DbHandler("localhost","mypdo","root","");
        $hoeveelrows = $connection->ReadData($rowcount);
        
        foreach($hoeveelrows as $row){
            foreach($row as $key => $val){
               $numberofrows = $val; 
            } 
        }
        
        $numberoflinks = ceil($numberofrows / 5);
        $html = "";
            for($i = 1; $i <= $numberoflinks; $i++){
                if(isset($_GET['zoek'])){
                    $html .= "<a href='/productencms?page=".$i."&zoek=".$_GET['zoek']."'>$i</a>  ";
                }else{
                $html .=  "<a href='/productencms?page=".$i."'>$i</a>  ";
                }
            }
        return $html;

    }
}
