//Dit is alle logica van het civity dashboard, hierin worden o.a. tekenfuncties, requests naar .php files, de kaart, enz. beschreven.
//Uitleg over de keuzes die gemaakt zijn in deze code wordt in de documentatie beschreven.

//Input: link naar osm.dataplatform.nl. SetView staat op Amersfoort en zoom staat op 14.
//Output: Kaart van civity, zichtbaar in het dashboard (Als andere kaart gewenst is, verander de link)
//Functie: Crëeren van kaartobject en toevoegen van de civity kaartlayer, zodat er een kaart verschijnt in het dashboard.
var map = L.map('map').setView([52.154727, 5.387218], 15);
L.tileLayer('https://osm.dataplatform.nl/osm/{z}/{x}/{y}.png', {
	maxZoom: 18,
	attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
		'<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
		'Imagery © <a href="http://mapbox.com">Mapbox</a>',
	id: 'mapbox.streets'
}).addTo(map);




//Input: lijst met aantallen (grades), 0-100 auto's, 100-200 auto's, 200-300 auto's, 300-500 auto's en 500+ auto's wordt in de legenda getoond.
//Output: Legenda met kleuren en aantallen van het verkeer, gehaald uit de inputlijst(grades).
//Functie: Maken van legenda die duidelijk aangeeft wat de wegkleuren inhouden en wordt toegevoegd aan het kaartobject.
var legend = L.control({position: 'bottomright'});
legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0, 100, 200, 300, 500],
        labels = [];

        div.innerHTML= "<p>Gemiddelde aantal <br>auto's per uur</p>";
    for (var i = 0; i < grades.length; i++) {
        div.innerHTML +=
				'<div class="legendaMap"><i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
				grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '</div>' : '+');
    }
    return div;
};
legend.addTo(map);




//Input: getal uit lijst(grades)
//Output: Hashcode van de kleur die hoort bij een bepaald getal uit de lijst.
//Functie: Omvormen van getal naar getal-kleur combinatie en returnen naar legenda- functie hierboven.
function getColor(d) {
    return d > 500 ? '#FF0000' :
        d > 300  ? '#FFA500' :
            d > 200  ? '#FFFF00' :
                d > 100  ? '#008000' :
                                '#7CFC00';
}



//Dit gedeelte werkt hetzelfde als hierboven, alleen is de lijst(grades) nu bezettingsgraad van parkeergarages in procenten
//Dus lijst met 0-20%, 20-40%, 40-60%, 60-80% en 80%+
var legenda = L.control({position: 'bottomleft'});
legenda.onAdd = function (map){
    var div = L.DomUtil.create('div', 'info legend'),
        grades = [0,20,40,60,80],
        labels = [];

        div.innerHTML= "<p>Gemiddelde bezettingsgraad <br>per uur in %</p>";
    for (var i = 0; i<grades.length; i++) {
        div.innerHTML +=
				'<div class="legendaMap"><i style="background:' + getKleur(grades[i] + 1) + '"></i> ' +
				grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '</div>' : '+');
    }
    return div;
};
legenda.addTo(map);

//Zelfde functie als getColor(d), maar dan met %-en in de lijst(grades).
function getKleur(d) {
    return d > 80 ? '#FF0000' :
        d > 60  ? '#FFA500' :
            d > 40  ? '#FFFF00' :
                d > 20  ? '#008000' :
                    '#7CFC00';
}



