<template>
    <canvas :height="height"  :id="chartId"> </canvas>
</template>

<script>
import COLOR from "@scripts/data/constants/COLOR";

export default {
    name: "BarChart",
    props: ['chartId', 'height'],
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
                        borderWidth: 1,
                        data: [12, 24, 30, 18, 24, 36, 24],
                        // borderColor: ['#5C229A', '#5C229A', '#5C229A', '#5C229A', '#5C229A', '#5C229A', '#5C229A'],
                        fill: false,
                        backgroundColor: COLOR.themes.light.secondary,

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
                            display: false,
                            maxBarThickness: 13,
                            ticks: {
                                min: 0
                            }
                        }],
                        yAxes: [{
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
}
</script>

<style scoped>

</style>
