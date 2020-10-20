export default {
    state: {
        isAuthenticated: false,
        user: null
    },
    getters: {
        isAuthenticated: state => state.isAuthenticated,
        user: state => state.user
    },
    mutations: {
        setUser(state, user) {
            if (user?.id) {
                state.user = user;
                state.isAuthenticated = true;
            } else {
                state.user = null;
                state.isAuthenticated = false;
            }
        }
    }
}