//Input: Button click van de IN-knop en ophalen van de begintijd, eindtijd en datum die geselecteerd zijn op het dashboard.
//Output:   1. Halen van data door een AJAX-request naar stadin.php.
//          2. Lijst van keys(dit zijn de keys van alle wegstukken die ingekleurd moeten worden, 1314 is bijv. van kruispunt 13 naar 14 de som vd. richtingen)
//          3. Door keys loopen en de sommen van de stadin richtingen in de stadin lijst doen
//          4. Nog steeds door keys loopen, als het geen stad in is dan wordt deze som in de kleurenarr gedaan. Dit zijn dus de ringwegen en de sommen daarvan.
//          5. Sturen van de data van punt 3 naar functie tekenStadIn, met als parameters de stadin lijst en het verschil van de begin- en eindtijd(verschiluren).
//          6. Sturen van de data van punt 4 naar functie tekenRing, met als parameters de kleurenarr lijst en het verschil van de begin- en eindtijd(verschiluren).
//Functie: Het ophalen van data en deze doorsturen naar de tekenfuncties voor de ring en de stadin, afhankelijk van de geselecteerde datum en tijden.
    $(".info").click(function() {
        var http = new XMLHttpRequest();
        var begintijd = $('.begintijd').html();
        var eindtijd = $('.eindtijd').html();
        var datum = $('.datum').val();
        var beginuren = $('.beginuren').html();
        var einduren = $('.einduren').html();
        var verschiluren = einduren - beginuren;

        // Begin punt 1.
        var url = "stadin.php?datum=" + datum + '&' + 'begintijd=' + begintijd + '&' + 'eindtijd=' + eindtijd;

        http.open("GET", url, true);

        http.onreadystatechange = function () {
            if (http.readyState === 4 && http.status === 200) {
                var response = JSON.parse(http.responseText);

                //begin punt 2.
                var keys = ["5", "11", "12", "13", "1314", "14", "1415", "15", "17", "19", "25"];
                var kleurenarr = [];
                var stadin = [];
                var i, len;
                len = keys.length;

                for (i = 0; i < len; i++) {
                    sommen = "";
                    latitude = "";
                    longitude = "";
                    stad = "";

                    //begin punt 3.
                    if (i == 1 || i == 3 || i == 5 || i == 7) {
                        stad += response[keys[i]].sommen;
                        stadin.push(stad);
                    }
                    //begin punt 4.
                    else {
                        sommen += response[keys[i]].sommen;
                        kleurenarr.push(sommen);
                    }
                }
                //begin punt 5.
                tekenStadIn(stadin, verschiluren);
                //begin punt 6.
                tekenRing(kleurenarr, verschiluren);
            }
        }
        http.send();
    });




//Input: stadin lijst en verschil tussen begin- en eindtijd(verschiluren)
//Output:       1. 4 lijsten:   - stadbeginlat: latitude van beginpunt van weg.
//                              - stadbeginlong: longitude van beginpunt van weg.
//                              - stadeindlat: latitude van eindpunt van weg.
//                              - stadeindlong: longitude van eindpunt van weg.
//              2. 4x loopen, omdat de lijsten bestaan uit 4 entries.
//              3. Bepalen of het gemiddelde aantal auto's per uur (som van alle geselecteerde uren / verschil begin- en eindtijd) voldoet aan de statement.
//              4. Afhankelijk van het gemiddelde wordt er een L.Routing.control- object gemaakt
//              5. In dit object wordt de index van de lijsten meegegeven (dus de begin- en eindpunten)
//              6. De color is per if- statement anders, hoe hoger het gemiddelde hoe roder de kleur van het object wordt.
//Functie: Maken van een L.Routing.control- object met een bepaalde kleur, afhankelijk van de drukte, en deze tonen op het dashboard.
function tekenStadIn(stadin, verschiluren) {

    var j;
    //begin punt 1.
    stadbeginlat = [52.155695, 52.152681, 52.152759, 52.155481];
    stadbeginlong = [5.383764, 5.386402, 5.390932, 5.394628];
    stadeindlat = [52.156061, 52.152959, 52.153374, 52.155139];
    stadeindlong = [5.386002, 5.386693, 5.390041, 5.395246];

    //begin punt 2.
    for (j = 0; j < 4; j++) {
        //begin punt 3.
        if (stadin[j] / verschiluren <= 100) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(stadbeginlat[j], stadbeginlong[j]),
                    L.latLng(stadeindlat[j], stadeindlong[j])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "LAWNGREEN", opacity: 2, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (stadin[j] / verschiluren > 100 && stadin[j] / verschiluren <= 200) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(stadbeginlat[j], stadbeginlong[j]),
                    L.latLng(stadeindlat[j], stadeindlong[j])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "GREEN", opacity: 1, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (stadin[j] / verschiluren > 200 && stadin[j] / verschiluren <= 300) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(stadbeginlat[j], stadbeginlong[j]),
                    L.latLng(stadeindlat[j], stadeindlong[j])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "YELLOW", opacity: 2, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (stadin[j] / verschiluren > 300 && stadin[j] / verschiluren <= 500) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(stadbeginlat[j], stadbeginlong[j]),
                    L.latLng(stadeindlat[j], stadeindlong[j])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "ORANGE", opacity: 2, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (stadin[j] / verschiluren > 500) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(stadbeginlat[j], stadbeginlong[j]),
                    L.latLng(stadeindlat[j], stadeindlong[j])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "RED", opacity: 1, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
    }
}



