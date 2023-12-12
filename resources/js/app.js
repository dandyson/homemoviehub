import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
/* fontawesome */
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPlay, faFileVideo, faVideo, faPlus, faPencil, faTrash, faSquareArrowUpRight } from '@fortawesome/free-solid-svg-icons'
import VuePlyr from 'vue-plyr'
import 'vue-plyr/dist/vue-plyr.css'

library.add(faPlay, faFileVideo, faVideo, faPlus, faPencil, faTrash, faSquareArrowUpRight)

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .component('font-awesome-icon', FontAwesomeIcon)
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VuePlyr, {
                plyr: {
                    ratio: '16:9',
                }
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
