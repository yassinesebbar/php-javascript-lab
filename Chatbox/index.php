<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="style.css">  
        <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous">
        </script>
    </head>
    <body>
        
    
        <div id="wrapper">
            <h1>chatbox</h1>
            <div class="chat_wrapper">
                <div id="chat"></div>
                <form method="POST" id="messageform">
                    <textarea name="message" cols="7" class="textarea"></textarea>
                </form>
            </div>
        </div>
        
        <script>
            
            function LoadChat()
            {
               $.post('handlers/message.php?action=getMessages',function(response){
                 var scrollpos = parseInt($('#chat').scrollTop()) + 520;
                 var scrollHeight = parseInt($('#chat').prop('scrollHeight'));
                 
                $('#chat').html(response);

                if(scrollpos < scrollHeight ){
                    
                 }else{
                    $('#chat').scrollTop( $('#chat').prop('scrollHeight'));
                 }
               }); 
            }
            
            LoadChat();
            
            setInterval(function(){
                LoadChat();
            },500);
            $('.textarea').keyup(function(e){
               if(e.which === 13){
                 $('form').submit();  
               }
            }); 

            $('form').submit(function(){
                var message = $('.textarea').val();
                $.post('handlers/message.php?action=sendMessage&message='+message, function(response){

                    if(response == 1){
                        document.getElementById('messageform').reset();
                        LoadChat();
                    }

                });

                return false;
            });
         
         
        </script>
        
        
    </body>
</html>
