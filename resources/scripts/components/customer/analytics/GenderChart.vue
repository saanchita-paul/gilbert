<template>
    <v-card>
        <v-card-title  class="card-bg-color app-title">
            Gender
        </v-card-title>
        <v-divider/>
        <div style="display: flex; justify-content: center; align-items: center; width: 100%; height: 186px">
            <div>
                <canvas id="myChart"></canvas>
            </div>
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
            options: {
                legend: {
                    position: 'right',
                }
            }
        });
    }
}
</script>

<style scoped>

</style>
