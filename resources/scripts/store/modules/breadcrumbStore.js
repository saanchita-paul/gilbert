export default {
    state: {
        breadcrumbs: null
    },
    getters: {
        breadcrumbs: state => {
            return state.breadcrumbs;
        }
    },
    mutations: {
        addBreadcrumb(state, breadcrumbs) {
            state.breadcrumbs = breadcrumbs;
        },
        removeBreadcrumb(state) {
            state.breadcrumbs = null;
        }
    }
}
