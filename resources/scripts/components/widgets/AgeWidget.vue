<template>
    <v-card style="height: 354px;">
        <v-card-text>
            <p class="widget-title mb-4">Age Group</p>

        <div class="age-chart">
            <BarHorizontalChart :data="data"/>
        </div>
        </v-card-text>
    </v-card>
</template>

<script>
import BarHorizontalChart from "@scripts/components/charts/BarHorizontalChart";

export default {
    name: "AgeWidget",
    props: {
        data: {
            type: Object
        }
    },
    components: { BarHorizontalChart },
    data() {
        return {
            chartData: {
                labels: ['18-24', '25-34', '35-44', '45-54', '55-64', '65+'],
                datasets: [{
                    maxBarThickness: 28,
                    borderWidth: 1,
                    data: [10, 20, 34, 15, 5, 16],
                }]
            }
        }
    }
}
</script>

<style scoped>
.age-chart {
    width: 100%;
}
</style>
