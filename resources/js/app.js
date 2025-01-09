import './bootstrap';
import { createApp } from 'vue';
import axios from "axios";
import Swal from "sweetalert2";
import shared from "./utils/shared";
import $ from 'jquery'; // Importar jQuery
import { GlobalVariables } from './utils/shared/Services/GlobalVariables'; // Importar variables globales
import { VueDraggableNext } from 'vue-draggable-next';

import PrimeVue from "primevue/config";
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable';
import Select from 'primevue/select';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Galleria from "primevue/galleria";
import Image from "primevue/image";
import FloatLabel from "primevue/floatlabel";
import Dialog from "primevue/dialog";
import MultiSelect from "primevue/multiselect";
import FileUpload from "primevue/fileupload";
import DatePicker from "primevue/datepicker";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import Password from "primevue/password";
import ToggleButton from 'primevue/togglebutton';


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

//importacion de incident
import IncidentComponent from './components/incident/IncidentComponent.vue';
app.component('incident-component', IncidentComponent);

import IncidentActionComponent from './components/incidentAction/IncidentActionComponent.vue';
app.component('incident-action-component', IncidentActionComponent);

import IncidentKanbanComponent from './components/incident/IncidentKanbanComponent.vue';
app.component('incident-kanban-component', IncidentKanbanComponent);

// importacion de provider
import ProviderComponent from './components/provider/ProviderComponent.vue';
app.component('provider-component', ProviderComponent);

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
app.component('Card', Card);
app.component('InputText', InputText);
app.component('DataTable', DataTable);
app.component('Select', Select);
app.component('Column', Column);
app.component('Button', Button);
app.component('Tag', Tag);
app.component('Galleria', Galleria);
app.component('Image', Image);
app.component('FloatLabel', FloatLabel);
app.component('Dialog', Dialog);
app.component('MultiSelect', MultiSelect);
app.component('FileUpload', FileUpload);
app.component('DatePicker', DatePicker);
app.component('Textarea', Textarea);
app.component('InputNumber', InputNumber);
app.component('Password', Password);
app.component('VueDraggableNext', VueDraggableNext);
app.component('ToggleButton', ToggleButton);

// Registrar funciones compartidas
app.mixin(shared.AlertsComponent);
app.mixin(shared.ReadHttpStatusErrors);
app.mixin(shared.HelperFunctions);
app.mixin(shared.RelationsTables);

// Configura Axios globalmente
app.config.globalProperties.$axios = axios;

// Configura SweetAlert2 globalmente
app.config.globalProperties.$swal = Swal;

// Configura variables globales
app.config.globalProperties.$globals = GlobalVariables;

window.$ = $;
window.jQuery = $;

$.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results ? results[1] : null;
};

app.mount('#app');