//Input: kleurenarr lijst en verschil tussen begin- en eindtijd(verschiluren)
//Output:       1. 4 lijsten:   - beginlatituden: latitude van beginpunt van weg.
//                              - beginlongituden: longitude van beginpunt van weg.
//                              - eindlatituden: latitude van eindpunt van weg.
//                              - eindlongituden: longitude van eindpunt van weg.
//              2. 7x loopen, omdat de lijsten bestaan uit 7 entries.
//              3. Bepalen of het gemiddelde aantal auto's per uur (som van alle geselecteerde uren / verschil begin- en eindtijd) voldoet aan de statement.
//              4. Afhankelijk van het gemiddelde wordt er een L.Routing.control- object gemaakt
//              5. In dit object wordt de index van de lijsten meegegeven (dus de begin- en eindpunten)
//              6. De color is per if- statement anders, hoe hoger het gemiddelde hoe roder de kleur van het object wordt.
//Functie: Maken van een L.Routing.control- object met een bepaalde kleur, afhankelijk van de drukte, en deze tonen op het dashboard.

function tekenRing(kleurenarr, verschiluren) {

    var i;
    //begin punt 1.
    beginlatituden = [52.162489, 52.155803, 52.152759, 52.155041, 52.156825, 52.149242, 52.152739];
    beginlongituden = [5.370564, 5.383563, 5.390932, 5.395361, 5.398112, 5.384121, 5.373253];
    eindlatituden = [52.155695, 52.152589, 52.152681, 52.152756, 52.155167, 52.152681, 52.155681];
    eindlongituden = [5.383558, 5.386295, 5.386402, 5.390953, 5.395519, 5.386388, 5.383492];

    //begin punt 2.
    for (i = 0; i < 7; i++) {
        //begin punt 3.
        if (kleurenarr[i] / verschiluren <= 100) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(beginlatituden[i], beginlongituden[i]),
                    L.latLng(eindlatituden[i], eindlongituden[i])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "LAWNGREEN", opacity: 2, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (kleurenarr[i] / verschiluren > 100 && kleurenarr[i] / verschiluren <= 200) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(beginlatituden[i], beginlongituden[i]),
                    L.latLng(eindlatituden[i], eindlongituden[i])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "GREEN", opacity: 1, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (kleurenarr[i] / verschiluren > 200 && kleurenarr[i] / verschiluren <= 300) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(beginlatituden[i], beginlongituden[i]),
                    L.latLng(eindlatituden[i], eindlongituden[i])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "YELLOW", opacity: 2, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (kleurenarr[i] / verschiluren > 300 && kleurenarr[i] / verschiluren <= 500) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(beginlatituden[i], beginlongituden[i]),
                    L.latLng(eindlatituden[i], eindlongituden[i])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "ORANGE", opacity: 2, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
        //begin punt 3.
        else if (kleurenarr[i] / verschiluren > 500) {
            //begin punt 4.
            L.Routing.control({
                //begin punt 5.
                waypoints: [
                    L.latLng(beginlatituden[i], beginlongituden[i]),
                    L.latLng(eindlatituden[i], eindlongituden[i])
                ],
                //begin punt 6.
                lineOptions: {
                    styles: [{color: "RED", opacity: 1, weight: 3}]
                },
                createMarker: function () {
                    return null;
                },
                fitSelectedRoutes: false,
                draggableWaypoints: false,
                addWaypoints: false
            }).addTo(map);
        }
    }
}



//Input: Zelfgemaakt parkeergarage icoontjes
//Output: L.icon- object, wat toegevoegd kan worden aan een L.Marker- object.
//Functie: Aantonen van drukte van parkeergarages mbv. kleuren in de icoontjes die we zelf hebben gemaakt. (red, orange, yellow, lawngreen, green)

var redIcon = L.icon({
    iconUrl: 'Pin1.png',
    iconSize: [50,50],
    popupAnchor: [0,-15]
});
var orangeIcon = L.icon({
    iconUrl: 'Pin2.png',
    iconSize: [50,50],
    popupAnchor: [0,-15]
});
var yellowIcon = L.icon({
    iconUrl: 'Pin3.png',
    iconSize: [50,50],
    popupAnchor: [0,-15]
});
var lawngreenIcon = L.icon({
    iconUrl: 'Pin4.png',
    iconSize: [50,50],
    popupAnchor: [0,-15]
});
var greenIcon = L.icon({
    iconUrl: 'Pin5.png',
    iconSize: [50,50],
    popupAnchor: [0,-15]
});



