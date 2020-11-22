import DashboardMapper from "@scripts/api/mappers/DashboardMapper";
import DateRange from "@scripts/models/DateRange";
import axios from 'axios';

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {CustomerSummary}
     */
    getDashboardSummary: async (dateRange) => {

        const data = (await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/customers/summary?start=${dateRange.start}&end=${dateRange.end}`)).data;
        return DashboardMapper.toClientList(data);
        const datas = [
            {
                date: '12/06/2020',
                total_customer: 100,
                new_customer: 70,
                active_customer: 40,
                engaged_customer: 30,
                message_sent: 215,
                message_received: 140,
            },
            {
                date: '13/06/2020',
                total_customer: 110,
                new_customer: 80,
                active_customer: 40,
                engaged_customer: 40,
                message_sent: 225,
                message_received: 150,
            },
            {
                date: '14/06/2020',
                total_customer: 120,
                new_customer: 90,
                active_customer: 40,
                engaged_customer: 50,
                message_sent: 235,
                message_received: 160,
            },
        ]
        return DashboardMapper.toClientList(data);
    },

    getUtilitySummary: dateRange => {
        return {
            energy: {
                colors: ['red', 'orange', 'green', 'black'],
                data: [25, 40, 35, 0],
                labels: ['High', 'Medium', 'Low', 'Not sure']
            },
            property: {
                colors: ['red', 'orange'],
                data: [77, 23],
                labels: ['Own', 'Rent']
            },
            household: {
                colors: ['red', 'orange', 'green', 'black'],
                data: [25, 25, 35, 15],
                labels: ['1-2', '2-3', '3-4', '4+']
            },
        }
    }
}
