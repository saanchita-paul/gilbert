import COLOR from "@scripts/data/constants/COLOR";

export default {

    /**
     * energy usage analytics mapper
     * @param {Object} data
     * @returns {Object}
     */
    getSummaryData: (response) => {

        function getConnectionPlan(data) {
            let labels = [];
            let values = [];
            data.forEach((dt) => {
                values.push(dt.percentage);
                labels.push(dt.utility_plan);
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
            let labels = [];
            let values = [];
            data.forEach((dt) => {
                values.push(dt.percentage);
                labels.push(dt.which_utility);
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
            let labels = [];
            let values = [];

            data.find(dt=> dt.utility_plan === 'Total Plan (Home)')
            data.forEach((dt,k) => {
                values.push(dt.percentage);
                labels.push(dt.rent);
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
            console.log(data);
            let labels = Object.keys(data);
            let values = Object.values(data);
            // value..reduce((a, b) => a + b, 0)

           return {
                    labels: labels,
                    datasets: [{
                    maxBarThickness: 28,
                    borderWidth: 1,
                    data: values,
                }]
            }
        }

        return {
            connection_plan: getConnectionPlan(response.connectionSummary.plan),
            connection_type: getConnectionType(response.connectionSummary.connectionType),
            tenancy_type: getTenancyType(response.connectionSummary.tenancyType),
            age_group: getAgeGroup(response.connectionSummary.ageGroupSummary)

        };
    },

}
