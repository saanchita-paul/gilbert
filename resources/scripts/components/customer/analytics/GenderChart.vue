<template>
    <v-card class="my-3 py-2">
        <div style="display: flex; justify-content: center">
            <canvas style="max-width: 180px!important; max-height: 180px!important;"
                    id="myChart"
                    width="100"
                    height="100"
            ></canvas>
        </div>
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
