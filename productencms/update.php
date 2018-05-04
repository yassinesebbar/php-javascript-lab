
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="grid-w3schools.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
$productid = $_GET["id"];
require 'dbcrudclass.php';
$crud = new DbHandler("localhost","mypdo","root","");
$table = "products";
$statement = "select * from $table where product_id = '$productid'";
$information = $crud->ReadData($statement);

    
if($_POST){
    $naam = $_POST['pname'];    
    $prijs = $_POST['price'];    
    $detals = $_POST['details'];  
    
    if(isset($_POST['update'])){
     $sqlupdate = "UPDATE $table
                   SET product_name='$naam', product_price='$prijs', other_product_details='$detals'
                   WHERE product_id='$productid'";
     $crud->UpdateData($sqlupdate);
    }
       header("Location: /productencms"); /* Redirect browser */
       exit();
}

echo "<div class='row'>";
echo "<table class='col-12 col-m-12' style='1px solid black'>";
echo "<hr>";
echo "</hr>";

    foreach ($information as $row){
        echo "<form method='post'>";
           echo 'Product_id: '. $row['product_id'].'<br>';
           echo "product_name: <input value='".$row['product_name']."'name='pname'><br>";
           echo "product_price: <input value='".$row['product_price']."'name='price'><br>";
           echo "other_product_details: <input value='".$row['other_product_details']."'name='details'><br>";
           echo "<button>Annuleren</button>";
           echo "<button name='update'>Updaten</button>";
        echo "</form>";
    }   
?>

</body>
</html>
