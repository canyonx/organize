import { Controller } from '@hotwired/stimulus';

/*
 * Search Form 
 * Enable disable Distance field when selecting a location 
 */
export default class extends Controller {
    connect() {
        var location = document.getElementById('search_location');
        var field = document.getElementById('search_distance');

        toggleDistance();

        location.addEventListener('change', function(e) {
                toggleDistance();
        });

        function toggleDistance(){
            if (location.value){
                field.disabled= false;
            } else {
                field.value = '';
                field.disabled = true;
            }
        }


    }
}