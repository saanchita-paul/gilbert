import EnergyPlanMapper from "@scripts/api/mappers/ea/EnergyPlanMapper";

export default {
    mapEnergyPlan: (data, plan_type) => {
        return EnergyPlanMapper.fromServer(data, plan_type);
    } ,

}
