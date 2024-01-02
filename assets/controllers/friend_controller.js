import { Controller } from '@hotwired/stimulus';
import axios from 'axios';

/*
 * Actions for friend relation, add, block, remove, reload buttons
 */
export default class extends Controller {
    add(){
        let userId = document.getElementById('userId').innerHTML;
        axios.get('/api/friend/add/' + userId)
            .then(function (response) {
                console.log('follow user ' + userId);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            }); 

        let button = '<button class="btn btn-secondary rounded-pill" data-controller="friend" data-action="click->friend#remove">Ne plus suivre</button>'

        this.reload(button);
    }

    block(){
        let userId = document.getElementById('userId').innerHTML;
        axios.get('/api/friend/block/' + userId)
            .then(function (response) {
                console.log('block user ' + userId);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            }); 

        let button = '<button class="btn btn-secondary rounded-pill" data-controller="friend" data-action="click->friend#remove">Débloquer</button>'

        this.reload(button);
    }

    remove(){
        let userId = document.getElementById('userId').innerHTML;
        axios.get('/api/friend/remove/' + userId)
            .then(function (response) {
                console.log('remove user ' + userId);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            }); 
        
        let b1 = '<button class="btn btn-primary rounded-pill" data-controller="friend" data-action="click->friend#add">Suivre</button>';
        let b2 = '<button class="btn btn-secondary rounded-pill" data-controller="friend" data-action="click->friend#block">Bloquer</button>';

        let button = b1 + ' ' + b2;
        
        this.reload(button);
    }

    reload(button){
        var container = document.getElementById("friendAction");
        var content = button;
        container.innerHTML= content; 
        
        //this line is to watch the result in console , you can remove it later
        console.log("Refreshed"); 
    }
}
