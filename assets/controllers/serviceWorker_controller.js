import { Controller } from '@hotwired/stimulus';

/*
 * Service worker on base.html.twig
 * calculate the full heigt on initialize
 */
export default class extends Controller {
    fullheight() {
        let vh = window.innerHeight;
        // console.log(vh);
        let navh = document.getElementById('nav_small').clientHeight;
        // console.log(navh);
        document.documentElement.style.setProperty('--vh', `${vh - navh}px`);
        // console.log(document.documentElement.style);
    }

    initialize() {
        this.fullheight();
        window.addEventListener('resize', () => {
            this.fullheight();
        });
    }


    connect() {
        const activePage = window.location.pathname;

        if (('serviceWorker' in navigator) && (activePage == '/')) {
            navigator.serviceWorker.register('https://organize-app.fr/sw.js')
                .then(function () {console.log('Enregistrement reussi.')})
                .catch(function (e) {console.error(e)});
        }
    }
}
