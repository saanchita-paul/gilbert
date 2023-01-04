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

        updateApp(state, app) {
            let allapp = state.applications;
            let index = allapp.findIndex(dt => dt.id == app.id);
            console.log('getting app', app, index);

            if(index !== -1 ) {
                allapp[index] = app;

            }
            state.applications = allapp;

        }
    }
}
