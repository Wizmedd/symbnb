




var carte = null;
var markerClusters; // Servira à stocker les groupes de marqueurs
// Nous initialisons une liste de marqueurs

window.onload = () => {




    var markers = []; // Nous initialisons la liste des marqueurs




    carte = L.map('map', { scrollWheelZoom: false }).setView([46.227638, 2.213749], 5);
    markerClusters = L.markerClusterGroup(); // Nous initialisons les groupes de marqueurs

    let Stamen_watercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.{ext}', {
        attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        subdomains: 'abcd',
        minZoom: 0,
        maxZoom: 20,
        name: 'tiles',
        ext: 'png'
    });
    carte.addLayer(Stamen_watercolor);


    // On envoie les données au serveur
    ajaxGet(`/loadHome.php`)
        .then(reponse => {
            console.log(reponse);






            // On convertit la réponse en objet Javascript


            let donnees = JSON.parse(reponse);


            // On boucle sur les données (ES8)
            Object.entries(donnees).forEach(agence => {
                console.log(agence)
                // Ici j'ai une seule agence
                var houseIcon = L.icon({
                    iconUrl: '/images/house.png',
                    //shadowUrl: '/images/leaf-shadow.png',

                    iconSize: [36, 38], // size of the icon
                    //shadowSize: [50, 64], // size of the shadow
                    iconAnchor: [0, 38], // point of the icon which will correspond to marker's location
                    //shadowAnchor: [4, 62],  // the same for the shadow
                    popupAnchor: [8, -6] // point from which the popup should open relative to the iconAnchor
                });

                var appartIcon = L.icon({
                    iconUrl: '/images/appart.png',
                    //shadowUrl: '/images/leaf-shadow.png',

                    iconSize: [36, 38], // size of the icon
                    //shadowSize: [50, 64], // size of the shadow
                    iconAnchor: [0, 38], // point of the icon which will correspond to marker's location
                    //shadowAnchor: [4, 62],  // the same for the shadow
                    popupAnchor: [8, -6] // point from which the popup should open relative to the iconAnchor
                });
                var landIcon = L.icon({
                    iconUrl: '/images/land.png',
                    //shadowUrl: '/images/leaf-shadow.png',

                    iconSize: [36, 38], // size of the icon
                    //shadowSize: [50, 64], // size of the shadow
                    iconAnchor: [0, 38], // point of the icon which will correspond to marker's location
                    //shadowAnchor: [4, 62],  // the same for the shadow
                    popupAnchor: [8, -6] // point from which the popup should open relative to the iconAnchor
                });
                // On crée un marqueur pour l'agence
                if (agence[1].price > 600) {
                    // faire quelque chose
                    var marker = L.marker([agence[1].latitude, agence[1].longitude], { icon: houseIcon });
                    marker.bindPopup(agence[1].title + "<img style=\"max-height: 300px; width:100%\" src=\"" + agence[1].cover_image + "\">" + "</br>" + "<a href=/ads/" + agence[1].slug + " class=\"btn btn-primary float-end\" >En savoir plus</a>")
                    markerClusters.addLayer(marker); // Nous ajoutons le marqueur aux groupes
                    markers.push(marker); // Nous ajoutons le marqueur à la liste des marqueurs

                } else if (agence[1].price > 300) {
                    // faire autre chose
                    var marker = L.marker([agence[1].latitude, agence[1].longitude], { icon: appartIcon });
                    marker.bindPopup(agence[1].title + "<img style=\"max-height: 300px; width:100%\" src=\"" + agence[1].cover_image + "\">" + "</br>" + "<a href=/ads/" + agence[1].slug + " class=\"btn btn-primary float-end\" >En savoir plus</a>")
                    markerClusters.addLayer(marker); // Nous ajoutons le marqueur aux groupes
                    markers.push(marker); // Nous ajoutons le marqueur à la liste des marqueurs
                } else {
                    // faire encore autre chose
                    var marker = L.marker([agence[1].latitude, agence[1].longitude], { icon: landIcon });
                    marker.bindPopup(agence[1].title + "<img style=\"max-height: 300px; width:100%\" src=\"" + agence[1].cover_image + "\">" + "</br>" + "<a href=/ads/" + agence[1].slug + " class=\"btn btn-primary float-end\" >En savoir plus</a>")
                    markerClusters.addLayer(marker); // Nous ajoutons le marqueur aux groupes
                    markers.push(marker); // Nous ajoutons le marqueur à la liste des marqueurs
                }
            })




            var group = new L.featureGroup(markers); // Nous créons le groupe des marqueurs pour adapter le zoom
            carte.fitBounds(group.getBounds().pad(0.5)); // Nous demandons à ce que tous les marqueurs soient visibles, et ajoutons un padding (pad(0.5)) pour que les marqueurs ne soient pas coupés
            carte.addLayer(markerClusters);

        })



}




/**
 * Cette fonction effectue un appel Ajax vers une url et retourne une promesse
 * @param {string} url 
 */
function ajaxGet(url) {
    return new Promise(function (resolve, reject) {
        // Nous allons gérer la promesse
        let xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            // Si le traitement est terminé
            if (xmlhttp.readyState == 4) {
                // Si le traitement est un succès
                if (xmlhttp.status == 200) {
                    // On résoud la promesse et on renvoie la réponse
                    resolve(xmlhttp.responseText);
                } else {
                    // On résoud la promesse et on envoie l'erreur
                    reject(xmlhttp);
                }
            }
        }

        // Si une erreur est survenue
        xmlhttp.onerror = function (error) {
            // On résoud la promesse et on envoie l'erreur
            reject(error);
        }

        // On ouvre la requête
        xmlhttp.open('GET', url, true);

        // On envoie la requête
        xmlhttp.send(null);
    })
}



