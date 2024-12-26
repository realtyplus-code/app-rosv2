import './bootstrap';
import { createApp } from 'vue';
import axios from "axios";
import Swal from "sweetalert2";
import shared from "./utils/shared";
import $ from 'jquery'; // Importar jQuery

import PrimeVue from "primevue/config";

const app = createApp({});

// importacion de layouts
import NavBarComponent from './components/layout/NavbarComponent.vue';
app.component('navbar-component', NavBarComponent);

import HomeComponent from './components/layout/HomeComponent.vue';
app.component('home-component', HomeComponent);

// importacion de properties
import PropertyComponent from './components/property/PropertyComponent.vue';
app.component('property-component', PropertyComponent);

//importacion de user
import UserComponent from './components/user/UserComponent.vue';
app.component('user-component', UserComponent);

//importacion de insurance
import InsuranceComponent from './components/insurance/InsuranceComponent.vue';
app.component('insurance-component', InsuranceComponent);

//importacion de enums
import EnumComponent from './components/enum/EnumComponent.vue';
app.component('enum-component', EnumComponent);

import Aura from "@primevue/themes/aura";
import "primeicons/primeicons.css";

import ProgressSpinner from 'primevue/progressspinner';

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            prefix: "p",
            darkModeSelector: "none",
            cssLayer: false,
        },
    },
});

app.component("ProgressSpinner", ProgressSpinner);

// Registrar funciones compartidas
app.mixin(shared.AlertsComponent);
app.mixin(shared.ReadHttpStatusErrors);
app.mixin(shared.HelperFunctions);
app.mixin(shared.RelationsTables);

// Configura Axios globalmente
app.config.globalProperties.$axios = axios;

// Configura SweetAlert2 globalmente
app.config.globalProperties.$swal = Swal;

window.$ = $;
window.jQuery = $;

$.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results ? results[1] : null;
};

app.mount('#app');
