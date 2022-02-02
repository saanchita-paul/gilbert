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

        function getSubmittedData(data, waitingForConnectionData, acManualProcessing, manualProcessing) {
            return {
                gasChartData: getGasData(data),
                powerChartData: getPowerData(data),
                total: data.total,
                waitingForConnection: waitingForConnectionData.total,
                acManualProcessing: acManualProcessing,
                manualProcessing: manualProcessing
            }
        }

        function getConnectedData(data, submittedData, rejectedData) {

            let conversionRate = null;
            if(rejectedData.total === 0 && submittedData.total === 0) {
                conversionRate = 0;
            } else {
                conversionRate = ((data.total / (submittedData.total + rejectedData.total)) * 100).toFixed(1);
            }

            return {
                gasChartData: getGasData(data),
                powerChartData: getPowerData(data),
                total: data.total,
                conversiton_rate: conversionRate
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
            total_new_application: response.total_new_application,
            total_unassigned: response.unassigned_application,
            total_assigned: response.assigned_application,
            total_consent_pending: response.total_consent_pending,
            total_closed: response.total_closed,
            submitted: getSubmittedData(response.successful_submission, response.waiting_for_connection, response.ac_manual_processing, response.manual_processing),
            connected: getConnectedData(response.connected, response.successful_submission, response.rejected),
            rejected: getRejectedData(response.rejected, response.declined),
        };
    },

}
