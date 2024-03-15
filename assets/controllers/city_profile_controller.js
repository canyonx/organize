import { Controller } from '@hotwired/stimulus';
import Photon from '@webgeodatavore/photon-geocoder-autocomplete';

/*
 * Choose a city in user edit profile by api call
 */
export default class extends Controller {
    connect(){
        // Format result in the search input autocomplete
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
            console.log(featureCollection);
        }

        // We reused the default function to center and zoom on selected feature.
        // You can make your own. For instance, you could center, zoom
        // and add a point on the map
        function onSelected(feature) {
            let input = document.getElementsByClassName('photon-input');
            input[0].setAttribute('placeholder', feature.properties.city);

            var form = document.getElementsByTagName('form');
            var element = form[0].name;
            
            document.getElementById(element + '_city').setAttribute('value', feature.properties.city);
            document.getElementById(element + '_lat').setAttribute('value', feature.geometry.coordinates[1].toFixed(4));
            document.getElementById(element + '_lng').setAttribute('value', feature.geometry.coordinates[0].toFixed(4));
            
            console.log(feature);
        }

        // Create search by adresses component
        var container = new Photon.Search({
            resultsHandler: myHandler,
            onSelected: onSelected,
            placeholder: "Entrez une ville",
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
        document.getElementById('city_profile').appendChild(element);

        // Add class from-control to search field
        var inputAddress = document.getElementsByClassName('photon-input');
        inputAddress[0].classList.add("form-control");

        if (document.getElementById('user_city').value) {
            inputAddress[0].setAttribute('placeholder', document.getElementById('user_city').value);
        }
        
    }
}
