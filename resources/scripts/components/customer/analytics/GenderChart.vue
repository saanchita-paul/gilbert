<template>
    <v-card class="py-2">
        <v-row class="gender-chart-card px-5">
            <v-col md="6">
                <img src="/assets/images/GenderChart.jpg" width="100%">
            </v-col>
            <v-col md="6">
                <img src="/assets/images/info.png" width="100%">
            </v-col>
            <v-col md="12" class="py-0 px-5">
                <p class="mb-0">Gender Chart</p>
            </v-col>
        </v-row>
        <!-- <div style="display: flex; justify-content: center">
            <canvas id="myChart"
                    width="132px"
                    height="132px"
            ></canvas>
        </div> -->
    </v-card>
</template>

<script>
import Chart from "chart.js";

export default {
    name: "GenderChart",
    mounted() {
        let ctx = document.getElementById('myChart')
        const myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                    data: [20, 30],
                    backgroundColor: ['#4287f5', '#f54275']
                }],

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'Male',
                    'Female'
                ]
            },
            options: {responsive: true}
        });
    }
}
</script>

<style scoped>

</style>