//garagenaam1 = latitude van deze garage (bijv. argonaut1), later meegestuurd aan functie kiesGarage(gemiddelde, LATITUDE, longitude)
//garagenaam2 = longitude van deze garage (bijv. argonaut2), later meegestuurd aan functie kiesGarage(gemiddelde, latitude, LONGITUDE)
var argonaut1 = 52.1530644;
var argonaut2 = 5.3713194;
var beestenmarkt1 = 52.1563115;
var beestenmarkt2 = 5.3941038
var flintplein1 = 52.1589361;
var flintplein2 = 5.3908078;
var koestraat1 = 52.1529346;
var koestraat2 = 5.3862629;
var soeverein1 = 52.1521653;
var soeverein2 = 5.385039;
var stjorisplein1 = 52.1553617;
var stjorisplein2 = 5.3834011;
var stadhuisplein1 = 52.156436;
var stadhuisplein2 = 5.384886;




//Input: Button click van de Garages-knop en ophalen van de begintijd, eindtijd en datum die geselecteerd zijn op het dashboard.
//Output:   1. Halen van data door een AJAX-request naar garages.php.
//          2. Lijst van garages(dit zijn de keys van alle garages die ingekleurd moeten worden) en van elke garage een lijst die straks gevuld wordt.
//          3. Door garages loopen.
//          4. Omdat de garagedata per 5 minuten in de database staat, wordt er 12x geloopt(als het verschil tussen begin- en eindtijd 1 is) of 13x geloopt(als het verschil tussen begin- en eindtijd >1 is)
//          5. Elke response parsen naar een int en in de array bezettingsgraadarr zetten.
//          6. Telkens 12 of 13 getallen uit bezettingsgraadarr(afhankelijk van jverschil) halen en deze toekennen aan een garagelijst. Met splice worden deze getallen daarna uit deze array gehaald, vandaar dat .splice altijd begint bij 0 en eindigt bij jverschil.
//          7. Gemiddelde per garagelijst berekenen door deze naar de functie neemGemiddelde(garagelijst) te sturen.
//          8. Vervolgens het resultaat uit die functie meesturen aan de kiesGarage(gemiddelde, latitude, longitude) samen met de latitude en longitude van de garages.
//Functie: Het ophalen van data en deze filteren en doorsturen naar verschillende functies die het tekenen en berekenen afhandelen.
$(".garage").click(function() {
    var http = new XMLHttpRequest();
    var begintijd = $('.begintijd').html();
    var eindtijd = $('.eindtijd').html();
    var datum = $('.datum').val();
    var beginuren = $('.beginuren').html();
    var einduren = $('.einduren').html();
    var verschiluren = einduren - beginuren;
    console.log(begintijd, eindtijd, datum);

    //begin punt 1.
    var url = "garages.php?datum=" + datum + '&' + 'begintijd=' + begintijd + '&' + 'eindtijd=' + eindtijd;

    http.open("GET", url, true);

    http.onreadystatechange = function () {
        if (http.readyState === 4 && http.status === 200) {
            var response = JSON.parse(http.responseText);
            console.log(response);
            console.log(http.responseText);
            console.log(response.statusText);

            //begin punt 2.
            var garages = ["argonaut","beestenmarkt","flintplein","koestraat","soeverein","stjorisplein","stadhuisplein"];
            var bezettingsgraadarr = [];
            var argonaut = [];
            var beestenmarkt = [];
            var flintplein = [];
            var koestraat = [];
            var soeverein = [];
            var stjorisplein = [];
            var stadhuisplein = [];
            var jverschil;

            if (verschiluren == 1){
                jverschil = 13;
            }else{
                jverschil = (12*verschiluren)+1;
            }
            var i,j,len;
            len = garages.length;
            //begin punt 3.
            for(i=0; i<len; i++) {
                for (j = 0; j < jverschil; j++){
                    bezettingsgraadarr.push(parseInt(response[garages[i]][j]["bezettingsgraad kort"]));
                }
            }
            argonaut = bezettingsgraadarr.splice(0,jverschil);
            beestenmarkt = bezettingsgraadarr.splice(0,jverschil);
            flintplein = bezettingsgraadarr.splice(0,jverschil);
            koestraat = bezettingsgraadarr.splice(0,jverschil);
            soeverein = bezettingsgraadarr.splice(0,jverschil);
            stjorisplein = bezettingsgraadarr.splice(0,jverschil);
            stadhuisplein = bezettingsgraadarr.splice(0,jverschil);

            var gemargonaut = neemGemiddelde(argonaut);
            var gembeestenmarkt = neemGemiddelde(beestenmarkt);
            var gemflintplein = neemGemiddelde(flintplein);
            var gemkoestraat = neemGemiddelde(koestraat);
            var gemsoeverein = neemGemiddelde(soeverein);
            var gemstjorisplein = neemGemiddelde(stjorisplein);
            var gemstadhuisplein = neemGemiddelde(stadhuisplein);

            kiesGarage(gemargonaut, argonaut1, argonaut2).bindPopup('Argonaut').openPopup();
            kiesGarage(gembeestenmarkt, beestenmarkt1, beestenmarkt2).bindPopup('Beestenmarkt').openPopup();
            kiesGarage(gemflintplein, flintplein1, flintplein2).bindPopup('Flintplein').openPopup();
            kiesGarage(gemkoestraat, koestraat1, koestraat2).bindPopup('Koestraat').openPopup();
            kiesGarage(gemsoeverein, soeverein1, soeverein2).bindPopup('Soeverein').openPopup();
            kiesGarage(gemstjorisplein, stjorisplein1, stjorisplein2).bindPopup('Sint Jorisplein').openPopup();
            kiesGarage(gemstadhuisplein, stadhuisplein1, stadhuisplein2).bindPopup('Stadhuisplein').openPopup();
            }
        }
    http.send();
});


