import Store from "@scripts/store";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
    getIsBothEnergySelected: () => Store.getters.isBothEnergySelected,
    setIsBothEnergySelected: isSelected =>
        Store.commit("setIsBothEnergySelected", isSelected),

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

    setUtilityDetails: (services) => {
        let powerService = services.find(data => data.service_type === "power");
        let gasService = services.find(data => data.service_type === "gas");

        UtilityStoreService.setPowerProvider(powerService?.provider_name);
        UtilityStoreService.setPowerPlan(powerService?.plan_type);
        UtilityStoreService.setGasProvider(gasService?.provider_name);
        UtilityStoreService.setGasPlan(gasService?.plan_type);

        if (
            powerService !== null &&
            powerService?.plan_type !== null &&
            powerService?.provider_name === gasService?.provider_name &&
            powerService?.plan_type === gasService?.plan_type &&
            LeadApplicationService.canSubmitEnergy(services)
        ) {
            UtilityStoreService.setIsBothEnergySelected(true);
        }
    }
};
