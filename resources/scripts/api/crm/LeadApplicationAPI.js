import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";
import AppMetricsMapper from "@scripts/api/mappers/crm/AppMetricsMapper";

const data = [
    {
        id: 1,
        title: 'Applications',
        lead_count: 500,
        status: 'Total submitted',
    },
    {
        id: 2,
        title: 'Non-payable',
        lead_count: 100,
        status: 'Non Connected',
    },
    {
        id: 3,
        title: 'Power',
        lead_count: 300,
        status: 'Successfully connected',
    },
    {
        id: 4,
        title: 'Gas',
        lead_count: 200,
        status: 'Successfully connected',
    },
    {
        id: 5,
        title: 'Internet',
        lead_count: 200,
        status: 'Successfully connected',
    },
    {
        id: 5,
        title: 'Water',
        lead_count: 200,
        status: 'Successfully connected',
    },
];
export default {

    getMetrics() {
        try {
            // const data = await axios.get('/');
            return AppMetricsMapper.mapAppMetricList(data);

        } catch (error) {
            return error.data;
        }
    }
}