//Input: garagelijst als parameter
//Output: Gemiddelde bezettingsgraad(in %) per uur
//Functie: Berekenen gemiddelde van garagelijsten.
function neemGemiddelde(array){
    var i;
    var som = 0;
    var gemiddelde;

    for (i=0; i<array.length;i++){
        som += array[i];
    }
    gemiddelde = Math.round(som/array.length);
    return gemiddelde;

}


//Input: Gemiddelde(uit functie neemGemiddelde(array)), latitude, longitude
//Output: L.Marker- object met een bepaalde kleur en ons eigen icoontje, wat getoond wordt in het dashboard.
//Functie: Maken van L.Marker- object met een kleur en icoon en tonen hiervan op de kaart (na het clicken van Garages- button)
function kiesGarage(gemiddelde, garage1, garage2){
    var marker;
    if(gemiddelde >= 0 && gemiddelde <= 20){
        marker = L.marker([garage1, garage2],{icon: lawngreenIcon}).addTo(map);
    }
    else if(gemiddelde > 20 && gemiddelde <= 40){
        marker = L.marker([garage1, garage2],{icon: greenIcon}).addTo(map);
    }
    else if(gemiddelde > 40 && gemiddelde <= 60){
        marker = L.marker([garage1, garage2],{icon: yellowIcon}).addTo(map);
    }
    else if (gemiddelde > 60 && gemiddelde <= 80) {
        marker =  L.marker([garage1, garage2],{icon: orangeIcon}).addTo(map);
    }
    else {
        marker =  L.marker([garage1, garage2],{icon: redIcon}).addTo(map);
    }
    return marker;
}


$(".text").click(function()
{
    var http = new XMLHttpRequest();
    var begintijd = $('.begintijd').html();
    var eindtijd = $('.eindtijd').html();
    var datum = $('.datum').val();
    var beginuren = $('.beginuren').html();
    var einduren = $('.einduren').html();

    var url = "info.php?datum=" + datum + '&' + 'begintijd=' + begintijd + '&' + 'eindtijd=' + eindtijd;

    http.open("GET", url, true);


    http.onreadystatechange = function () {
        if (http.readyState === 4 && http.status === 200) {
            var response = JSON.parse(http.responseText);
            var i;
            var keys = ["5", "11", "12", "13", "14", "15", "17", "19", "25"];
            var array = [];
            for (i=0; i<9; i++){
                array.push(response[keys[i]]["TotaalVerkeer"]);
            }
            console.log(array);
            var sum = array[0] + array[1] + array[2] + array[3] + array[4] + array[5] + array[6] + array[7] + array[8];
            console.log(sum);
        }
    }
    http.send();
});



$(document).ready(function(){
    $(".welkomKnop").click(function(){
        $(".welkom").fadeOut()
    });
});
//Gemaakt door: Haik Kebabdji, Yassine Sebbar en Ramon Abächerli
//Voor vragen, stuur een mail naar: ramon.abacherli@student.hu.nl      (niet verkeerd spellen, moeilijke achternaam!)
