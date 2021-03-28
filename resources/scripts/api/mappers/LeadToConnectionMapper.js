import COLOR from "@scripts/data/constants/COLOR";

export default {

    mapLeadToConnection(response) {
        let chartDataSet = [];
        chartDataSet.push(response.lead_by_channel);
        chartDataSet.push(response.service_connection);
        chartDataSet.push(response.connection_type);
        chartDataSet.push(response.connection_submitted);
        chartDataSet.push(response.connection_accepted);
        return {
            labels: [
                ['Lead by', 'Channels'],
                ['Service', 'Connection'],
                ['Connection', 'Type'],
                ['Connection', 'Submitted'],
                ['Connection', 'Accepted']
            ],
            datasets: [{
                borderWidth: 1,
                data: chartDataSet,
                fill: false,
                maxBarThickness: 55,
                backgroundColor: COLOR.themes.light.primary,
            }]

        };
    }

}
