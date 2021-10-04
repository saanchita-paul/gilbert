import Vue from 'vue';
// import 'vuetify/dist/vuetify.min.css'
import Vuetify from 'vuetify/lib';
import COLOR from "../data/constants/COLOR";

Vue.use(Vuetify);

const opts = {
    theme: {
        themes: {
            light: {...COLOR.themes.light}
        },
    },
};

export default new Vuetify(opts)
