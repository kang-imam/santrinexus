import Alpine from 'alpinejs';
import './install-prompt.js';
window.Alpine = Alpine;
Alpine.start();
if ('serviceWorker' in navigator) {
    window.addEventListener('load', async () => {
        try {
            const registration = await navigator.serviceWorker.register('/sw.js', { scope: '/' });
            console.log('SantriNexus PWA aktif:', registration.scope);
        } catch (error) {
            console.error('Service Worker gagal:', error);
        }
    });
}
