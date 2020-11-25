<template>
    <v-card style="height: 300px; overflow: auto !important;">
        <v-card-text style="">
            <h4 class="title">Top Towns/cities</h4>
            <div v-if="!Array.isArray(cities) || cities.length === 0" class="text-h5">Not enough user data to display top cities and towns.</div>
            <div v-else v-for="city in cities" class="my-2">
                <div class="d-flex flex-row pa-0 ma-0">
                  <div class="pa-0 ma-0">{{getCityLabel(city)}}</div>
                  <div class="pa-0 ml-auto">{{getCityPercentage(city)}}</div>
                </div>
                <div class="city-amount mt-1">
<!--                    <div class="fill"></div>-->
<!--                    <div class="empty"></div>-->
                  <div :style="getFillStyle(city)"></div>
                  <div :style="getEmptyStyle(city)"></div>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: "UtilityUsageByCityGraph",
    props: ['cities'],
    methods: {
      getCityLabel(city) {
        const cityName = city.city_name || '';
        const state = city.state || '';
        const country = city.country || '';
        return `${cityName}, ${state}, ${country}`;
      },
      getCityPercentage(city) {
        const { customer_percentage = 0 } = city;
        return `${customer_percentage}%`;
      },
      getFillStyle(city) {
        const { customer_percentage = 0 } = city;
        const fillPercentage = `${customer_percentage}%`;
        return { height: '7px', width: fillPercentage, backgroundColor: '#0892cf' };
      },
      getEmptyStyle(city) {
        const { customer_percentage = 0 } = city;
        const fillPercentage = `${100 - customer_percentage}%`;
        return { height: '7px', width: fillPercentage, backgroundColor: '#b1b1b1' };
      }
    }
}
</script>

<style scoped>
.title {
    color: black;
    font-size: 15px !important;
}
.city-amount {
    display: flex;
    flex-direction: row;
}
.fill {
    height: 7px;
    width: 20%;
    background-color: #0892cf
}
.empty {
    height: 7px;
    width: 80%;
    background-color: #b1b1b1;
}
</style>
