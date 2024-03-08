import { Controller } from '@hotwired/stimulus';

/*
 * On Create Trip Form
 * Show info if user have a planified trip when select day
 */
 import axios from 'axios';

export default class extends Controller {
    initialize() {
        this.check();
    }
    
    check() {
        //  Get date from field
        var date = document.getElementById('trip_dateAt_date').value;

        axios.get('/api/istripthatday/' + date)
            .then(function (response) {
                // handle success
                console.log(response.data);
                if (response.data == true) {
                    document.getElementById('warning_limitation').classList.remove("visually-hidden");
                    document.getElementById('trip_submit').classList.add("disabled");
                } else {
                    document.getElementById('warning_limitation').classList.add("visually-hidden");
                    document.getElementById('trip_submit').classList.remove("disabled");
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });   
    }
}
