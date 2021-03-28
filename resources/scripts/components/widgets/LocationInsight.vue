<template>
    <v-card v-if="isLoaded">
        <v-card-text>
            <v-row>
                <v-col md="6" class="my-0 pt-0">
                    <p class="widget-title mb-2">Location Insight</p>
                    <div class="au-states pl-2">
                        <p class="state" v-for="v in data">
                            <v-icon
                                :style="{color: `rgba(86,48,139,${v.opacity})`}"
                                class="pt-0"
                                size="12"
                            >mdi-rectangle</v-icon>
                            <span class="ml-1">{{v.text}} {{v.value}}%</span>
                        </p>
                    </div>
                </v-col>
                <v-col md="6" class="pa-0">
                    <div id="location_insight" style="width: 100%; margin: auto"></div>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
import {mapStateKey} from "@scripts/data/AustraliaStates";

export default {
    name: "LocationInsight",
    props: ['data'],
    data() {
        return {
            isLoaded: false,
           
        }
    },
    computed: {
        map() {
            return window.simplemaps_australiamap;
        },
        mapData() {
            const max = Math.max(...this.data.map(item => item.value))
            return this.data.map(item => ({...item, ...{opacity: item.value / max}}))
        },
    },
    mounted() {
        this.isLoaded = true;
        setTimeout(() => {
            this.map.load();
            this.updateMapColor()
        }, 400)
    },
    methods: {
        updateMapColor() {
            this.mapData.map(item => {
                const key = mapStateKey(item.text);
                if (this.map.mapdata.state_specific[key]) {
                    this.map.mapdata.state_specific[key].opacity = item.opacity;
                    this.map.mapdata.state_specific[key].color = '#5C229A';
                }
            })
            this.map.refresh();
        }
    }
}
</script>

<style scoped>
.state {
    font-size: 12px;
    margin: 0px;
    padding: 0px;
}
</style>
