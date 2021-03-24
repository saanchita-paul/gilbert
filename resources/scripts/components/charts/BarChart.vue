<template>
    <canvas :height="height"   :id="chartId"> </canvas>
</template>

<script>
import COLOR from "@scripts/data/constants/COLOR";
import ChartDataLabels from 'chartjs-plugin-datalabels';
import merge from "lodash-es/merge";

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
        options: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            defaultOptions: {
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
                },
                plugins: {
                    datalabels: {
                        display: false,
                    }
                }
            }
        }
    },
    mounted() {
        this.renderChart();
    },
    methods: {
        renderChart() {
            setTimeout(() => {
                const ctx = document.getElementById(this.chartId);
                const myBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: this.data,
                    options: merge(this.options || this.defaultOptions)
                });
            }, 400)
        }
    }
}
</script>

<style scoped>

</style>
