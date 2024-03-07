import { Controller } from '@hotwired/stimulus';

/*
 * Initialise the Map in Trip Form Create and Update
 */
//  import axios from 'axios';
import L from '../vendor/leaflet/leaflet.index.js';

export default class extends Controller {
    connect(){
        var lat = document.getElementById('trip_lat').value;
        var lng = document.getElementById('trip_lng').value;

        console.log(lat, lng);
       
        // Create map
        var map = L.map('map_form', {
            'center': [lat, lng],
            'zoom': 13,
            'minZoom': 10,
            'maxZoom': 17,
        })

        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Create marker
        var marker = L.marker([lat, lng], {draggable: true})
            .addTo(map)
            .bindPopup('Drag marker to set the Meeting Point')
            .openPopup();

        // Marker drag function
        marker.on('moveend', function() {
            // console.log(marker.getLatLng().lat);
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;
            // Set marker
            map.panTo([lat, lng]);
            // set form fields
            document.getElementById('trip_lat').value = lat;
            document.getElementById('trip_lng').value = lng;
        });

        // Map onclick move marker
        // map.on('click', function(e) {
        //     // console.log(marker.getLatLng().lat);
        //     var lat = e.latlng.lat;
        //     var lng = e.latlng.lng;
        //     // Set marker
        //     marker.setLatLng([lat, lng]);
        //     map.panTo([lat, lng]);
        //     // set form fields
        //     document.getElementById('trip_meetingPointLat').value = lat;
        //     document.getElementById('trip_meetingPointLng').value = lng;
        // });

        // Center map when select location
        // document.getElementById('trip_lat')
        // .addEventListener('change', function(e) {
        //     //  Get location Id
        //     var lat = document.getElementById('trip_lat').value;
        //     var lng = document.getElementById('trip_lng').value;
        //     console.log(lat, lng);

        //     // Center map
        //     map.panTo([lat, lng], 13);
        //     // Set marker
        //     marker.setLatLng([lat, lng]);
                    

              
        // });
    }
}
