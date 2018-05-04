<?php

$garages = [
    5 => "0077d99e-127c-4c28-acde-c0f337e13065",
    11 => "6b39a68b-54d1-4254-a2ce-af59a8856f3f",
    12 => "fd82625a-747d-4cdd-8698-9ddefe3408e6",
    13 => "261daeeb-78dd-4d25-9b6b-b91ba0554032",
    14 => "b52c4cbc-f4c4-4aea-9e3f-04285a6fb611",
    15 => "7b66656a-8133-4695-876d-a7dcecb8481a",
    17 => "8a203b94-b1ec-4271-a55e-54f741eb7f05",
    19 => "4d9e56a6-1b2f-4eb8-9d22-3d9719ba0887",
    25 => "9635ae7f-6dc0-4876-bfce-ee03d534fb38"
];

$datum = $_REQUEST['datum'];
$begintijd = $_REQUEST['begintijd'];
$eindtijd = $_REQUEST['eindtijd'];

if (isset($_REQUEST['datum']) && isset($_REQUEST['begintijd']) && isset($_REQUEST['eindtijd'])) {
    $returnVariable = array();

    foreach ($garages as $key => $value) {
        $kruispunt = $value;
        $sleutel = $key;
        $result = [];

        $query1 = "SELECT latitude FROM \"$kruispunt\"  WHERE to_char(\"Date\", 'YYYY-MM-DD') = '$datum' and tijd between '$begintijd' and '$eindtijd' GROUP BY latitude";
        $json1 = file_get_contents('http://fiwarelab.ckan.nl/api/action/datastore_search_sql?sql=' . urlencode($query1));
        $result = json_decode($json1, true)['result']['records'][0]['latitude'];

        $returnVariable[$key]["latitude"] = $result;


    }

    header('Content-Type: application/json');
    echo json_encode($returnVariabele);

}

?>