import Vue from 'vue';
import App from '@scripts/App.vue'
import Vuetify from "@scripts/plugins/Vuetify";
import Router from '@scripts/routes/router';
import '@scripts/plugins/VeeValidate';
import '@scripts/plugins/Axios';
import '@scripts/plugins/GoogleMap';
import store from '@scripts/store';
import {authUser, kickOut} from "@scripts/services/AuthService";
import GoogleMapService from "@scripts/services/GoogleMapService";
import '@scripts/directives';
import '@scripts/filters';
import '@scripts/plugins/DayJs'
import {EventBusPlugin} from "@scripts/plugins/EventBus";
import VueMask from "v-mask";

Vue.use(EventBusPlugin);
Vue.use(VueMask);
/**
 * initializing GoogleMapService
 */
GoogleMapService.initialize();

authUser().finally(() => {
    new Vue( {
        vuetify: Vuetify,
        router: Router,
        store,
        render: h => h(App)
    }).$mount('#app')
})

