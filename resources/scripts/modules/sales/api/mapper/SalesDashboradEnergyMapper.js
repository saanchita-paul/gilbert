import COLOR from "@scripts/data/constants/COLOR";
import { DashboardSourceModel } from "@scripts/modules/sales/models/DashboardSourceModel";
import {getProviderBackgound, PLAN} from "@scripts/data/constants/ENERGY_PLAN";

export default {

    /**
     * energy usage analytics mapper
     * @param {Object} data
     * @returns {Object}
     */
    getEnergyDashboardData: (response) => {



        function getGasData(data, isRejected = false) {

            let lables = [
                'EA',
                'Sumo',
                'Origin',
                'PowerShop'
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
                    },
                    {
                        key: 'Flexi Plan', value: data?.ea_gas_flexi_plan,
                    },
                    {
                        key: 'Balance Plan', value: data?.ea_gas_balance_plan,
                    }
                ],
                [
                    {
                        key: 'Freedom', value: data?.sumo_gas_freedom,
                    },
                ],
                [
                    {
                        key: 'Advantage variable', value: data?.origin_advantage_variable,
                    },
                    {
                        key: 'Home Supply', value: data?.origin_supply,
                    },
                    {
                        key: 'Home Basic', value: data?.origin_basic,
                    },
                ],
                [
                    {
                        key: 'PowerShop 100% Carbon Neutral', value: data?.powershop_gas_carbon_neutral,
                    },
                    // {
                    //     key: 'PowerShop Switch Saver', value: data?.switch_saver,
                    // },
                ],
            ];

            const totalEaData =
                data?.ea_gas_no_frills
                + data?.ea_gas_basic_plan
                + data?.ea_gas_total_plan
                + data?.ea_gas_flexi_plan
                + data?.ea_gas_balance_plan;
            const totalSumoData = data?.sumo_gas_freedom;
            const totalPowerShopData = data?.powershop_gas_carbon_neutral;
            const totalOriginData = data?.origin_advantage_variable + data?.origin_supply + data?.origin_basic;

            const chartData = [totalEaData, totalSumoData, totalOriginData, totalPowerShopData];
            const plan = PLAN;
            const charBackGround = getProviderBackgound(plan)
            console.log('char ', chartData);
            const backgroundColorList = [
                '#542E89',
                '#03A9F4',
                '#FFC72C',
                '#FA0E6A',
            ];

            const rejectedbackgroundColor = [
                '#542E89',
                '#03A9F4',
                '#FFC72C',
                '#FA0E6A',
            ]


            return {
                labels: lables,
                toolTips: toolTips,
                total: totalEaData + totalSumoData + totalOriginData + totalPowerShopData,
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
                'EA',
                'Sumo',
                'Origin',
                'PowerSHop'
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
                    },
                    {
                        key: 'Flexi Plan', value: data?.ea_power_flexi_plan,
                    },
                    {
                        key: 'Balance Plan', value: data?.ea_power_balance_plan,
                    }
                ],
                [
                    {
                        key: 'Freedom', value: data?.sumo_power_freedom,
                    },
                ],
                [
                    {
                        key: 'Home Assist', value: data?.origin_home_assist,
                    },
                    {
                        key: 'Home Support', value: data?.origin_home_support,
                    },
                ],
                [
                    {
                        key: 'PowerShop 100% Carbon Neutral', value: data?.powershop_power_carbon_neutral,
                    },
                    {
                        key: 'PowerShop Switch Saver', value: data?.powershop_power_switch_saver,
                    },
                ],
            ];

            const totalEaData = data?.ea_power_no_frills
                + data?.ea_power_basic_plan
                + data?.ea_power_total_plan
                + data?.ea_power_flexi_plan
                + data?.ea_power_balance_plan;
            const totalSumoData = data?.sumo_power_freedom;
            const totalPowerShopData = data?.powershop_power_carbon_neutral +  data?.powershop_power_switch_saver;
            const totalOriginData = data?.origin_home_assist + data?.origin_home_support;


            const chartData = [totalEaData, totalSumoData, totalOriginData, totalPowerShopData];
            const backgroundColorList = [
                '#542E89',
                '#03A9F4',
                '#FFC72C',
                '#FA0E6A',
            ];
            const rejectedbackgroundColor = [
                '#542E89',
                '#03A9F4',
                '#FFC72C',
                '#FA0E6A',
            ];

            return {
                labels: lables,
                toolTips: toolTips,
                total: totalEaData + totalSumoData + totalOriginData + totalPowerShopData,
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
