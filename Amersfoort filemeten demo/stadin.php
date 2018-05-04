<?php

//Hier staan de richtingen die we gebruiken (in dit geval de richtingen de stad in)
$inrichtingen = [
    5 => ["VerkeerRichting4","VerkeerRichting8", "VerkeerRichting12"],
    11 => ["VerkeerRichting1", "VerkeerRichting5","VerkeerRichting9"],
    12 => ["VerkeerRichting2", "VerkeerRichting8"],
    13 => ["VerkeerRichting1","VerkeerRichting9"],
    1314 => ["VerkeerRichting2", "VerkeerRichting10"],
    14 => ["VerkeerRichting1","VerkeerRichting5", "VerkeerRichting9"],
    1415 => ["VerkeerRichting4","VerkeerRichting8", "VerkeerRichting12"],
    15 => ["VerkeerRichting2","VerkeerRichting69"],
    17 => ["VerkeerRichting2","VerkeerRichting10"],
    19 => ["VerkeerRichting1"],
    25 => ["VerkeerRichting4","VerkeerRichting8"]

];
//Hier staan de kruispunten (tabellen) waarover we beschikken, key is het kruispuntnummer en value is de resource_id van de tabel
$kruispunten = [
    5 => "0077d99e-127c-4c28-acde-c0f337e13065",
    11 => "6b39a68b-54d1-4254-a2ce-af59a8856f3f",
    12 => "fd82625a-747d-4cdd-8698-9ddefe3408e6",
    13 => "261daeeb-78dd-4d25-9b6b-b91ba0554032",
    1314 => "b52c4cbc-f4c4-4aea-9e3f-04285a6fb611",
    14 => "b52c4cbc-f4c4-4aea-9e3f-04285a6fb611",
    1415 => "b52c4cbc-f4c4-4aea-9e3f-04285a6fb611",
    15 => "7b66656a-8133-4695-876d-a7dcecb8481a",
    17 => "8a203b94-b1ec-4271-a55e-54f741eb7f05",
    19 => "4d9e56a6-1b2f-4eb8-9d22-3d9719ba0887",
    25 => "9635ae7f-6dc0-4876-bfce-ee03d534fb38"
];

// Hier worden onze inputs vanaf javascript omgezet naar php variables
$datum = $_REQUEST['datum'];
$begintijd = $_REQUEST['begintijd'];
$eindtijd = $_REQUEST['eindtijd'];
$kruispunt;

//Als alle variabelen binnen zijn (if isset) dan beginnen onze for loops door de richtingen en kruispunten heen te loopen
if (isset($_REQUEST['datum']) && isset($_REQUEST['begintijd']) && isset($_REQUEST['eindtijd'])) {
    $returnVariable = array();

//door de kruispunten lijst loopen en de resource_id meegeven aan de query later.
    foreach ($kruispunten as $key => $value) {
        $kruispunt = $value;
        $sleutel = $key;

//Van elk kruispunt alleen de richingen die in de tabel staan (de richtingen die de stad in gaan of op de ring blijven) kiezen.
        $richtingen = $inrichtingen[$sleutel];
        foreach($richtingen as &$richting) {
            $richting = '"'.$richting.'"';
        }

        $lijst = implode(",  ", $richtingen);
            $result = [];
//Wij versturen de resultaten, dmv een query: som van de inrichten van de kruispunten
            $query = "SELECT SUM($richting) FROM \"$kruispunt\" WHERE to_char(\"Date\", 'YYYY-MM-DD') = '$datum' and tijd between '$begintijd' and '$eindtijd'";
            $json = file_get_contents('http://fiwarelab.ckan.nl/api/action/datastore_search_sql?sql=' .urlencode($query));
            $result = (int) json_decode($json, true)['result']['records'][0]['sum'];
             $returnVariable[$key]["sommen"] = $result;

    }
//Versturen van de antwoorden uit de query (returnVariabele is de lijst met antwoorden), wat in de echo staat kan dashboard.js uitlezen.
    header('Content-Type: application/json');
    echo json_encode($returnVariable);

}