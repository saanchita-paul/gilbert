export default {
    state: {
        energyServices: [],
        isBothServiceSelected: false,
        powerProvider: null,
        gasProvider: null,
        powerPlan: null,
        gasPlan: null
    },
    getters: {
        energyService: state => {
            return state.energyService;
        },
        isBothServiceSelected: state => {
            return state.isBothServiceSelected;
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
        setEnergyService(state, service) {
            state.energyService = service;
        },
        setIsBothServiceSelected(state, value) {
            state.isBothServiceSelected = value;
        },
        setPowerProvider(state, provider) {
            state.powerProvider = provider;
        },
        setGasProvider(state, provider) {
            state.gasProvider = provider;
        },
        setPowerPlan(state, plan) {
            state.powerPlan = plan;
        },
        setGasPlan(state, plan) {
            state.gasPlan = plan;
        }
    }
};
