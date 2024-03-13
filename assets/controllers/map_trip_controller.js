import { Controller } from '@hotwired/stimulus';

import L from '../vendor/leaflet/leaflet.index.js';

/*
 * Initialise the Map in Trip Show
 */
export default class extends Controller {
    initialize() {
        var lat = document.getElementById('trip_lat').value;
        var lng = document.getElementById('trip_lng').value;

        var map = L.map('map', {
            center : [lat, lng],
            zoom: 14,
            minZoom: 10,
            maxZoom: 17,
            dragging: false, 
            scrollWheelZoom: false
        })

        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Initialize icon
        var defaultMarker = L.icon({
            iconUrl: '../../images/marker-icon.png',

            iconSize:     [24, 40], // size of the icon
            iconAnchor:   [12, 40], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -40] // point from which the popup should open relative to the iconAnchor
        });

        const marker = L.marker([lat, lng], {icon: defaultMarker}).addTo(map)
        	.bindPopup('<a href="https://maps.google.com/?q=' + lat + ',' + lng + '">Ouvrir avec Google map</a>')
            .openPopup();
    }
}
