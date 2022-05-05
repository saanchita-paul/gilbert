import Store from "@scripts/store";

export default {
    getIsBothEnergySelected: () => Store.getters.isBothEnergySelected,
    setIsBothEnergySelected: isSelected => Store.commit("setIsBothEnergySelected", isSelected),

    getPowerProvider: () => Store.getters.powerProvider,
    setPowerProvider: provider => Store.commit("setPowerProvider", provider),

    getGasProvider: () => Store.getters.gasProvider,
    setGasProvider: provider => Store.commit("setGasProvider", provider),

    getPowerPlan: () => Store.getters.powerPlan,
    setPowerPlan: plan => Store.commit("setPowerPlan", plan),

    getGasPlan: () => Store.getters.gasPlan,
    setGasPlan: plan => Store.commit("setGasPlan", plan),

    setBothProvider: provider => Store.commit("setBothProvider", provider),
    setBothPlan: plan => Store.commit("setBothPlan", plan),
};
