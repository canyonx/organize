import { Controller } from '@hotwired/stimulus';

/*
 * Message box scroll to bottom on load
 */
export default class extends Controller {
    connect() {
        var box = document.getElementById("messageBox");
        box.scrollTop = box.scrollHeight;
    }
}
