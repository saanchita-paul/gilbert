import InternetServiceInfo from "@scripts/models/crm/InternetServiceInfo";

export default {
    namespaced: true,

    state: {
        internetServiceInfo: new InternetServiceInfo(),
        internetStatus: null,
        internetPlan: null,
    },

    getters: {
        getInternetServiceInfo: state => state.internetServiceInfo,
        internetStatus: state => state.internetStatus,
        internetPlan: state => state.internetPlan,
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
        setInternetPlan(state, plan) {
            state.internetPlan = plan;
        },
        setInternetStatus(state, status) {
            state.internetStatus = status;
        },
    }
}
