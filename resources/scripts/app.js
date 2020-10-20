import Vue from 'vue';
import App from '@scripts/App.vue'
import Vuetify from "@scripts/plugins/Vuetify";
import Router from '@scripts/routes/router';
import '@scripts/plugins/VeeValidate';
import '@scripts/plugins/Axios';
import store from '@scripts/store';


new Vue( {
    vuetify: Vuetify,
    router: Router,
    store,
    render: h => h(App)
}).$mount('#app')
