<<<<<<< HEAD
import './bootstrap';
//
=======
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';

// Inicjalizacja aplikacji
createInertiaApp({
    resolve: name => import(`./Pages/${name}.vue`),  // dynamiczne importowanie komponentów
    setup({ el, App, props }) {
        createApp({ render: () => h(App, props) }).mount(el);
    },
});

InertiaProgress.init();
>>>>>>> 8f0c9ba (poprawki)
