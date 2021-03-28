<template>
    <canvas height="273px" :id="chartId"> </canvas>
</template>

<script>
import COLOR from "@scripts/data/constants/COLOR";
import ChartDataLabels from 'chartjs-plugin-datalabels';
import ApplicationService from "@scripts/services/ApplicationService";
export default {
    name: "BarHorizontalChart",
    props: {
        height: {
            type: String,
            default: '280px'
        },
        data: {
            type: Object,
            required: false
        },
    },
    data() {
        return {
            chartId: ApplicationService.getRandomString(),
        }
    },
    mounted() {
        this.renderChart();
    },
    methods: {
        calculateColors() {
            const data = this.data.datasets[0].data;
            const colors = [];
            data.map(value => colors.push(`rgba(84,46,137,${value / Math.max(...data)})`))
            this.data.datasets[0].backgroundColor = [...colors];
        },
        renderChart() {
            const ctx = document.getElementById(this.chartId);
            this.calculateColors();
            const myBarChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: this.data,
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    layout: {
                        padding: {
                            right: 50,
                        }
                    },
                    scales: {
                        // ticks: {
                        //     display: false
                        // },
                        xAxes: [{
                            display: false,

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
                            clamp: false,
                            align: 'end',
                            formatter: (value, context) => Math.round(value) + '%',
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
