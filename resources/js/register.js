import './bootstrap';

import { createApp } from 'vue';
import fieldContainer from "@/Components/field-container.js";
import currency from "@/Filters/currency.js";
import plural from "@/Filters/plural.js";
import htmlize from "@/Filters/htmlize.js";
import date from "@/Filters/date";
import phone from "@/Filters/phone";
import ntl from "@/Filters/ntl.js";
import "vue-multiselect/dist/vue-multiselect.css";

import isMobileMixin from "@/Components/is-mobile-mixin.js";
import RegistrationForm from "@/Pages/RegistrationForm.vue";
import { createI18n } from 'vue-i18n';



const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
let app = createApp(RegistrationForm)
app.config.globalProperties.$filters = {currency: currency, plural: plural, htmlize: htmlize, date: date, phone: phone, ntl: ntl}
app.mixin(isMobileMixin)
    .directive("field-container", fieldContainer)
    .use(createI18n({
        locale: document.getElementsByTagName('html')[0].getAttribute('lang')
    }))
    .mount( document.getElementById('registration_form'));

