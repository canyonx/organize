import { Controller } from '@hotwired/stimulus';

/*
 * Active class on nav
 */
export default class extends Controller {
    connect() {
        const activePage = window.location.pathname;
        // console.log(activePage);
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            if (link.href.includes(activePage) && activePage != '/') {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
}
