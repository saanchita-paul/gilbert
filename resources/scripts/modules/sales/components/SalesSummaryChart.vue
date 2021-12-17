<template>
    <v-container>
        <v-row class="justify-center">
            <h3 class="sub-title" v-if="title !== 'Water'">
                <v-icon v-if="title === 'Power'" color="yellow">mdi-flash</v-icon>
                <v-icon v-if="title === 'Gas'" color="red">mdi-fire</v-icon>
                {{ title }}
            </h3>
        </v-row>
        <v-row class="justify-center my-2">
            <Donut v-if="isDataExists(data)" :data="data"/>
            <EmptyDonut v-else :data="data"/>
        </v-row>
        <v-row v-if="title !== 'Water'" class="justify-center d-flex mt-1">
            <div v-for="(value, index) in data.datasets[0].data" :key="index" class="mx-3 justify-center">
                <p class="label mb-1">
                    <v-icon :color="data.datasets[0].backgroundColor[index]" size="13">mdi-checkbox-blank-circle
                    </v-icon>
                </p>
                <p class="label font-weight-bold  mb-1">{{ value }}</p>
                <p class="label  mb-2">{{ data.labels[index] }}</p>
            </div>
        </v-row>
    </v-container>
</template>

<script>
import Donut from "@scripts/modules/sales/components/charts/Donut";
import EmptyDonut from "@scripts/modules/sales/components/charts/EmptyDonut";

export default {
    name: "SalesSummaryChart",
    components: {
        Donut,
        EmptyDonut
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
    methods: {
        isDataExists(data) {
            return data.datasets[0].data.some(value => value !== 0);
        }
    }
}
</script>

<style scoped>

.label {
    font-size: 14px;
    padding: 0px;
    text-align: center;
}

</style>
