



let $map = document.querySelector('#map');

class LeafletMap {

    constructor() {

        this.map = null;
        this.bounds = [];

    }

    async load(element) {
        return new Promise((resolve, reject) => {
            $script("https://unpkg.com/leaflet@1.7.1/dist/leaflet.js", "https://stamen-maps.a.ssl.fastly.net/js/tile.stamen.js?v1.3.0", () => {
                this.map = L.map(element, { scrollWheelZoom: false });
                let Stamen_Terrain = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}.{ext}', {
                    attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    subdomains: 'abcd',
                    minZoom: 0,
                    maxZoom: 20,
                    name: 'tiles',
                    ext: 'png'
                });
                this.map.addLayer(Stamen_Terrain);


                resolve();

            })

        });


    }



    addMarker(lat, lng, text) {
        let point = [lat, lng];
        this.bounds.push(point);
        return new LeafletMarker(point, text, this.map);

    }

    center() {
        this.map.fitBounds(this.bounds);
    }


}

class LeafletMarker {
    constructor(point, text, map) {
        this.text = text;
        this.popup = L.popup({
            autoClose: false,
            closeOnEscapeKey: false,
            closeOnClick: false,
            closeButton: false,
            className: 'marker',
            maxWidth: 300
        })
            .setLatLng(point)
            .setContent(text)
            .openOn(map);

    }

    setActive() {
        this.popup.getElement().classList.add('is-active')
    }
    unsetActive() {
        this.popup.getElement().classList.remove('is-active')
    }

    addEventListener(evt, cb) {
        this.popup.addEventListener('add', () => {
            this.popup.getElement().addEventListener(evt, cb);
        })

    }
    setContent(text) {
        this.popup.setContent(text);
        this.popup.getElement().classList.add('is-expanded');
        this.popup.update();
    }
    resetContent() {
        this.popup.setContent(this.text);
        this.popup.getElement().classList.remove('is-expanded');
        this.popup.update();
    }

}

const initMap = async function () {
    let map = new LeafletMap();
    let hoverMarker = null;
    let activeMarker = null;

    await map.load($map);

    Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
        let marker = map.addMarker(item.dataset.lat, item.dataset.lng, item.dataset.price + ' €')
        item.addEventListener('mouseover', function () {
            if (hoverMarker !== null) {
                hoverMarker.unsetActive();
            }
            marker.setActive();
            hoverMarker = marker;
        })
        item.addEventListener('mouseleave', function () {
            if (hoverMarker !== null) {
                hoverMarker.unsetActive();
            }

        })

        marker.addEventListener('click', function () {
            if (activeMarker !== null) {
                activeMarker.resetContent();
            }

            marker.setContent(item.dataset.rooms + " chambres, " + item.dataset.price + " €" + "<br>" + item.dataset.content + "<br>" + "<a href=" + item.dataset.link + " class=\"btn btn-primary float-end\" >En savoir plus</a>")
            activeMarker = marker;

        })

        marker.addEventListener('mouseleave', function () {
            if (activeMarker !== null) {
                activeMarker.resetContent();
            }



        })


    })
    map.center();



}



if ($map !== null) {
    initMap();


}











