

            <form method='post'>
                product : <input type='text' class="" name='product_name'>
                prijs : <input type='text' class="" name='product_price'>
                product uitleg: <input type='text' class="" name='other_product_details'>
                <input type='submit' name='annuleren' class='btn' value='Annuleren'>
                <input type='submit' name='create' class='btn' value='Create'>
            </form>

<?php
require 'dbcrudclass.php';
$crud = new DbHandler("localhost","mypdo","root","");
$table = "products";
if($_POST){
    $naam = $_POST['product_name'];    
    $prijs = $_POST['product_price'];    
    $detals = $_POST['other_product_details'];  

    if(isset($_POST['create'])){
        $sqlupdate = "INSERT INTO $table (product_name, product_price, other_product_details)
                        VALUES ('$naam', '$prijs', '$detals')";
        $crud->CreateData($sqlupdate);      
    }
    if(isset($_POST['annuleren'])){
        header("Location: /productencms"); /* Redirect browser */
        exit();     
    }

}
