import InternetServiceInfo from "@scripts/models/crm/InternetServiceInfo";

export default {
    namespaced: true,

    state: {
        internetServiceInfo: new InternetServiceInfo(),
    },

    getters: {
        getInternetServiceInfo: state => state.internetServiceInfo,
    },

    mutations: {
        /**
         * updating  internet service info state
         * @param state
         * @param {InternetServiceInfo} internetServiceInfo
         */
        setInternetServiceInfo(state, internetServiceInfo) {
            state.internetServiceInfo = internetServiceInfo;
        },
    }
}
