<template>
    <v-container>
        <v-row class="justify-center">
            <h3 class="page-title">
                <v-icon :color="iconColor">mdi-flash</v-icon> {{title}}
            </h3>
        </v-row>
        <v-row  class="justify-center">
            <Donut :data="data">
            </donut>
        </v-row>
        <v-row  class="justify-center d-flex mt-3">
            <div v-for="(value, index) in data.datasets[0].data" :key="index" class="mx-3 justify-center" >
              <p class="label mb-1"><v-icon :color="data.datasets[0].backgroundColor[index]" size="13">mdi-checkbox-blank-circle</v-icon></p>
                <p class="label font-weight-bold  mb-1">{{value}}</p>
                <p class="label  mb-2">{{data.labels[index]}}</p>
            </div>
        </v-row>
    </v-container>
</template>

<script>
import Donut from "@scripts/modules/sales/components/charts/Donut";

export default {
    name: "SalesSummaryChart",
    components: {
        Donut
    },
    props: {
        data: {
            type: Object,
            required: true
        },
        title: {
            required: true,
        },
        iconColor: {
            default: 'primary',
        }

    },
}
</script>

<style scoped>

.label {
    font-size: .6rem;
    padding: 0px;
    text-align: center;
}

</style>
