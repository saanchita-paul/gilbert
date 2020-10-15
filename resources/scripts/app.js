import Vue from 'vue';
import App from '@scripts/App.vue'
import Vuetify from "@scripts/plugins/Vuetify";
import Router from '@scripts/routes/router';
import '@scripts/plugins/VeeValidate';

new Vue( {
    vuetify: Vuetify,
    router: Router,
    render: h => h(App)
}).$mount('#app')
