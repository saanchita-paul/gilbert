import Vue from 'vue'
import Vuex from 'vuex'
import authStore from "@scripts/store/modules/authStore";
import breadcrumbStore from "@scripts/store/modules/breadcrumbStore";
import applicationStore from "@scripts/store/modules/ApplicationStore";
import UtilityServiceStore from "@scripts/store/modules/UtilityServiceStore";
import addressValidationStore from "@scripts/store/modules/addressValidationStore";
import leadSummaryStore from "@scripts/store/modules/LeadSummaryStore";
import internetServiceInfoStore from "@scripts/store/modules/InternetServiceInfoStore";

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        authStore,
        breadcrumbStore,
        application: applicationStore,
        UtilityServiceStore,
        addressValidationStore,
        leadSummaryStore,
        internetServiceInfoStore
    }
});
