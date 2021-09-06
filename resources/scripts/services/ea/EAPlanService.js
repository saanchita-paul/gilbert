import EnergyApi from "@scripts/api/ea/EnergyApi";

export default {

    getAllPlans: query => EnergyApi.getAllPlans(query),
    /**
     *
     * @param plan
     * @returns {Promise<EnergyPlan>}
     */
    getPlanDetailsByPlanType(plan) {
        return EnergyApi.getPlanDetailsByCustomerIdAndPlanType(plan)
    },

    /**
     *
     * @param postcode
     * @param plan
     * @returns TarrifPlan data
     */
    getTarrifDetailsByPostcode(postcode, plan) {
        return EnergyApi.getTarrifPlansByPostcode(
            postcode,
            plan
        )
    },

    checkIfDateIsHoliday: query => EnergyApi.checkIfDateIsHoliday(query)
}
