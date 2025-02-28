import { Controller } from '@hotwired/stimulus';
import Photon from '@webgeodatavore/photon-geocoder-autocomplete';

/*
 * Choose a city in user edit profile by api call
 */
export default class extends Controller {
    connect(){
        const form = document.getElementsByTagName('form');
        const formName = form[0].name;
        
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

            
            document.getElementById(formName + '_city').setAttribute('value', feature.properties.city);
            document.getElementById(formName + '_lat').setAttribute('value', feature.geometry.coordinates[1].toFixed(4));
            document.getElementById(formName + '_lng').setAttribute('value', feature.geometry.coordinates[0].toFixed(4));
            
            console.log(feature);
        }

        // Delete existing fields (when use return or next in browser)
        var fields = document.getElementsByClassName('photon-geocoder-autocomplete');
        for (let index = 0; index < fields.length; index++) {
            const element = fields[index];
            element.remove();
        }

        // Create search by adresses component
        var container = new Photon.Search({
            resultsHandler: myHandler,
            onSelected: onSelected,
            placeholder: document.getElementById(formName + '_city').value,
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

        // // Add class from-control to search field
        // var inputAddress = document.getElementsByClassName('photon-input');

        // // Get the form name
        // let form = document.getElementsByTagName('form');
        // let formName = form[0].name;

        // if (document.getElementById(formName + '_city').value) {
        //     inputAddress[0].setAttribute('placeholder', document.getElementById(formName + '_city').value);
        // }
        
    }
}
