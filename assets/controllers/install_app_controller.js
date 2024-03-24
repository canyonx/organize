import { Controller } from '@hotwired/stimulus';

/*
 * Install button action
 */
export default class extends Controller {
    initialize(){
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            document.getElementById('installAppContainer').classList.remove('d-none');
            deferredPrompt = e;
        });

        const installApp = document.getElementById('installApp');

        installApp.addEventListener('click', async () => {
            if (deferredPrompt !== null) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    deferredPrompt = null;
                }
            }
        });
    }
}
