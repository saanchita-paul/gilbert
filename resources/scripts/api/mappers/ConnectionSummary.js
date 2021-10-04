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
                switch (dt.which_utility.toLowerCase()) {
                    case 'gas':
                        values[0] = dt.percentage;
                        break
                    case 'electricity':
                        values[1] = dt.percentage;
                        break
                    case 'electricity_and_gas':
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
                if(dt.rent && dt.rent.toString() === "1")
                {
                    dt.rent = "Rent";
                }
                else
                {
                    dt.rent = "Own"
                }
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

        return {

            connection_plan: getConnectionPlan(response.connectionSummary.plan),
            connection_type: getConnectionType(response.connectionSummary.connectionType),
            tenancy_type: getTenancyType(response.connectionSummary.tenancyType),
        };
    },

}
