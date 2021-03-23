import axios from 'axios';
import COLOR from "@scripts/data/constants/COLOR";

export default {
    getLeadAnalytics: async () => {
        return {
            lead: {
                value: '12.6K',
                tooltip: null,
                chartData: {
                    labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                    datasets: [{
                        borderWidth: 1,
                        data: [12, 24, 30, 18, 24, 36, 24],
                        fill: false,
                        maxBarThickness: 13,
                        backgroundColor: COLOR.themes.light.secondary,
                    }]
                }
            },
            qualified: {
                value: '15K',
                tooltip: null,
                chartData: {
                    labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                    datasets: [{
                        borderWidth: 1,
                        data: [12, 24, 30, 18, 24, 36, 24],
                        fill: false,
                        backgroundColor: COLOR.themes.light.secondary
                    }]
                },
            },
            conversion: {
                value: '35.8%',
                tooltip: [
                    {title: 'Qualified Lead', value: '10%'},
                    {title: 'Total Conversion', value: '90%'},
                ],
                chartData: {
                    labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                    datasets: [
                        {
                            borderWidth: 0,
                            data: [12, 24, 45, 18, 24, 36, 24],
                            fill: true,
                            backgroundColor: COLOR.themes.light.primary + '80'
                        },
                        {
                            borderWidth: 0,
                            data: [32, 17, 15, 18, 22, 10, 12],
                            fill: true,
                            backgroundColor: COLOR.themes.light.secondary + '80'
                        },
                    ]
                },
            },
            automation: {
                value: '40.9%',
                tooltip: [
                    {title: 'Full Automation', value: '10,2457'},
                    {title: 'Manual Intervention', value: '781'},
                ],
                chartData: {
                    labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                    datasets: [
                        {
                            borderWidth: 1,
                            data: [24, 20, 15, 25, 30, 20, 40],
                            fill: false,
                            backgroundColor: COLOR.themes.light.primary
                        },
                        {
                            borderWidth: 1,
                            data: [12, 24, 30, 18, 24, 36, 24],
                            fill: false,
                            backgroundColor: COLOR.themes.light.secondary
                        }
                    ]
                },
            },
        }
    },
    getConnectionSummary: async () => {
        return {
            connection_plan: {
                title: "Utility Type",
                chart_data: {
                    labels: ['Total Plan', 'Basic Home', 'No Frills'],
                    datasets: [{
                        borderWidth: 1,
                        data: [40, 29, 31],
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.primary,
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }
            },
            connection_type: {
                title: "Connection Type",
                chart_data: {
                    labels: ['Gas', 'Electricity', 'Dual Fuel'],
                    datasets: [{
                        borderWidth: 1,
                        data: [29, 51, 30],
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.primary,
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }

            },
            tenancy_type: {
                title: "Tenancy Type",
                chart_data: {
                    labels: ['Own', 'Basic Home', 'Rent'],
                    datasets: [{
                        borderWidth: 1,
                        data: [40, 60],
                        fill: false,
                        backgroundColor: [
                            COLOR.themes.light.secondary,
                            COLOR.themes.light.purple3,
                        ]
                    }]
                }
            },
            age_group: {},
        }
    }
}
