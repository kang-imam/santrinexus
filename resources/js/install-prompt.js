const promptElement = document.getElementById('install-prompt');
if (promptElement) {
    const installButton = document.getElementById('install-prompt-button');
    const closeButton = document.getElementById('install-prompt-close');
    const laterButton = document.getElementById('install-prompt-later');
    const helpElement = document.getElementById('install-prompt-help');
    const storageKey = 'santrinexus-install-prompt-dismissed';
    const installedKey = 'santrinexus-pwa-installed';
    const repeatDelay = 5 * 60 * 1000;
    const initialDelay = 5000;
    let deferredPrompt = null;
    let timer = null;
    const isStandalone = () => window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    const isInstalled = () => isStandalone() || localStorage.getItem(installedKey) === 'true';
    const hidePrompt = () => {
        promptElement.classList.add('hidden');
        promptElement.classList.remove('flex');
        promptElement.setAttribute('aria-hidden', 'true');
    };
    const showPrompt = () => {
        if (isInstalled()) return;
        promptElement.classList.remove('hidden');
        promptElement.classList.add('flex');
        promptElement.setAttribute('aria-hidden', 'false');
    };
    const dismissPrompt = () => {
        localStorage.setItem(storageKey, String(Date.now()));
        hidePrompt();
    };
    const schedulePrompt = () => {
        if (isInstalled()) return;
        const lastDismissed = Number(localStorage.getItem(storageKey) || 0);
        const elapsed = Date.now() - lastDismissed;
        const delay = lastDismissed ? Math.max(initialDelay, repeatDelay - elapsed) : initialDelay;
        clearTimeout(timer);
        timer = setTimeout(showPrompt, delay);
    };
    const showInstallHelp = () => {
        const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
        helpElement.textContent = isIOS ? 'Untuk memasang SantriNexus, ketuk tombol Bagikan pada browser, lalu pilih Tambahkan ke Layar Utama.' : 'Buka menu browser (⋮ atau ...), lalu pilih Install app atau Tambahkan ke layar utama.';
        helpElement.classList.remove('hidden');
    };
    window.addEventListener('beforeinstallprompt', event => {
        event.preventDefault();
        deferredPrompt = event;
        const label = installButton.querySelector('span');
        if (label) label.textContent = 'Install Sekarang';
    });
    window.addEventListener('appinstalled', () => {
        localStorage.setItem(installedKey, 'true');
        localStorage.removeItem(storageKey);
        deferredPrompt = null;
        clearTimeout(timer);
        hidePrompt();
    });
    installButton.addEventListener('click', async () => {
        if (!deferredPrompt) {
            showInstallHelp();
            return;
        }
        const installEvent = deferredPrompt;
        deferredPrompt = null;
        await installEvent.prompt();
        const choice = await installEvent.userChoice;
        if (choice.outcome === 'accepted') {
            localStorage.setItem(installedKey, 'true');
            clearTimeout(timer);
            hidePrompt();
        } else {
            dismissPrompt();
        }
    });
    closeButton.addEventListener('click', dismissPrompt);
    laterButton.addEventListener('click', dismissPrompt);
    promptElement.addEventListener('click', event => {
        if (event.target === promptElement) dismissPrompt();
    });
    window.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !promptElement.classList.contains('hidden')) dismissPrompt();
    });
    if (!isInstalled()) schedulePrompt();
}
