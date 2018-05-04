
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="grid-w3schools.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<form method="get" action="create.php">
    <button type="submit">Create New Product</button>
</form>
<?php
 require 'controller.php';

require 'dbcrudclass.php';
$crud = new DbHandler("localhost","mypdo","root","");
$table = "products";

if($_POST){
    $id = $_POST['product_id'];       
    if(isset($_POST['read'])){
       header("Location: /productencms/read.php?id=".$id); /* Redirect browser */
       exit();
    }
    if(isset($_POST['update'])){
       header("Location: /productencms/update.php?id=".$id); /* Redirect browser */
       exit();
    }
    if(isset($_POST['delete'])){
        $sqlupdate = "DELETE FROM $table WHERE product_id='$id'";
        $crud->DeleteData($sqlupdate);
    }

}
if(isset($_GET['page'])){

    $pagination = $_GET['page'];
    $begin = 0;
    $end = 0;      
    $begin = ($pagination - 1) * 5;
    $end = ($pagination  * 5) - 1;
}
  $sql = "SELECT * FROM $table";  

$statement = "select * from $table limit 1";
$rowcount = "select count(*) from $table limit 1";
$res = $crud->ReadData($sql);
$header = $crud->ReadData($statement);
$nrrows = $crud->ReadData($rowcount);
echo "<div class='row'>";
echo "<table class='col-8 col-m-12' style='1px solid black'>";
echo "<hr>";

$numberofrows;

foreach($nrrows as $row){
    foreach($row as $key => $val){
       $numberofrows = $val; 
    } 
}
$ii = 0;
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
echo "</hr>";
    foreach ($res as $row){  
      
        if(isset($_GET['page'])){
            if($ii >= $begin && $ii <= $end){
                        
          echo "<form action='' method='post'>";
        echo "<tr>";
        foreach ($row as $key => $val){
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
        echo "<td><button type='submit' name='read'><i class='fa fa-file'></i>Read</button> <button type='submit' name='update' class='btn btn-success'><i class='fa fa-edit'></i> Edit</button> <button type='submit' name='delete'><i class='fa fa-trash'></i> Delete</button> </td>";
        echo "</tr>";
        echo "</form>";
                
            }
            $ii++;
            }
            

            
        


    }
echo "</table>";
echo "</div>";


$numberoflinks = ceil($numberofrows / 5);
for($i = 1; $i <= $numberoflinks; $i++){
    echo "<a href='/productencms?page=".$i."'>$i</a>  ";
}

?>
</body>
</html>
