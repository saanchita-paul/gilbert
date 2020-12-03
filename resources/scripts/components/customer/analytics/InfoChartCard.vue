<template>
    <v-card>
        <div class="pa-2">
            <div class="infos">
                <p class="ma-0 app-title-small">{{title}}</p>
                <p class="ma-0 info-value">{{value}} <span class="app-title teal--text">+{{percentage}}%</span> </p>
            </div>
            <v-divider class="mb-2"></v-divider>
            <div class="chart" style="height: 60px">
                <canvas  height="60px" :id="chartId"></canvas>
            </div>
        </div>
    </v-card>
</template>

<script>
import Chart from 'chart.js';
import isFinite from 'lodash-es/isFinite';
export default {
    name: "InfoChartCard",
    props: ['chartId', 'title', 'value', 'prevValue', 'colors', 'chartData'],
    data() {
        return {
            chart: {
                data: this.chartData.data,
                labels: this.chartData.labels
            }
        }
    },
    computed: {
        percentage() {
            if (isFinite(this.prevValue) || this.prevValue === 0 || this.value === 0) {
                return 0;
            } else {
                return Math.round(((this.value - this.prevValue) / this.prevValue) * 100);
            }
        }
    },
    watch: {
        chartData: {
            handler: function (newVal) {
                const { data = [], labels = [] } = newVal || {};
                this.chart = { data, labels };
                this.loadChart();
            },
            deep: true
        },
    },
    methods: {
        loadChart() {
            const ctx = document.getElementById(this.chartId);
            // const allColor = ['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)'];
            let barColors = [];
            let next = 0;
            this.chart.data.map(() => {
                barColors.push(this.colors[next]);
                next = next ? 0 : 1;
            })
            const myBarChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [...this.chart.labels],
                    datasets: [{
                        borderWidth: 1,
                        data: [...this.chart.data],
                        borderColor: [...barColors],
                        fill: false
                        // backgroundColor: [...barColors]
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
                        xAxes: [{
                            display: false,
                            ticks: {
                                min: 0
                            }
                        }],
                    }
                }
            });
        }
    },
    mounted() {
        this.loadChart();
    }
}
</script>

<style scoped>

</style>
