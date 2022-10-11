import mainNavigation from "@scripts/data/mainNavigation";
import EnergyPlanApi from "@scripts/api/plans/EnergyPlanApi";

export default {
    getEnergyPlan: () => EnergyPlanApi.getEnergyPlan(),
    updateEnergyPlans: (plans) => EnergyPlanApi.updateEnergyPlans(plans),
}
