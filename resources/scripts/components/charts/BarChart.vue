<template>
    <canvas :height="height"  :id="chartId"> </canvas>
</template>

<script>
import COLOR from "@scripts/data/constants/COLOR";

export default {
    name: "BarChart",
    props: {
        data: {
            type: Object,
            required: true
        },
        chartId: {
            required: true,
            type: String,
        },
        height: {
            type: String,
            default: '100px'
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
                data: this.data,
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
