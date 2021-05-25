<template>
    <v-card class="" style="overflow: hidden; height: 100%;">
        <div class="connection-container">
            <div class="max-width" style="margin-top: -10px; margin-bottom: 20px">
                <p class="widget-title">Lead to Connection</p>
            </div>
            <BarChart
                height="270px"
                style="height: 100%; padding-bottom: 20px"
                :data="chartData"
                chart-id="leadToConnection"
                :options="options"
            />
        </div>
    </v-card>
</template>

<script>
import BarChart from "@scripts/components/charts/BarChart";
import COLOR from "@scripts/data/constants/COLOR";

export default {
    name: "ConnectionLeadWidget",
    components: { BarChart },
    props: {
        chartData: {required: true}
    },
    data() {
        return {
            chartDataa: {
                labels: [
                    ['Lead by', 'Channels'],
                    ['Service', 'Connection'],
                    ['Connection', 'Type'],
                    ['Connection', 'Submitted'],
                    ['Connection', 'Accepted']
                ],
                datasets: [{
                    borderWidth: 1,
                    data: [12, 30, 18, 36, 24],
                    fill: false,
                    maxBarThickness: 55,
                    backgroundColor: COLOR.themes.light.primary,
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    callbacks: {
                        title: function (itemObj) {
                            const label = itemObj?.[0]?.label;
                            return label ? label.replace(',', ' ') : '';
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 40,
                    }
                },
                legend: {
                    display: false
                },
                scales: {

                    // ticks: {
                    //     display: false
                    // },
                    xAxes: [{
                        // maxBarThickness: 20,
                        ticks: {
                            min: 0
                        },
                        gridLines: {
                            drawOnChartArea: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0
                        },
                        gridLines: {
                        }
                    }],
                },
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    }
}
</script>

<style scoped>
.connection-container {
    width: 100%;
    height: 420px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center
}
</style>
