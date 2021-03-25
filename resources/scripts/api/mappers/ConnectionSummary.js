import COLOR from "@scripts/data/constants/COLOR";

export default {

    /**
     * energy usage analytics mapper
     * @param {Object} data
     * @returns {Object}
     */
    getSummaryData: (response) => {
        function getConnectionPlan(data) {
            let labels = ['Total Plan', 'Basic Home', 'No Frills'];
            let values = [0, 0, 0];
            data.forEach((dt) => {
                switch (dt.utility_plan.toLowerCase()) {
                    case 'total_plan':
                        values[0] = dt.percentage;
                        break
                    case 'basic_plan':
                        values[1] = dt.percentage;
                        break
                    case 'no_frills':
                        values[2] = dt.percentage;
                        break
                    default:
                        break
                }

            });

            return {
                title: "Utility Type",
                chart_data: {
                    labels:labels ,
                    datasets: [{
                        borderWidth: 1,
                        data: values,
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.primary,
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }],
                }
            }
        }

        function getConnectionType(data) {
            let labels = ['Gas', 'Electricity', 'Dual Fuel'];
            let values = [0, 0, 0];
            data.forEach((dt) => {
                switch (dt.which_utility) {
                    case 'Gas':
                        values[0] = dt.percentage;
                        break
                    case 'Electricity':
                        values[1] = dt.percentage;
                        break
                    case 'Dual Fuel':
                        values[2] = dt.percentage;
                        break
                    default:
                        break
                }
            })

            return {
                title: "Connection Type",
                chart_data: {
                    labels: labels,
                    datasets: [{
                        borderWidth: 1,
                        data: values,
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.primary,
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }
            }
        }

        function getTenancyType(data) {
            let labels = ['Own', 'Rent'];
            let values = [0, 0];
            data.forEach((dt) => {
                switch (dt.rent) {
                    case 'Own':
                        values[0] = dt.percentage;
                        break
                    case 'Rent':
                        values[1] = dt.percentage;
                        break
                    default:
                        break
                }

            })
            return {
                title: "Tenancy Type",
                chart_data: {
                    labels: labels,
                    datasets: [
                        {
                            borderWidth: 1,
                            data: values,
                            fill: false,
                            backgroundColor: [
                                COLOR.themes.light.secondary,
                                COLOR.themes.light.purple3,
                            ]
                        }
                    ]
                }
            }
        }

        function getAgeGroup(data) {
            let labels = Object.keys(data);
            let values = Object.values(data);
            let totalValue = values.reduce((a, b) => a + b, 0);
           return {
                    labels: labels,
                    datasets: [{
                    maxBarThickness: 28,
                    borderWidth: 1,
                    data: values.map(d=> Math.floor (d/totalValue * 100)),
                }]
            }
        }

        return {

            connection_plan: getConnectionPlan(response.connectionSummary.plan),
            connection_type: getConnectionType(response.connectionSummary.connectionType),
            tenancy_type: getTenancyType(response.connectionSummary.tenancyType),
            age_group: getAgeGroup(response.ageGroupSummary)

        };
    },

}
