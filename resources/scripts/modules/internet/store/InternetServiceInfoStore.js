import InternetServiceInfo from "@scripts/models/crm/InternetServiceInfo";

export default {
    namespaced: true,

    state: {
        internetServiceInfo: new InternetServiceInfo(),
        internetStatus: null,
        internetProvider: null,
        internetPlan: null,
        isValidForm: true
    },

    getters: {
        getInternetServiceInfo: state => state.internetServiceInfo,
        internetStatus: state => state.internetStatus ?? state.internetServiceInfo.connection_service.status,
        internetProvider: state => state.internetProvider ?? state.internetServiceInfo.connection_service.provider_name,
        internetPlan: state => state.internetPlan ?? state.internetServiceInfo.connection_service.plan_type,
        isValidForm: state => state.isValidForm
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
        setInternetProvider(state, provider) {
            state.internetProvider = provider;
        },
        setInternetPlan(state, plan) {
            state.internetPlan = plan;
        },
        setInternetStatus(state, status) {
            state.internetStatus = status;
        },
        setIsValidForm(state, status) {
            state.isValidForm = status;
        },
    }
}
