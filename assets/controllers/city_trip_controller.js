import { Controller } from '@hotwired/stimulus';
import Photon from '@webgeodatavore/photon-geocoder-autocomplete';
import L from '../vendor/leaflet/leaflet.index.js';


/*
 * Choose a city in user edit profile by api call
 */
export default class extends Controller {
    connect(){

        // Initialize map
        // Get values lat, lng from form field (user location)
        var lat = document.getElementById('trip_lat').value;
        var lng = document.getElementById('trip_lng').value;

        // Create map
        var map = L.map('map_form', {
            'center': [lat, lng],
            'zoom': 13,
            'minZoom': 10,
            'maxZoom': 17,
        })

        // Add tiles
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

        // Create marker
        var marker = L.marker([lat, lng], {icon: defaultMarker, draggable: true})
            .addTo(map)
            .bindPopup('Drag marker to set the Meeting Point')
            .openPopup();

        // Marker drag function
        // On move set values of lat, lng form fields
        marker.on('moveend', function() {
            // console.log(marker.getLatLng().lat);
            var lat = marker.getLatLng().lat.toFixed(4);
            var lng = marker.getLatLng().lng.toFixed(4);
            // Set marker
            map.panTo([lat, lng]);
            // set form fields
            document.getElementById('trip_lat').value = lat;
            document.getElementById('trip_lng').value = lng;
        });


        // Format result in the search input autocomplete
        // DropDown Style to choose a city
        function formatResult(feature, el) {
            // Title
            var title = document.createElement("strong");
            el.appendChild(title);
            // Details
            var detailsContainer = document.createElement("small");
            el.appendChild(detailsContainer);
            var details = [];

            // Set title, city name
            title.innerHTML = feature.properties.label || feature.properties.name;
            // Set details, city context
            if (feature.properties.context) {
                details.push(feature.properties.context);
            }
            detailsContainer.innerHTML = details.join(", ");
        };

        // Function to show you can do something with the returned elements
        function myHandler(featureCollection) {
            // console.log(featureCollection);
        }

        // We reused the default function to center and zoom on selected feature.
        // You can make your own. For instance, you could center, zoom
        // and add a point on the map
        function onSelected(feature) {
            // Set placeholder of city field
            let input = document.getElementsByClassName('photon-input');
            input[0].setAttribute('placeholder', feature.properties.city);
            // Get coordinates of feature
            var lat = feature.geometry.coordinates[1].toFixed(4);
            var lng = feature.geometry.coordinates[0].toFixed(4);
            // Set form fields
            document.getElementById('trip_lat').value = lat;
            document.getElementById('trip_lng').value = lng;
            // Center map
            map.panTo([lat, lng], 13);
            // Set marker
            marker.setLatLng([lat, lng]);

        }

        // Create search by city component
        var container = new Photon.Search({
            resultsHandler: myHandler,
            onSelected: onSelected,
            placeholder: "Chercher une ville",
            formatResult: formatResult,
            url: "https://api-adresse.data.gouv.fr/search/?type=municipality&autocomplete=1&",
            feedbackEmail: null,
        });

        // Create search field
        const element = document.createElement("div");
            element.id = 'js_address';
            element.className = "photon-geocoder-autocomplete ol-unselectable ol-control";
            element.appendChild(container);

        // Where to show search field
        document.getElementById('city_trip').appendChild(element);

        // Add class from-control to search field
        var inputAddress = document.getElementsByClassName('photon-input');
        inputAddress[0].classList.add("form-control");
    }
}
