
import axios from "axios";
import MetricsMapper from "@scripts/modules/realestate/api/mappers/MetricsMapper";
export default {
    getMatrics: async (officeId) => {
        try {
            const data = await axios.get('/api/offices/office/' + officeId + '/get-metrics');
            return MetricsMapper.mapMetrics(data.data.data);
        } catch (error) {
            return error.data;
        }
    },
}
