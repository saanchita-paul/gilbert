import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";

export default {
    namespaced: true,

    state: {
        applicationSummary: new ApplicationSummary(),
    },

    getters: {
        /**
         *
         * @param state
         * @returns {ApplicationSummary}
         */
        getApplicationSummary: state => state.applicationSummary,

    },

    mutations: {
        /**
         * updating  lead state
         * @param state
         * @param {ApplicationSummary} applicationSummary
         */
        setLeadSummary(state, applicationSummary) {
            state.applicationSummary = applicationSummary;
        },
    }
}
