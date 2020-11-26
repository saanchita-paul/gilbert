<template>
    <v-card style="height: 350px" class="px-2" v-if="_id && chartData">
        <v-card-text>
            <div style="height: 150px">

                <div class="">
                    <div class="d-flex flex-row">
                        <h3 class="title">{{title}}</h3>
                        <v-dialog
                            v-model="dialog"
                            persistent
                            max-width="290"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon class="ml-auto" v-bind="attrs" v-on="on">
                                    <v-icon>mdi-tune</v-icon>
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title>
                                    <div class="d-flex flex-row" style="width: 100%">
                                        <div class="font-italic">Filter By</div>
                                        <div class="flex-grow-1 flex-shrink-0 d-flex flex-row-reverse">
                                            <v-btn icon @click="dialog = false">
                                                <v-icon>mdi-close-circle</v-icon>
                                            </v-btn>
                                        </div>
                                    </div>
                                </v-card-title>

                                <v-card-text>
                                    Lorem ipsum
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="primary"
                                        text
                                        @click="dialog = false"
                                    >
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>

                        </v-dialog>
                    </div>
                    <small>Camberwell, 3152</small>
                </div>
                <v-row class="mt-1">
                    <v-col class="my-0 py-0" v-for="(color, index ) in chartData.colors" :key="index" sm="6">
                        <div class="chart-title">
                            <div class="labelColor" :style="{backgroundColor: color}"></div>
                            <div>{{chartData.labels[index]}}</div>
                        </div>
                    </v-col>
                </v-row>
            </div>
            <div class="utility-chart-container">
                <div>
                    <canvas :id="_id"></canvas>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import Chart from "chart.js";

export default {
    name: "EnergyUsageChart",
    props: ['_id', 'chartData', 'title', 'filter'],
    data() {
        return {
            // chartData: {
            //     colors: ['red', 'orange', 'green', 'black'],
            //     data: [40, 25, 35, 0],
            //     labels: ['High', 'Medium', 'Low', 'Not sure']
            // }
            dialog: false,
        }
    },
    mounted() {
        this.drawChart();
    },
    watch: {
        chartData: {
            handler: function () {
                this.drawChart();
            },
            deep: true
        },
    },
    methods: {
        drawChart() {
            Chart.pluginService.register({
                beforeDraw: chart => {
                    if (chart.config.options._custom?._id === this._id) {
                        let width = chart.chart.width,
                            height = chart.chart.height,
                            ctx = chart.chart.ctx;
                        ctx.restore();
                        let fontSize = (height / 114).toFixed(2);
                        ctx.font = fontSize + "em sans-serif";
                        ctx.textBaseline = "middle";
                        ctx.fillStyle = this.chartData.colors[this.chartData.data.indexOf(Math.max(...this.chartData.data))];

                        let text = Math.max(...this.chartData.data) + '%',
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            });

            let el = document.getElementById(this._id)

            const myPieChart = new Chart(el, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: this.chartData.data,
                        backgroundColor: this.chartData.colors
                    }],

                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: this.chartData.labels
                },
                options: {
                    _custom: { _id: this._id},
                    responsive: true,
                    legend: {
                        display: false,
                    }
                }
            });
        }
    }
}
</script>

<style scoped>
.labelColor {
    width: 18px;
    height: 8px;
    border-radius: 8px;
    margin-right: 5px;
}
.chart-title {
    display: flex; flex-direction: row; align-items: center
}

.title {
    font-weight: 600;
    font-size: 18px;
}
</style>
