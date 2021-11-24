import axios from "axios";
import SumoMapper from "@scripts/api/mappers/crm/SumoMapper";

const baseURL = 'https://stageapi.sumo.com.au/hood/v1.0';

const sumoAxios = axios.create({
    baseURL,
})

export default {
    qualifyAddress: async (address , leadId) => {
        try {
            console.log('calling sumo api')
            let meta = SumoMapper.mapAddress(address , leadId);
            console.log(meta)
            const data = await sumoAxios.get('/qualification/address',{params: meta});
            console.log('calling sumo api')
            console.log('sumo api location data' , data);
            return data.data;
        } catch (error) {
            console.log('in the error')
            console.log(error)
            throw error.data;
        }
    },
    products: async (sumoInfo , service_type , agent_name, lead_id) => {
        try {

            console.log('print sumoInfo info' , sumoInfo);

            console.log('calling sumo api' , sumoInfo);
            let meta   = SumoMapper.mapProduct(sumoInfo , service_type, agent_name, lead_id);
            console.log('printing meta of sumo' , meta)
            const data = await sumoAxios.get('/products',{params: meta});
            console.log('calling sumo api')
            console.log('sumo' , data);
            let a = SumoMapper.planMapper(data.data);
            console.log('mapper data' , a)
            return SumoMapper.planMapper(data.data);
        } catch (error) {
            console.log('in the error')
            console.log(error)
            throw 'failed';
        }
    }
}
