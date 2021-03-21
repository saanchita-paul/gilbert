<template>
    <v-card class="widget mx-2">
        <div class="widget-container">
            <h2 class="title">12.6K</h2>
            <p class="mb-5">Total Leads</p>
            <div class="small-chart">
                <slot/>
            </div>
        </div>
    </v-card>
</template>

<script>
import BarChart from "@scripts/components/charts/BarChart";
import LineChart from "@scripts/components/charts/LineChart";

export default {
    name: "LeadWidget",
    components: { BarChart, LineChart }
}
</script>

<style scoped>
.widget {
    width: 100%;
    height: 227px;
    background: #fff;
    overflow: hidden;
    padding: 0px 20px 20px 20px;
}
.widget-container {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    margin: auto;
}

.title {
    font-weight: 500;
    font-size: 58px !important;
    color: #263238 !important;
    margin-bottom: 15px;
}
.small-chart {
    width: 100%;
}
</style>
