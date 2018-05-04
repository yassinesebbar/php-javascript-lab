
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="grid-w3schools.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div>
        <?php
        require 'dbcrudclass.php';
        $crud = new DbHandler("localhost","mypdo","root","");
        $table = "products";
        $productid = $_GET['id'];
        $statement = "select * from $table where product_id ='$productid'";
        $information = $crud->ReadData($statement);
        echo "<div class='row'>";
        echo "<table class='col-12 col-m-12' style='1px solid black'>";
        echo "<hr>";
        echo "</hr>";

            foreach ($information as $row){
                   echo 'Product_id: '. $row['product_id'].'<br>';
                   echo 'product_name:'. $row['product_name'].'<br>';
                   echo 'product_price:'. $row['product_price'].'<br>';
                   echo 'other_product_details: '. $row['other_product_details'].'<br>';
            }
        ?>

            <form action='/productencms'>
                <button>Ga terug</button>
            </form>
    </div>
</body>
</html>
