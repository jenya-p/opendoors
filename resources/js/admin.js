import './bootstrap';
import '../css/reset.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import fieldContainer from "@/Components/field-container.js";
import currency from "@/Filters/currency.js";
import plural from "@/Filters/plural.js";
import htmlize from "@/Filters/htmlize.js";
import date from "@/Filters/date";
import phone from "@/Filters/phone";
import "vue-multiselect/dist/vue-multiselect.css";
import VueMask from "@devindex/vue-mask";
import isMobileMixin from "@/Components/is-mobile-mixin.js";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        let app = createApp({ render: () => h(App, props) });
        app.config.globalProperties.$filters = {currency: currency, plural: plural, htmlize: htmlize, date: date, phone: phone};
        app.use(plugin)
            .use(ZiggyVue)
            .use(VueMask)
            .mixin(isMobileMixin)
            .directive("field-container", fieldContainer)
            // .component('tabs', tabs)
            // .component('tab', tab)
            .mount(el);
        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
