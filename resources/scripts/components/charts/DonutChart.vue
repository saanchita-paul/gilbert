<template>
    <canvas :id="chartId"></canvas>
</template>

<script>
import COLOR from "@scripts/data/constants/COLOR";
import Chart from "chart.js";
import ApplicationService from "@scripts/services/ApplicationService";

export default {
    name: "DonutChart",
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            chartId: ApplicationService.getRandomString()
        }
    },
    mounted() {
        this.renderChart();
    },
    methods: {
        getCenterColor() {
            let dataset = this.data.datasets[0];
            return dataset.backgroundColor[dataset.data.indexOf(Math.max(...dataset.data))];
        },
        renderChart() {
            Chart.pluginService.register({
                beforeDraw: chart => {
                    if (chart.config.options.__id === this.chartId) {
                        let width = chart.chart.width,
                            height = chart.chart.height,
                            ctx = chart.chart.ctx;
                        ctx.restore();
                        let fontSize = 34;
                        ctx.font = '600 ' + fontSize + "px sans-serif ";
                        ctx.textBaseline = "middle";
                        ctx.fillStyle = this.getCenterColor();

                        let text = Math.max(...this.data.datasets[0].data) + '%',
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            });
            const ctx = document.getElementById(this.chartId);
            ctx.height = 200;
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: this.data,
                options: {
                    __id: this.chartId,
                    responsive: false,
                    cutoutPercentage: 78,
                    tooltips: {
                        mode: 'label',
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return data['datasets'][0]['data'][tooltipItem['index']] + '%';
                            }
                        }
                    },
                    // label: {
                    //     display: false
                    // },
                    legend: {
                        display: false
                    },
                    // scales: {
                    //     // ticks: {
                    //     //     display: false
                    //     // },
                    //     xAxes: [{
                    //         display: false,
                    //         ticks: {
                    //             min: 0
                    //         }
                    //     }],
                    //     yAxes: [{
                    //         display: false,
                    //         ticks: {
                    //             min: 0
                    //         }
                    //     }],
                    // },
                    plugins: {
                        datalabels: {
                            display: false,
                        }
                    }
                }
            });
        }
    },
}
</script>

<style scoped>

</style>
