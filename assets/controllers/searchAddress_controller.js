import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect(){
        // Format result in the search input autocomplete
        var formatResult = function (feature, el) {
        var title = document.createElement("strong");
        el.appendChild(title);
        var detailsContainer = document.createElement("small");
        el.appendChild(detailsContainer);
        var details = [];
        title.innerHTML = feature.properties.label || feature.properties.name;
        var types = {
            municipality: "commune",
        };
        if (types[feature.properties.type]) {
            var spanType = document.createElement("span");
            spanType.className = "type";
            title.appendChild(spanType);
            spanType.innerHTML = types[feature.properties.type];
        }
        if (
            feature.properties.city &&
            feature.properties.city !== feature.properties.name
        ) {
            details.push(feature.properties.city);
        }
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
            input[0].setAttribute('placeholder', feature.properties.label);
            
            document.getElementById('user_city').setAttribute('value', feature.properties.city);
            document.getElementById('user_lat').setAttribute('value', feature.geometry.coordinates[1]);
            document.getElementById('user_lng').setAttribute('value', feature.geometry.coordinates[0]);
            
        console.log(feature);
        }

        // URL for API
        var API_URL = "//api-adresse.data.gouv.fr";

        // Create search by adresses component
        var container = new Photon.Search({
        resultsHandler: myHandler,
        onSelected: onSelected,
        placeholder: "Entrez une adresse",
        formatResult: formatResult,
        url: API_URL + "/search/?",
        feedbackEmail: null,
        });

        // create search field
        const element = document.createElement("div");
        element.id = 'js_address';
        element.className = "photon-geocoder-autocomplete ol-unselectable ol-control";
        element.appendChild(container);

        // where to show search field
        document.getElementById('searchAddress').appendChild(element);
    }
}
