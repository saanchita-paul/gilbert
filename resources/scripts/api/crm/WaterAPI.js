import axios from "axios";
import * as dayjs from "dayjs";


export default {
    saveWater: async (water, id) => {
        try {
            // water.connection_date = dayjs(water.connection_date,'DD/MM/YYYY').format('YYYY-MM-DD');
            let mapWaterData = {
                ...water,
                connection_date:  dayjs(water.connection_date,'DD/MM/YYYY').isValid()?
                    dayjs(water.connection_date,'DD/MM/YYYY')
                        .format('YYYY-MM-DD'): ''
            }
            const data = await axios.post('/api/applications/' + id +'/service/update',{...mapWaterData});
            console.log('data', data);
            return data.data.service;

        } catch (error) {
            return error.data;
        }
    },
}
