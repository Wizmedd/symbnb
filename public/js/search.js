
//variable globale
let ville = "";
let distance = "5";

window.onload = () => {


    let carte = L.map('map', { scrollWheelZoom: false }).setView([46.227638, 2.213749], 5);
    L.tileLayer('//{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: 'donn&eacute;es &copy; <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
        minZoom: 1,
        name: 'tiles',
        maxZoom: 20
    }).addTo(carte);

    //gestion des champs
    let champVille = document.getElementById('champ-ville');
    let champDistance = document.getElementById('champ-distance');
    let valeurDistance = document.getElementById('valeur-distance');

    champVille.addEventListener('change', function () {
        ajaxGet(`https://nominatim.openstreetmap.org/search?q=${this.value}&format=json&addressdetails=1&limit=1&polygon_svg=1`)
            .then(reponse => {
                //console.log(response);
                //on convertit la réponse en objet javascript (en Json actuellement)
                console.log(reponse);
                let data = JSON.parse(reponse);
                //on stocke les coordonnées dans ville
                ville = [data[0].lat, data[0].lon];
                console.log(ville);
                //on centre sur la ville



                // On envoie les données au serveur
                ajaxGet(`/loadAds.php?latitude=${ville[0]}&longitude=${ville[1]}&distance=${distance}`)
                    .then(reponse => {
                        console.log(reponse);
                        valeurDistance.innerText = distance + " km"


                        // On efface toutes les couches de la carte sauf les tuiles
                        carte.eachLayer(function (layer) {
                            if (layer.options.name != "tiles") carte.removeLayer(layer);
                        });

                        // On trace le cercle de rayon "distance"
                        let circle = L.circle(ville, {
                            color: '#4471C4',
                            fillColor: '#4471C4',
                            fillOpacity: 0.3,
                            radius: distance * 1000,
                        }).addTo(carte);

                        // On convertit la réponse en objet Javascript
                        let donnees = JSON.parse(reponse);

                        // On boucle sur les données (ES8)
                        Object.entries(donnees).forEach(agence => {
                            // Ici j'ai une seule agence
                            var redIcon = L.icon({
                                iconUrl: '/images/image.png',
                                //shadowUrl: '/images/leaf-shadow.png',

                                iconSize: [32, 34], // size of the icon
                                //shadowSize: [50, 64], // size of the shadow
                                iconAnchor: [0, 34], // point of the icon which will correspond to marker's location
                                //shadowAnchor: [4, 62],  // the same for the shadow
                                popupAnchor: [8, -6] // point from which the popup should open relative to the iconAnchor
                            });
                            // On crée un marqueur pour l'agence

                            let marker = L.marker([agence[1].latitude, agence[1].longitude], { icon: redIcon }).addTo(carte)
                            marker.bindPopup(agence[1].title + "<img style=\"max-height: 300px; width:100%\" src=\"" + agence[1].cover_image + "\">" + "</br>" + "<a href=" + agence[1].slug + " class=\"btn btn-primary float-end\" >En savoir plus</a>")

                        })

                        // On centre et on zoome sur le cercle
                        bounds = circle.getBounds();
                        carte.fitBounds(bounds);
                    })


            })
    })


    champDistance.addEventListener("change", function () {

        // On récupère la distance choisie
        distance = this.value

        // On écrit cette valeur sur la page
        valeurDistance.innerText = distance + " km"

        // On vérifie si une ville a été saisie
        if (ville != "") {
            // On envoie les données au serveur
            ajaxGet(`/loadAds.php?latitude=${ville[0]}&longitude=${ville[1]}&distance=${distance}`)
                .then(reponse => {
                    console.log(reponse);


                    // On efface toutes les couches de la carte sauf les tuiles
                    carte.eachLayer(function (layer) {
                        if (layer.options.name != "tiles") carte.removeLayer(layer);
                    });

                    // On trace le cercle de rayon "distance"
                    let circle = L.circle(ville, {
                        color: '#4471C4',
                        fillColor: '#4471C4',
                        fillOpacity: 0.3,
                        radius: distance * 1000,
                    }).addTo(carte);

                    // On convertit la réponse en objet Javascript
                    let donnees = JSON.parse(reponse);

                    // On boucle sur les données (ES8)
                    Object.entries(donnees).forEach(agence => {
                        // Ici j'ai une seule agence
                        // On crée un marqueur pour l'agence
                        let marker = L.marker([agence[1].latitude, agence[1].longitude]).addTo(carte)
                        marker.bindPopup(agence[1].title)
                    })

                    // On centre et on zoome sur le cercle
                    bounds = circle.getBounds();
                    carte.fitBounds(bounds);
                })
        }
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

