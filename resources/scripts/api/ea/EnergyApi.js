import axios from 'axios';
import EnergyPlanMapper, {mapAllPlan, mapServices} from "@scripts/api/mappers/ea/EnergyPlanMapper";
import {mapEAPlanQuery} from "@scripts/api/mappers/ea/EAPlanQueryMapper";


export default {

    /**
     * Get all plans
     *
     * @param query
     *
     * @return {Promise<*>}
     */
    getAllPlans: async query => {
        query = mapEAPlanQuery(query)
        const data = (await axios.get(
            `https://hb.leninsheikh.com/hood-dashboard/api/ea-plans`,
            {params: query})
        ).data.data
        return mapAllPlan(data)
    },
    /**
     *
     * @returns {Promise<EnergyPlan>}
     * @param query
     */
    getPlanDetailsByCustomerIdAndPlanType: async (query) => {
        query = mapEAPlanQuery(query);
        const data = (await axios.get(
            `https://hb.leninsheikh.com/hood-dashboard/api/ea-plans/${query.plan_type}`,
            {params: query})
        ).data.data
        return EnergyPlanMapper.fromServer(data, query.plan_type);
    },

    /**
     *
     * @param postcode
     * @param plan
     * @param customerId
     * @returns {Promise<data>}
     */
     getTarrifPlansByPostcode: async (postcode, plan, customerId) => {
        return (await axios.get(`/api/webview/bdid?postcode=${postcode}&plan=${plan}`)).data.data
    }
}
