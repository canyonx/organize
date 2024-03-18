import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 */
export default class extends Controller {
    connect() {
        window.addEventListener("scroll", this.reveal);
    }

    reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 150;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            } else {
                // reveals[i].classList.remove("active");
            }
        }
}

}
