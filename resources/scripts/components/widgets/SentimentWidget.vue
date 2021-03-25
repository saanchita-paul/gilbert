<template>
    <v-card>
        <v-card-text>
            <p class="widget-title">Lead Sentiment Status</p>
            <v-row>
                <v-col md="4" class="py-0">
                    <div class="label-area">
                        <div class="label"
                             v-for="(item, i) in sentimentData"
                             :key="i"
                        >
                            <p class="sentiment-value">{{ item.value }}%</p>
                            <p class="sentiment-title" :style="{color: item.color + '!important'}">
                                {{ item.sentiment_text | CAPITALIZE }}
                            </p>
                        </div>
                    </div>
                </v-col>
                <v-col md="8" class="py-0">
                    <div class="chart-area">
                        <DonutChart :data="chart_data"/>
                    </div>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
import DonutChart from "@scripts/components/charts/DonutChart";
import {capitalize} from "lodash-es";
import {mapSentimentColor} from "@scripts/data/SentimentColor";

export default {
    name: "SentimentWidget",
    props: ['sentiment', 'chart_data'],
    components: {DonutChart},
    data() {
        return {
            // sentiment: [
            //     {sentiment_text: "NEGATIVE", value: 10},
            //     {sentiment_text: "POSITIVE", value: 30},
            //     {sentiment_text: "NEUTRAL", value: 60},
            // ],
            // chart_data: {
            //     labels: ['Positive', 'Neutral', 'Negative'],
            //     datasets: [{
            //         borderWidth: 1,
            //         data: [30, 60, 10],
            //         fill: false,
            //         backgroundColor: [
            //             '#4CAF50',
            //             '#BDBDBD',
            //             '#E91E63',
            //         ]
            //     }]
            // }
        }
    },
    computed: {
        sentimentData() {
            let sort = this.sentiment.sort((a, b) => b.value - a.value)
            sort.shift();
            return sort.map(item => ({...item, ...{color: mapSentimentColor(item.sentiment_text)}}) )
        }
    },
    filters: {
        CAPITALIZE(value){
            return capitalize(value)
        }
    }
}
</script>

<style scoped>
.chart-area {
    width: 100%;
    display: flex;
    justify-content: center;
    align-content: center;
}

.label-area {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: flex-start;
    align-content: center;
}

.label {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.sentiment-title {
    font-size: 12px;
    color: #828282;
}

.sentiment-value {
    margin: 0px !important;
    color: #263238;
    font-size: 24px;
    font-weight: 700;
}
</style>
