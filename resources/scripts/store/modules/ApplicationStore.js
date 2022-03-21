/**
 * application Vuex store
 *
 * @type {Vuex.Store} store
 */
const store = {
    namespaced: true,
    state: {
        activeServiceTab: null,
    },
    getters: {
        activeServiceTab: state => state.activeServiceTab,
    },

    mutations: {
        /**
         * setActiveServiceTab
         *
         * @param {store.state} state
         * @param tab
         */
        setActiveServiceTab(state, tab) {
            state.activeServiceTab = tab;
        },
    },
}

export default store;
