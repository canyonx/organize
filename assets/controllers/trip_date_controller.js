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
        var id = document.getElementById('trip_id').value;
        var date = document.getElementById('trip_dateAt_date').value;
console.log(id,date);

        axios.post('/api/istripthatday', {
                id: id,
                date: date
            })
            .then(function (response) {
                // handle success
                console.log(response.data);
                if (response.data == true) {
                    document.getElementById('warning_limitation').classList.remove("visually-hidden");
                    document.getElementById('trip_submit').disabled = true;
                } else {
                    document.getElementById('warning_limitation').classList.add("visually-hidden");
                    document.getElementById('trip_submit').disabled = false;
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });   
    }
}
