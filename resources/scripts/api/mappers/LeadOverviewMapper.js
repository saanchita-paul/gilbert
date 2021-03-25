import COLOR from "@scripts/data/constants/COLOR";

export default {
    mapLeadOverview: (data) => {
        function mapTotalLead(data) {
            let chartLabels = [];
            let chartData = [];
            data.charts.forEach(element => {
                chartLabels.push(element.date);
                chartData.push(element.total);
            });
    
            return {
                value: data.total,
                tooltip: null,
                chartData: {
                    labels: chartLabels,
                    datasets: [{
                        borderWidth: 1,
                        data: chartData,
                        fill: false,
                        maxBarThickness: 13,
                        backgroundColor: COLOR.themes.light.secondary,
                    }]
                }
            };
        }

        function mapQualifiedLead(data) {
            let chartLabels = [];
            let chartData = [];
            data.charts.forEach(element => {
                chartLabels.push(element.date);
                chartData.push(element.total);
            });
    
            return {
                value: data.total,
                tooltip: null,
                chartData: {
                    labels: chartLabels,
                    datasets: [{
                        borderWidth: 1,
                        data: chartData,
                        fill: false,
                        backgroundColor: COLOR.themes.light.secondary
                    }]
                },
            };
        }

        function mapTotalEnergyConnection(data) {
            let chartLabels = [];
            let chartData = [];
            data.charts.forEach(element => {
                chartLabels.push(element.date);
                chartData.push(element.total);
            });
    
            return {
                value: data.total,
                tooltip: null,
                chartData: {
                    labels: chartLabels,
                    datasets: [{
                        borderWidth: 1,
                        data: chartData,
                        fill: false,
                        backgroundColor: COLOR.themes.light.secondary
                    }]
                },
            };
        }

        function mapConversionRate(data) {
            let chartLabels = [];
            let chartData1 = [];
            let chartData2 = [];
            data.charts.forEach(element => {
                chartLabels.push(element.date);
                chartData1.push(element.total_lead);
                chartData2.push(element.total_conversion);
            });
    
            return {
                value: data.total,
                tooltip: [
                    {title: 'Qualified Lead', value: data.qualified_lead},
                    {title: 'Total Conversion', value: data.total_conversion},
                ],
                chartData: {
                    labels: chartLabels,
                    datasets: [
                        {
                            borderWidth: 0,
                            data: chartData1,
                            fill: true,
                            backgroundColor: COLOR.themes.light.primary + '80'
                        },
                        {
                            borderWidth: 0,
                            data: chartData2,
                            fill: true,
                            backgroundColor: COLOR.themes.light.secondary + '80'
                        },
                    ]
                },
            };
        }

        function mapAutomationRate (data) {
            let chartLabels = [];
            let chartData1 = [];
            let chartData2 = [];
            data.charts.forEach(element => {
                chartLabels.push(element.date);
                chartData1.push(element.full_automation);
                chartData2.push(element.manual_intervention);
            });
    
            return {
                value: data.total,
                tooltip: [
                    {title: 'Full Automation', value: data.full_automation},
                    {title: 'Manual Intervention', value: data.manual_intervention},
                ],
                chartData: {
                    labels: chartLabels,
                    datasets: [
                        {
                            borderWidth: 1,
                            data: chartData1,
                            fill: false,
                            backgroundColor: COLOR.themes.light.primary
                        },
                        {
                            borderWidth: 1,
                            data: chartData2,
                            fill: false,
                            backgroundColor: COLOR.themes.light.secondary
                        }
                    ]
                },
            };
    
        }

        return {
            lead: mapTotalLead(data.total_lead),
            qualified: mapQualifiedLead(data.qualified_lead),
            energy: mapTotalEnergyConnection(data.total_energy_connection),
            conversion: mapConversionRate(data.conversion_rate),
            automation: mapAutomationRate(data.automation_rate),
        };
    },
}
