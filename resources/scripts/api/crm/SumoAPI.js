import SumoMapper from "@scripts/mappers/crm/SumoMapper";
import axios from "axios";
const baseURL = 'https://stageapi.sumo.com.au/hood/v1.0';

const sumoAxios = axios.create({
    baseURL,
})

export default {
    qualifyAddress: async (address) => {
        try {
            meta = SumoMapper.mapAddress(address);
            const data = await sumoAxios.get('/qualification/address',{params: {address}});
            console.log('sumo' , data);
            return data.data;
        } catch (error) {
            return error.data;
        }
    },
}
