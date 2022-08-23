import Store from "@scripts/store";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import {planTypeNameMapper} from "@scripts/data/ProviderAndPlanNameMapper";

export default {
    getIsBothEnergySelected: () => Store.getters.isBothEnergySelected,
    setIsBothEnergySelected: isSelected =>
        Store.commit("setIsBothEnergySelected", isSelected),

    getPowerStatus: () => Store.getters.powerStatus,
    setPowerStatus: status => Store.commit("setPowerStatus", status),
    getPowerProvider: () => Store.getters.powerProvider,
    setPowerProvider: provider => Store.commit("setPowerProvider", provider),
    getPowerPlan: () => Store.getters.powerPlan,
    setPowerPlan: plan => Store.commit("setPowerPlan", plan),

    getGasStatus: () => Store.getters.gasStatus,
    setGasStatus: status => Store.commit("setGasStatus", status),
    getGasProvider: () => Store.getters.gasProvider,
    setGasProvider: provider => Store.commit("setGasProvider", provider),
    getGasPlan: () => Store.getters.gasPlan,
    setGasPlan: plan => Store.commit("setGasPlan", plan),

    setBothProvider: provider => Store.commit("setBothProvider", provider),
    setBothPlan: (plan, provider = null, names) => {
        console.log('STORE BOTH : ', plan, provider, names);
        if(provider === 'origin' && plan !== null) {
            // Store.commit("setPowerPlan", planTypeNameMapper.PLAN_ORIGIN_HOME_ASSIST);
            // Store.commit("setGasPlan", planTypeNameMapper.PLAN_ORIGIN_ADVANTAGE_VARIABLE);
            // Store.commit("setPowerPlan", plan);
            // Store.commit("setGasPlan", plan);
        } else {
            Store.commit("setBothPlan", plan)
        }
    },

    setUtilityDetails: services => {
        let powerService = services.find(data => data.service_type === "power");
        let gasService = services.find(data => data.service_type === "gas");

        UtilityStoreService.setPowerStatus(powerService?.status);
        UtilityStoreService.setPowerProvider(powerService?.provider_name);
        UtilityStoreService.setPowerPlan(powerService?.plan_type);
        UtilityStoreService.setGasStatus(gasService?.status);
        UtilityStoreService.setGasProvider(gasService?.provider_name);
        UtilityStoreService.setGasPlan(gasService?.plan_type);

        // if (
        //     powerService !== null &&
        //     powerService?.plan_type !== null &&
        //     powerService?.provider_name === gasService?.provider_name &&
        //     powerService?.plan_type === gasService?.plan_type &&
        //     LeadApplicationService.canSubmitAnyEnergy(services)
        // ) {
        //     UtilityStoreService.setIsBothEnergySelected(true);
        // }
    }
};
