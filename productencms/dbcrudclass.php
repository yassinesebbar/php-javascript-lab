<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */    
    class DBhandler{
        var  $localhost; 
        var  $DBname;
        var  $root;
        var  $wachtwoord;
        var  $conn;
        var  $errmode;
        function __construct($localhost,$DBname,$root,$wachtwoord) {
            $this->localhost = $localhost;
            $this->DBname = $DBname;
            $this->root = $root;
            $this->wachtwoord = $wachtwoord;
            $this->conn = new PDO("mysql:host=$this->localhost;dbname=$this->DBname", $this->root, $this->wachtwoord);
            $this->errmode = $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        function CreateData($sql){
            try {
                  $this->errmode;
                  return  $this->conn->exec($sql);
            }
            catch(PDOException $e){
                echo $sql . "<br>" . $e->getMessage();
            }
         }
        function ReadData($sql){
            try {
                $this->errmode;
                $stm =  $this->conn->prepare($sql);
                // use exec() because no results are returned
                $stm->execute();
                $result = $stm->fetchALL(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e){
                echo $sql . "<br>" . $e->getMessage();
            }
         }
        function UpdateData($sql){
            try {
                  $this->errmode;
                  return  $this->conn->exec($sql); 
            }
            catch(PDOException $e){
                echo $sql . "<br>" . $e->getMessage();
            }
         }
        function DeleteData($sql){
            try {
                $this->errmode;
                return  $this->conn->exec($sql);
            }
            catch(PDOException $e){
                echo $sql . "<br>" . $e->getMessage();
            } 
         }

    }

