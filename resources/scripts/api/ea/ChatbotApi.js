
import axios from "axios";
import {mapState} from "vuex";
import {getStateKey, STATES} from "@scripts/data/constants/STATES";
const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;
export default {

    /**
     * Get all plans
     *
     * @param query
     *
     * @return {Promise<*>}
     */
    getNextBusinessDay: async state => {

        state = getStateKey(state)
        const data = (await axios.get(`${ROOT}/next-business-day`, {params: {state: state}})).data;
       return data?.next_business_day;
    },

}
