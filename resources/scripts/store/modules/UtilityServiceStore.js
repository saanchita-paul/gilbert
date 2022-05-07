export default {
    state: {
        isBothEnergySelected: false,
        powerStatus: null,
        powerProvider: null,
        powerPlan: null,
        gasStatus: null,
        gasProvider: null,
        gasPlan: null,
        waterStatus: null,
        internetStatus: null
    },
    getters: {
        isBothEnergySelected: state => {
            return state.isBothEnergySelected;
        },
        powerStatus: state => {
            return state.powerStatus;
        },
        powerProvider: state => {
            return state.powerProvider;
        },
        powerPlan: state => {
            return state.powerPlan;
        },
        gasStatus: state => {
            return state.gasStatus;
        },
        gasProvider: state => {
            return state.gasProvider;
        },
        gasPlan: state => {
            return state.gasPlan;
        }
    },
    mutations: {
        setIsBothEnergySelected(state, value) {
            state.isBothEnergySelected = value;
        },
        setPowerStatus(state, status) {
            state.powerStatus = status;
        },
        setPowerProvider(state, provider) {
            state.powerProvider = provider;
        },
        setPowerPlan(state, plan) {
            state.powerPlan = plan;
        },
        setGasStatus(state, status) {
            state.gasStatus = status;
        },
        setGasProvider(state, provider) {
            state.gasProvider = provider;
        },
        setGasPlan(state, plan) {
            state.gasPlan = plan;
        },
        setBothProvider(state, provider) {
            state.powerProvider = provider;
            state.gasProvider = provider;
        },
        setBothPlan(state, plan) {
            state.powerPlan = plan;
            state.gasPlan = plan;
        }
    }
};
