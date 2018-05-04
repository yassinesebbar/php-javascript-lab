<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author yassi
 */
class userModel extends model{
    public function register(){
        
          // sanitize POST
        $post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
        
      
        if($post['submit']){
            if($post['name'] == '' || $post['email'] == ''|| $post['password'] == ''){
               return  messages::setMsg('vul alle velden in', 'error');
            }
   
            // INSERT INTO mysql
            $password = md5($post['password']);
            $this->query('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
            $this->bind(':name', $post['name']);
            $this->bind(':email', $post['email']);
            $this->bind(':password', $password);
            $this->execute();
            // verify
            
            if($this->lastInsertId()){
                header('Location: '. ROOT_URL.'users/login');
                
            }
        }
        return;

    }
    public function login(){
         $post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
        if($post['submit']){
            // INSERT INTO mysql
           
            $password = md5($post['password']);
            $this->query('SELECT * FROM users WHERE email = :email AND password = :password');
            $this->bind(':email', $post['email']);
            $this->bind(':password', $password);
            // verify
            $row = $this->single();
            
            if($row){
                $_SESSION[is_logged_in] = true;
                $_SESSION['user_data'] = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "email" => $row['email']
                );
                header('Location: '. ROOT_URL.'shares');
            }else{
                messages::setMsg('Incorrecte Login', 'error');
            }
        }
        return;
    }
    
    
}
