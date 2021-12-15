import COLOR from "@scripts/data/constants/COLOR";

export default {

    /**
     * energy usage analytics mapper
     * @param {Object} data
     * @returns {Object}
     */
    getEnergyDashboardData: (response) => {


        function getGasData(data, isRejected = false) {

            let lables = [
                'Energy Australia',
                'Sumo',
            ];

            let toolTips = [
                [
                    {
                        key: 'No Frills', value: data?.ea_gas_no_frills,
                    },
                    {
                        key: 'Basic', value: data?.ea_gas_basic_plan,
                    },
                    {
                        key: 'Total Plan', value: data?.ea_gas_total_plan,
                    }
                ],
                [
                    {
                        key: 'Freedom', value: data?.sumo_gas_freedom,
                    },
                ],
            ];

            const totalEaData = data?.ea_gas_no_frills + data?.ea_gas_basic_plan + data?.ea_gas_total_plan;
            const totalSumoData = data?.sumo_gas_freedom;

            const chartData = [totalEaData, totalSumoData];
            const backgroundColorList = [
                '#542E89',
                '#03A9F4',
            ];

            const rejectedbackgroundColor = [
                '#542E89',
                '#E91E63'
            ]


            return {
                labels: lables,
                toolTips: toolTips,
                total: totalEaData + totalSumoData,
                datasets: [{
                    label: 'My First Dataset',
                    data: chartData,
                    backgroundColor: !isRejected ? backgroundColorList : rejectedbackgroundColor,
                }
                ]
            };

        }

        function getPowerData(data, isRejected) {
            let lables = [
                'Energy Australia',
                'Sumo',
            ];

            let toolTips = [
                [
                    {
                        key: 'No Frills', value: data?.ea_power_no_frills,
                    },
                    {
                        key: 'Basic', value: data?.ea_power_basic_plan,
                    },
                    {
                        key: 'Total Plan', value: data?.ea_power_total_plan,
                    }
                ],
                [
                    {
                        key: 'Freedom', value: data?.sumo_power_freedom,
                    },
                ],
            ];

            const totalEaData = data?.ea_power_no_frills + data?.ea_power_basic_plan + data?.ea_power_total_plan;
            const totalSumoData = data?.sumo_power_freedom;

            const chartData = [totalEaData, totalSumoData];
            const backgroundColorList = [
                '#542E89',
                '#03A9F4',
            ];
            const rejectedbackgroundColor = [
                '#542E89',
                '#E91E63'
            ];

            return {
                labels: lables,
                toolTips: toolTips,
                total: totalEaData + totalSumoData,
                datasets: [{
                    label: 'My First Dataset',
                    data: chartData,
                    backgroundColor: !isRejected ? backgroundColorList : rejectedbackgroundColor,
                }
                ]
            };
        }

        function getSubmittedData(data) {
            return {
                gasChartData: getGasData(data),
                powerChartData: getPowerData(data),
                total: data.total

            }
        }

        function getConvertedData(data) {
            return {
                gasChartData: getGasData(data),
                powerChartData: getPowerData(data),
                total: data.total
            }
        }

        function getRejectedData(data, declined) {
            return {
                gasChartData: getGasData(data, true),
                powerChartData: getPowerData(data, true),
                total: data.total,
                declined: declined.total
            }
        }

        return {

            submitted: getSubmittedData(response.submission),
            converted: getConvertedData(response.conversions),
            rejected: getRejectedData(response.rejected, response.declined),
            total_open_application: response.total_open_application,
            total_consent_pending: response.total_consent_pending,
            total_closed: response.total_closed,

        };
    },

}
