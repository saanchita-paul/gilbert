import COLOR from "@scripts/data/constants/COLOR";
import { DashboardSourceModel } from "@scripts/modules/sales/models/DashboardSourceModel";

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

        function getSubmittedData(data, waitingForConnectionData, acManualProcessing) {
            return {
                gasChartData: getGasData(data),
                powerChartData: getPowerData(data),
                total: data.total,
                waitingForConnection: waitingForConnectionData.total,
                acManualProcessing: acManualProcessing
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
            source_all: new DashboardSourceModel(response.application_summary.all),
            source_assigned: new DashboardSourceModel(response.application_summary.assigned),
            source_closed: new DashboardSourceModel(response.application_summary.closed),
            source_consent_pending: new DashboardSourceModel(response.application_summary.consent_pending),
            source_conversation_rate: new DashboardSourceModel(response.application_summary.conversation_rate),
            source_submitted: new DashboardSourceModel(response.application_summary.submitted),
            source_unassigned: new DashboardSourceModel(response.application_summary.unassigned),
            source_escalated: new DashboardSourceModel(response.application_summary.escalated),
            submitted: getSubmittedData(response.successful_submission, response.waiting_for_connection, response.ac_manual_processing),
            connected: getConnectedData(response.connected, response.successful_submission, response.rejected),
            rejected: getRejectedData(response.rejected, response.declined),
        };
    },

}
