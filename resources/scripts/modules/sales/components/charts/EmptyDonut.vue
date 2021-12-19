<template>
    <canvas width="120" height="100" :id="chartId"></canvas>
</template>

<script>
import ApplicationService from "@scripts/services/ApplicationService";

export default {
    name: "EmptyDonut",
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            chart: null,
            chartId: ApplicationService.getRandomString(),
            newData: {
                datasets: [{
                    label: 'Empty Data',
                    data: [1, 0, 0],
                    backgroundColor: [
                    '#a3a2a0',
                    '#a3a2a0',
                    '#a3a2a0'
                    ],
                }]
            }
        }
    },

    watch: {
      data(n, o) {
          this.renderChart()
      }
    },

    mounted() {
        this.renderChart();
    },
    methods: {
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
                        ctx.fillStyle = '#a3a2a0';
                        let text = 0,
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            });
            const ctx = document.getElementById(this.chartId);
            ctx.height = 140;
            const that = this;
            this.chart = new Chart(ctx, {
                type: 'doughnut',
                data: this.newData,
                options: {
                    __id: this.chartId,
                    responsive: false,
                    cutoutPercentage: 78,
                    legend: {
                        display: false
                    },
                    plugins: {
                        datalabels: {
                            display: false,
                        },
                    },
                    tooltips: {
                        enabled: false,
                    }
                }
            });
        }
    },

}
</script>

<style scoped>

</style>
