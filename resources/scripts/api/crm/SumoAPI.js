import axios from "axios";
import SumoMapper from "@scripts/api/mappers/crm/SumoMapper";

const baseURL = process.env.MIX_SUMO_BASE_URL || 'https://stageapi.sumo.com.au/hood/v1.0';

const sumoAxios = axios.create({
    baseURL,
})

export default {
    qualifyAddress: async (address , leadId) => {
        try {
            let meta = SumoMapper.mapAddress(address , leadId);
            console.log("asddfasdf", address);
            const data = await sumoAxios.get('/qualification/address',{params: meta});
            return data.data;
        } catch (error) {
            console.log('in the error')
            console.log(error)
            throw error.data;
        }
    },
    products: async (sumoInfo , service_type , agent_name, lead_id) => {
        try {

            let meta   = SumoMapper.mapProduct(sumoInfo , service_type, agent_name, lead_id);
            const data = await sumoAxios.get('/products',{params: meta});
            let a = SumoMapper.planMapper(data.data);
            return SumoMapper.planMapper(data.data);
        } catch (error) {
            console.log('in the error')
            console.log(error)
            throw 'failed';
        }
    }
}
