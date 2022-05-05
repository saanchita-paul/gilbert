export default {
    state: {
        isBothEnergySelected: false,
        powerProvider: null,
        gasProvider: null,
        powerPlan: null,
        gasPlan: null
    },
    getters: {
        isBothEnergySelected: state => {
            return state.isBothEnergySelected;
        },
        powerProvider: state => {
            return state.powerProvider;
        },
        gasProvider: state => {
            return state.gasProvider;
        },
        powerPlan: state => {
            return state.powerPlan;
        },
        gasPlan: state => {
            return state.gasPlan;
        }
    },
    mutations: {
        setIsBothEnergySelected(state, value) {
            state.isBothEnergySelected = value;
        },
        setPowerProvider(state, provider) {
            state.powerProvider = provider;
        },
        setGasProvider(state, provider) {
            state.gasProvider = provider;
        },
        setBothProvider(state, provider) {
            state.powerProvider = provider;
            state.gasProvider = provider;
        },
        setPowerPlan(state, plan) {
            state.powerPlan = plan;
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
