import { Controller } from '@hotwired/stimulus';
import axios from 'axios';
import L from '../vendor/leaflet/leaflet.index.js';

/*
 * Initialise the Map in Trip Show
 */
export default class extends Controller {
    initialize() {
        var lat = document.getElementById('search_map_lat').value;
        var lng = document.getElementById('search_map_lng').value;

        var map = L.map('map', {
            center : [lat, lng],
            zoom: 9,
            minZoom: 5,
            maxZoom: 17,
            dragging: true, 
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

        // const marker = L.marker([lat, lng], {icon: defaultMarker}).addTo(map)
        // 	.bindPopup('<a href="https://maps.google.com/?q=' + lat + ',' + lng + '">Ouvrir avec Google map</a>');

       // axios /api/alltripthatday
        axios.post('/api/alltripthatday', {
            date: document.getElementById('search_map_dateAt').value,
            location: document.getElementById('search_map_location').value,
            lat: document.getElementById('search_map_lat').value,
            lng: document.getElementById('search_map_lng').value,
            distance: document.getElementById('search_map_distance').value,
        })
        .then(function (response) {
            // Foreach trip add marker
                // console.log(response);
            response =JSON.parse(response.data);
            for (let index = 0; index < response.length; index++) {
                const element = response[index];
                const marker = L.marker([element.lat, element.lng], {icon: defaultMarker}).addTo(map)
                        .bindPopup('<a href="https://127.0.0.1:8000/trip/' + element.id + '" data-turbo-frame="_top">' + element.title + '</a><br>' + element.activity.name);
            }
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        }); 
    }
}
