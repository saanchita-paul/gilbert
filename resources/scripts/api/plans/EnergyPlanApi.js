import axios from "axios";
import EnergyPlanMapper from "@scripts/api/mappers/EnergyPlanMapper";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    /**
     *  getting all customer plans form CB
     *
     * @returns {Object}
     */
    getEnergyPlan: async () => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/energy-plan`);
            return EnergyPlanMapper.mapEnergyPlan(data.data?.plans);
        } catch (error) {
            console.log('error data', error);
            return error.data;
        }
    },
    /**
     *  getting all customer data from CB
     *
     * @returns {Object}
     */
    updateEnergyPlans: async (plans) => {
        try {
            const data = await axios.put(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/shuffle-energy-plan`, {plans: {...plans}});
            return EnergyPlanMapper.mapEnergyPlan(data.data?.plans);
        } catch (error) {
            console.log('error data', error);
            return error.data;
        }
    },
}
