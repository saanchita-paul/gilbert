<template>
    <v-card class="pa-1">
        <div class=" body-bg pa-3">
            <div class="infos">
                <h5>Total messages</h5>
                <p class="mb-0 app-title-big text--primary">15,940 <span class="app-title-small"></span> </p>
            </div>
            <v-divider class="mb-2"></v-divider>
            <div class="chart" style="height: 70px">
                <canvas  height="70px" :id="chartId"></canvas>
            </div>
        </div>
    </v-card>
</template>

<script>
import Chart from 'chart.js'
export default {
    name: "InfoChartCard",
    props: ['chartId'],
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
        const allColor = ['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)'];
        let barColors = [];
        let next = 0;
        this.chart.data.map(() => {
            barColors.push(allColor[next]);
            next = next ? 0 : 1;
        })
        const myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [...this.chart.labels],
                datasets: [{
                    barPercentage: .5,
                    barThickness: 10,
                    maxBarThickness: 10,
                    minBarLength: 50,
                    data: [...this.chart.data],
                    backgroundColor: [...barColors]
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
                    yAxes: [{
                        display: false
                    }],
                }
            }
        });
    }
}
</script>

<style scoped>

</style>
