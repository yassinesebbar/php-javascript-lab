<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '../config.php';

session_start();
$_SESSION['username'] = "Yassine Sebbar";


switch($_REQUEST['action']){
   case "sendMessage": 
       $query = $db->prepare("INSERT INTO messages SET user=?, message=?");
       $run = $query->execute([$_SESSION['username'],$_REQUEST['message']]);
       
       if($run){
         echo 1;
         exit;
       }
      
      
    break;
    
   case 'getMessages':
        $query = $db->prepare("SELECT * FROM messages");
        $run = $query->execute();
       
        $rs = $query->fetchAll(PDO::FETCH_OBJ);
        $chat = '';
        foreach($rs as $message){
           $chat .= '<div class="single-message">'
                   . '<strong>'.$message->user.': </strong>'.$message->message
                   . '<span>'.date('h:i a', strtotime($message->date)).'</span>'
                   . '</div>';
        }
        
        echo $chat;
        
    break;
}