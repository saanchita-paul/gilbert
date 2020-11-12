<template>
    <v-card>
        <div class="pa-2">
            <div class="infos">
                <p class="ma-0 app-title-small">{{title}}</p>
                <p class="ma-0 info-value">{{value}} <span class="app-title teal--text">+25%</span> </p>
            </div>
            <v-divider class="mb-2"></v-divider>
            <div class="chart" style="height: 60px">
                <canvas  height="60px" :id="chartId"></canvas>
            </div>
        </div>
    </v-card>
</template>

<script>
import Chart from 'chart.js'
export default {
    name: "InfoChartCard",
    props: ['chartId', 'title', 'value', 'colors'],
    data() {
        return {
            chart: {
                data: [90, 70, 62, 80, 50, 88, 80, 30, 40, 50],
                labels: ["0", "1", "2", '3', '4', '5', '6', '7', '8', '9']
            }
        }
    },
    mounted() {
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
}
</script>

<style scoped>

</style>
