<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of share
 *
 * @author yassi
 */
class shareModel extends model{
      public function Index(){
        $this->query("SELECT * FROM shares ORDER BY create_time DESC");
        $rows = $this->resultSet();
        return $rows;
    }
    
    public function add(){
        // sanitize POST
        $post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
        if($post['submit']){
            // INSERT INTO mysql
             if($post['title'] == '' || $post['body'] = ''|| $post['link'] = ''){
               return  messages::setMsg('please fil in all fields', 'error');
            }
            $this->query('INSERT INTO shares (title, body, link, user_id) VALUES (:title, :body, :link, :user_id)');
            $this->bind(':title', $post['title']);
            $this->bind(':body', $post['body']);
            $this->bind(':link', $post['link']);
            $this->bind(':user_id',1);
            $this->execute();
            // verify
            
            if($this->lastInsertId()){
                header('Location: '. ROOT_URL.'shares');
                
            }
        }
        return;
    }
    
}
