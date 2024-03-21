import { Controller } from '@hotwired/stimulus';

/*
 * Service worker on base.html.twig
 */
export default class extends Controller {
    connect() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('https://zazoo.ddns.net/sw.js')
                .then(function () {console.log('Enregistrement reussi.')})
                .catch(function (e) {console.error(e)});
        }
    }
}
