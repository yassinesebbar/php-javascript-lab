
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="grid-w3schools.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php
require 'controller.php';
$crud = new DbHandler("localhost","mypdo","root","");
$table = "products";

if($_POST){
    view::postHandler($table,$alles);
}
?>
    <div class="row">
        <form class='nopadding col-5 col-m-12'  method="get" action="index.php">
            <input placeholder=" zoekterm" name="zoek" type="text"> 
            <button type="submit">zoeken</button>
        </form>
        <form class='nopadding col-2 col-m-12'  method="get" action="create.php">
            <button type="submit">Create New Product</button>
        </form>   
         
    </div>
<?php
     if(isset($_GET['zoek'])){
?>
    <form  method="" action="http://localhost/productencms/">
      <button type="submit"><i class='fa fa-times'></i> <?= $_GET['zoek'] ?></button>
    </form>
<?php
    }
?>
    
 <form class=' col-12 col-m-12' id='deletes'  method="post">
            <button name="deletemore" type="submit">Delete selected</button>
 </form>  
<?php
echo "<div  class='row'>";
echo "<table class='col-8 col-m-12' style='1px solid black'>";
$statement = "select * from $table limit 1";
$header = $crud->ReadData($statement);
echo "<td style='border:1px solid black' ><input form='deletes' name='deleteselected[]' type='checkbox' value='all'>";
foreach($header as $row){
    foreach($row as $key => $val){
        if($key == 'other_product_details'){
            echo "<td style='border:1px solid black' >$key</td>";
            echo "<td  style='border:1px solid black' >Actions</td>";
        }else{
            echo "<td style='border:1px solid black' >$key</td>";
        } 
    } 
}
input://php
echo "</hr>";
    $sql = view::paginationSearch($table);
    $res = $crud->ReadData($sql);

    foreach ($res as $row){  
        echo "<form action='' method='post'>";
        echo "<tr>";
        $counter = true;
        foreach ($row as $key => $val){
            if($counter == true){
                echo "<td style='border:1px solid black' ><input form='deletes' name='deleteselected[]' type='checkbox' value='$val'></td>";
                $alles[] = $val;
                $counter = false;
            }
            echo "<td  style='border:1px solid black'>";
                if($key == 'product_price'){
                   $bedrag = str_replace(".",",","$val");
                    echo "&euro;$bedrag";
                }else if($key == 'product_id'){
                    echo "$val <input style='display:none' name='$key' type='text' value='$val' readonly='readonly' >";
                }else{
                    echo "$val";
                }
          
            echo "</td>";
        }
        echo "<td><button type='submit' name='read'><i class='fa fa-file'></i> Read</button> <button type='submit' name='update' class='btn btn-success'><i class='fa fa-edit'></i> Edit</button> <button type='submit' name='delete'><i class='fa fa-trash'></i> Delete</button> </td>";
        echo "</tr>";
        echo "</form>";

    }
    
  
echo "</table>";
echo "</div>";
echo  view::pagination($table);

?>

</body>
</html>
