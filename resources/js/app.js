import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';

// Font Awesome
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faBox,
    faPlus,
    faUser,
    faEye,
    faPencil,
    faTrash,
    faArrowRight,
    faRefresh,
    faSignOutAlt,
    faHome,
    faTachometerAlt,
    faImage,
    faShoppingCart,
    faCheck,
    faArrowDown,
    faChevronDown
} from '@fortawesome/free-solid-svg-icons';

// Agregar iconos a la librería
library.add(
    faBox,
    faPlus,
    faUser,
    faEye,
    faPencil,
    faTrash,
    faArrowRight,
    faRefresh,
    faSignOutAlt,
    faHome,
    faTachometerAlt,
    faImage,
    faShoppingCart,
    faCheck,
    faArrowDown,
    faChevronDown
);

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        if (!page) {
            throw new Error(`Page not found: ./Pages/${name}.vue`);
        }
        return page.default;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
});
