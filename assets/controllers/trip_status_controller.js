import { Controller } from '@hotwired/stimulus';

/*
* UNUSED
*
 * On Trip request status Accept/Refuse 
 * Change trip status when owner choose one
 */
import axios from 'axios';

export default class extends Controller {
    // initialize(){
    //     var id = document.getElementById('tripRequest_id').innerHTML;
    //     var status = document.getElementById('tripRequest_status').value;
    // }

    // Request API when click on Accept
    change({ params: { id, status } }) {
        axios.post('/api/trip/request/status', {
            id: id,
            status: status,
        })
        .then(function (response) {
            console.log('tripRequest ' + id + ', ' + status);
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        }); 

        // let button = '<button class="btn btn-secondary rounded-pill" data-controller="friend" data-action="click->friend#remove">Ne plus suivre</button>'

        // this.reload(button);
    }

    // Reload action buttons 
    reload(button){
        var container = document.getElementById("statusAction");
        var content = button;
        container.innerHTML= content; 
        
        //this line is to watch the result in console , you can remove it later
        console.log("Refreshed"); 
    }
}
