import DashboardMapper from "@scripts/api/mappers/DashboardMapper";
import DateRange from "@scripts/models/DateRange";

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {CustomerSummary}
     */
    getDashboardSummary: (dateRange) => {
        const data = [
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
    }
}
