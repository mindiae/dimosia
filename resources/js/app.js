require('./bootstrap');

import { createApp, h } from 'vue';
import VueCookies from 'vue-cookies';
import VueScrollTo from 'vue-scrollto';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(VueCookies, {expire: '365d'})
            .use(VueScrollTo)
            .mixin({ methods: { route } })
            .mixin(require('./base'))
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
