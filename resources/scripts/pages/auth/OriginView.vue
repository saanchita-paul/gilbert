<template>
    <v-app>
        <div fluid>
            <v-container fluid>
               <v-card class="card-section">
                   <div class="plan-title">
                       <p> Origin Go </p>
                       <hr class="orange-line">
                       <p class="plan-text">Rates guaranteed for 12 months, simplified pricing, Everyday Rewards points</p>
                   </div>
                    <hr class="devider-hr">
                   
               </v-card>
            </v-container>

        </div>
    </v-app>
</template>

<script>

export default {
    data() {
        return {
        }
    },
    mounted() {
    },

    methods: {
    },
}
</script>

<style scoped>
.card-section {
    width: 25%;
}
.plan-title {
    padding-top: 8%;
    padding-left: 6%;
}
hr.orange-line {
      border-top: 4px solid red;
      width: 20%;
      margin-bottom: 4%;
}
hr.devider-hr {
    width: 90%;
    margin-left: 6%;
    margin-right: 6%;
}
.plan-text {
    color: #505050;
    display: block;
    font-size: 14px;
    font-family: sans-serif;
    line-height: 24px;
    text-rendering: optimizeLegibility;
    text-transform: none;
    -webkit-font-smoothing: antialiased;
}

</style>
