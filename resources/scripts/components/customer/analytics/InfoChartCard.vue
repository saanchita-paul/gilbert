<template>
    <v-card class="pa-1">
        <div class=" body-bg pa-3">
            <div class="infos">
                <h5>Total messages</h5>
                <p class="mb-0 app-title-big text--primary">15,940 <span class="app-title-small"></span> </p>
            </div>
            <v-divider></v-divider>
            <div class="chart" style="height: 60px">
                <canvas  height="50px" :id="chartId"></canvas>
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
        }
    },
    mounted() {
        const ctx = document.getElementById(this.chartId);
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    "Foo",
                    "Bar",
                    "Baz"
                ],
                datasets: [{
                    barPercentage: 1,
                    barThickness: 10,
                    maxBarThickness: 10,
                    minBarLength: 2,
                    data: [90, 70, 62, 80, 50, 88, 80],
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
