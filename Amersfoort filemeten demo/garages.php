<?php
//lijst met de garages als key en de resource_id als value, zodat de juiste databasetabel aangeroepen kan worden a.d.h.v. de key.
$garages = [
    "argonaut" => "407fbc8e-8e0b-4d97-8b26-0278f7b64f10",
    "beestenmarkt" => "e39cfa92-ee86-4df1-b609-17afd206474a",
    "flintplein" => "80636f3e-a30c-478c-a7de-842030f16065",
    "koestraat" => "d22149dc-1334-466e-898d-08df545a90d9",
    "soeverein" => "40105df0-bf0b-4f25-982a-805583f4ea1d",
    "stjorisplein" => "ced40d54-38b7-4ab5-89a4-d21885f11ddd",
    "stadhuisplein" => "34e67e20-49c8-44b5-b168-00a78a3a687c"
];
//de 3 variabelen, datum, begintijd en eindtijd die bepalen welke data wordt aangeroepen door de query.
$datum = $_REQUEST['datum'];
$begintijd = $_REQUEST['begintijd'];
$eindtijd = $_REQUEST['eindtijd'];

//Als de datum, begintijd en eindtijd zijn ontvangen, ga dan verder
if (isset($_REQUEST['datum']) && isset($_REQUEST['begintijd']) && isset($_REQUEST['eindtijd'])) {
    $returnVariable = array();

//loop die door de garages tabel loopt, voor elke waarde wordt een query uitgevoerd. resultaat van de query wordt in de result array gezet.
    foreach ($garages as $key => $value) {
        $garage = $value;
        $sleutel = $key;
        $result = [];

//query die de bezettingsgraad kort selecteert van de tabel waar de foreach- loop nu op staat. Alleen de data op de ontvangen datum en tussen de begintijd en eindtijd wordt geselecteerd.
//vervolgens wordt deze query toegevoegd aan de api-link om de data op te halen, in JSON-formaat.
//de json wordt in de returnVariabele lijst gezet.
        $query = "SELECT \"bezettingsgraad kort\" FROM \"$garage\"WHERE to_char(\"Tijd\", 'YYYY-MM-DD') = '$datum' and to_char(\"Tijd\", 'HH24:MI') between '$begintijd' and '$eindtijd'";
        $json = file_get_contents('http://fiwarelab.ckan.nl/api/action/datastore_search_sql?sql=' . urlencode($query));
        $result = json_decode($json, true)['result']['records'];
        $returnVariable[$key] = $result;
    }
//de returnVariabele lijst (de lijst met alle json antwoorden uit de api-link) wordt ge-echoed, alleen dit kan door dashboard.js worden ontvangen.
    header('Content-Type: application/json');
    echo json_encode($returnVariable);
}
