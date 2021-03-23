<template>
    <canvas :height="null"  :id="chartId"> </canvas>
</template>

<script>
import COLOR from "@scripts/data/constants/COLOR";
// import ChartDataLabels from 'chartjs-plugin-datalabels';
export default {
    name: "BarChart",
    props: {
        data: {
            type: Object,
            required: false
        },
        chartId: {
            required: true,
            type: String,
        },
        height: {
            type: String,
            default: '400px'
        },
    },
    mounted() {
        this.renderChart();
    },
    methods: {
        renderChart() {
            const ctx = document.getElementById(this.chartId);
            const myBarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                    datasets: [{
                        maxBarThickness: 55,
                        borderWidth: 0,
                        data: [12, 24, 30, 18, 24, 36, 24],
                        fill: false,
                        backgroundColor: '#5C229A'

                    }]
                },
                options: {
                    label: {
                        display: false
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        // ticks: {
                        //     display: false
                        // },
                        xAxes: [{

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
                                drawOnChartArea: false
                            }
                        }],
                    },
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'right',
                            formatter: Math.round,
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            });

        }
    }
}
</script>

<style scoped>

</style>
