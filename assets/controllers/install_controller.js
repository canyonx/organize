import { Controller } from '@hotwired/stimulus';

/*
 * Installation button
 */
export default class extends Controller {
    connect() {
        let installPrompt = null;
        const installButton = document.getElementById('install');

        window.addEventListener("beforeinstallprompt", (event) => {
            // event.preventDefault();
            // installPrompt = event;
            installButton.removeAttribute("hidden");
        });

        installButton.addEventListener("click", async () => {
            if (!installPrompt) {
                return;
            }
            const result = await installPrompt.prompt();
            console.log(`Install prompt was: ${result.outcome}`);
            installPrompt = null;
            installButton.setAttribute("hidden", "");
        });
    }
}


