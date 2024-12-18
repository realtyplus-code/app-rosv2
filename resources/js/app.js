import './bootstrap';
import { createApp } from 'vue';
import axios from "axios";
import Swal from "sweetalert2";
import shared from "./utils/shared"

import PrimeVue from "primevue/config";

const app = createApp({});

// importacion de layouts
import NavBarComponent from './components/layout/NavbarComponent.vue';
app.component('navbar-component', NavBarComponent);

// importacion de properties
import PropertyComponent from './components/property/PropertyComponent.vue';
app.component('property-component', PropertyComponent);

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

app.mount('#app');
