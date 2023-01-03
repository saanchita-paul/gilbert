export default {
    state: {
        applications: [],
    },
    getters: {
        applications: state => state.applications,
    },
    mutations: {
        setchatbotApplications(state, applications) {
            state.applications = applications;
        },

        updateApp(state, i, app) {
            state.application[i] = app;
        }
    }
}
