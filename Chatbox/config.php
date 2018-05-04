<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dbhost = 'locahost';
$dbname = 'chat';
$dbuser = '';
$dbpass = '';


try{
   $db = new PDO("mysql:dbhost=$dbhost;dbname=$dbname", "$dbuser","$dbpass");
 
} catch (PDOExeption $e) {
    echo $e->getMessage();
}