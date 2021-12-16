<template>
    <canvas width="120" height="100" :id="chartId"></canvas>
</template>

<script>
import ApplicationService from "@scripts/services/ApplicationService";
import {isArray} from "lodash-es";

export default {
    name: "Donut",
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            chart: null,
            chartId: ApplicationService.getRandomString()
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
                        let text = that.data.total,
                            textX = Math.round((width - ctx.measureText(text).width) / 2),
                            textY = height / 2;

                        ctx.fillText(text, textX, textY);
                        ctx.save();
                    }
                }
            });
            const ctx = document.getElementById(this.chartId);
            ctx.height = 200;
            const that = this;
            this.chart = new Chart(ctx, {
                type: 'doughnut',
                data: this.data,
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
                        custom: function (tooltipModel) {
                            if(!that.data.toolTips) {
                                return;
                            }

                            let dataIndex = null;
                            that.data.toolTips
                            let dataToShowInTooltip;
                            if (isArray(tooltipModel.dataPoints)) {
                                dataIndex = tooltipModel.dataPoints[0].index;
                            }
                            var tooltipEl = document.getElementById('chartjs-tooltip');
                            if (!tooltipEl) {
                                tooltipEl = document.createElement('div');
                                tooltipEl.id = 'chartjs-tooltip';
                                tooltipEl.innerHTML = '<table></table>';
                                document.body.appendChild(tooltipEl);
                            }

                            // Hide if no tooltip
                            if (tooltipModel.opacity === 0) {
                                tooltipEl.style.opacity = 0;
                                return;
                            }

                            // Set caret Position
                            tooltipEl.classList.remove('above', 'below', 'no-transform');
                            if (tooltipModel.yAlign) {
                                tooltipEl.classList.add(tooltipModel.yAlign);
                            } else {
                                tooltipEl.classList.add('no-transform');
                            }

                            // Set Text
                            if (tooltipModel.body) {
                                var titleLines = tooltipModel.title || [];
                                var innerHtml = '<thead>';

                                titleLines.forEach(function (title) {
                                    innerHtml += '<tr><th>' + title + '</th></tr>';
                                });
                                innerHtml += '</thead><tbody>';

                                let tooltipData = that.data.toolTips;

                                tooltipData[dataIndex].forEach(function (body, i) {
                                });
                                innerHtml += '</tbody>';
                                var tableRoot = tooltipEl.querySelector('table');


                                var position = this._chart.canvas.getBoundingClientRect();

                                // Display, position, and set styles for font
                                tooltipEl.style.opacity = 1;
                                tooltipEl.style.position = 'absolute';
                                tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + 'px';
                                tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + 'px';
                                tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
                                tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
                                tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
                                tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
                                tooltipEl.style.pointerEvents = 'none';
                                tooltipEl.style.width = '169px';
                                tooltipEl.style.borderRadius = '16px';
                                tooltipEl.style.padding = '16px';
                                tooltipEl.style.background = 'white';
                                tooltipEl.style.boxShadow = '0px 3px 1px -2px rgb(0 0 0 / 20%), 0px 2px 2px 0px rgb(0 0 0 / 14%), 0px 1px 5px 0px rgb(0 0 0 / 12%)';


                                const title = that.data.labels[dataIndex];
                                const tooltipBodyData = that.data.toolTips[dataIndex];
                                let tooltipBody = '';
                                tooltipBodyData.forEach((item) => {
                                    tooltipBody += `
                                                    <div style="display:flex; margin-top: 10px;">
                                                        <p style="font-weight:bold; width: 40px;">
                                                            ${item.value}
                                                        </p>
                                                        <p style="font-weight:normal; color: #7E8A8F; ">
                                                            ${item.key}
                                                        </p>
                                                    </div>
                                                    `
                                });
                                tableRoot.innerHTML =
                                    `
                                <div style="background: white;">
                                    <p style="font-weight:bold;">${title}</p>

                                    <div>

                                        ${tooltipBody}

                                    </div>

                                </div>
                                `;
                            }

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
