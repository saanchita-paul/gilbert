import Vue from 'vue'
import Vuex from 'vuex'
import authStore from "@scripts/store/modules/authStore";
import breadcrumbStore from "@scripts/store/modules/breadcrumbStore";

Vue.use(Vuex)

export default new Vuex.Store({
    modules: { authStore, breadcrumbStore }
})
