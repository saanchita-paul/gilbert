<template>
    <v-card style="height: 300px; overflow: auto !important;">
        <v-card-text style="">
            <h4 class="ttitle">Top Towns/cities</h4>
            <div v-for="city in cities" class="my-2">
                <p class="pa-0 ma-0">Sydney, Australia</p>
                <div class="city-amount mt-1">
                    <div class="fill"></div>
                    <div class="empty"></div>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: "UtilityUsageByCityGraph",
    props: ['cities'],
}
</script>

<style scoped>
.ttitle {
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
