import axios from 'axios';
import EnergyPlanMapper, {mapAllPlan, mapServices} from "@scripts/api/mappers/ea/EnergyPlanMapper";
import {mapEAPlanQuery} from "@scripts/api/mappers/ea/EAPlanQueryMapper";
import {getStateKey} from "@scripts/data/constants/STATES";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;
// const ROOT = `https://hb.leninsheikh.com/hood-dashboard/api`;

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
        const data = (await axios.get(`${ROOT}/ea-plans`, {params: query})).data.data
        return mapAllPlan(data)
    },
    /**
     *
     * @returns {Promise<EnergyPlan>}
     * @param query
     */
    getPlanDetailsByCustomerIdAndPlanType: async (query) => {
        query = mapEAPlanQuery(query);
        const data = (await axios.get(`${ROOT}/ea-plans/${query.plan_type}`, {params: query})).data.data
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
        return (await axios.get(`${ROOT}/api/webview/bdid?postcode=${postcode}&plan=${plan}`)).data.data
    },

    checkIfDateIsHoliday: async query => {
        query.state = getStateKey(query.state)
        // let u = 'https://hb.leninsheikh.com/hood-dashboard/api'
        return (await axios.get(`${ROOT}/is-holiday`, {params: query})).data.data?.is_holiday
    }
}
