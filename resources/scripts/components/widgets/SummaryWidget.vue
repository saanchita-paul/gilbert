<template>
    <v-card style="height: 354px;">
        <v-card-text>
            <p class="widget-title mb-4">{{ title }}</p>

        <div class="chart-area">
            <DonutChart :data="data"  chart-id="connectionType"/>
        </div>
            <div class="label-area">
                <div v-for="(value, index) in data.datasets[0].data" :key="index" class="label-items">
                    <span><v-icon :color="data.datasets[0].backgroundColor[index]" size="13">mdi-checkbox-blank-circle</v-icon></span>
                    <p class="label">{{data.labels[index]}}</p>
                    <p class="label">{{value}}%</p>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import DonutChart from "@scripts/components/charts/DonutChart";

export default {
    name: "SummaryWidget",
    props: {
        title: {
            type: String,
            required: true,
        },
        data: {
            type: Object
        }
    },
    components: {DonutChart},
    data() {
        return {}
    }
}
</script>

<style scoped>
.chart-area {
    height: 188px;
    margin-bottom: 25px;
    width: 100%;  display: flex; justify-content: center; align-content: center
}

.label-area {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    align-content: center;
}

.label-items {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.label {
    font-size: .6rem;
    margin: 0px 0px -4px 0px;
    padding: 0px;
}
</style>
