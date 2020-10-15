<template>
    <v-card>
        <v-card-title class="card-bg-color white--text">
         <v-icon size="28" color="white">mdi-emoticon</v-icon>
            <span class="ml-2">EMOTION METER</span>
        </v-card-title>
        <v-divider></v-divider>
            <v-list dense>
                    <v-list-item>
                        <v-list-item-avatar><v-icon color="red">mdi-emoticon-angry</v-icon></v-list-item-avatar>
                        <v-list-item-content>
                            <v-row>
                                <v-col md="">
                                    <v-list-item-title>Negative</v-list-item-title>
                                </v-col>
                                <v-col md="2">
                                    <v-list-item-title>{{this.emotions.negative}}%</v-list-item-title>
                                </v-col>
                                <v-col md="7">
                                    <div class="meter text-center" :style="negativeStyles">
                                    </div>
                                </v-col>
                            </v-row>
                        </v-list-item-content>
                    </v-list-item>
                <v-divider></v-divider>
                    <v-list-item>
                        <v-list-item-avatar color="gray"><v-icon>mdi-emoticon-neutral</v-icon></v-list-item-avatar>
                        <v-list-item-content>
                            <v-row>
                                <v-col md="3">
                                    <v-list-item-title>Neutral</v-list-item-title>
                                </v-col>
                                <v-col md="2">
                                    <v-list-item-title>{{this.emotions.neutral}}%</v-list-item-title>
                                </v-col>
                                <v-col md="7">
                                    <div class="meter text-center" :style="neutralStyles">
                                    </div>
                                </v-col>
                            </v-row>
                        </v-list-item-content>
                    </v-list-item>
                <v-divider></v-divider>
                <v-list-item>
                    <v-list-item-avatar><v-icon color="green">mdi-emoticon-excited</v-icon></v-list-item-avatar>
                    <v-list-item-content>
                            <v-row>
                                <v-col md="3">
                                    <v-list-item-title>Positive</v-list-item-title>
                                </v-col>
                                <v-col md="2">
                                    <v-list-item-title>{{this.emotions.positive}}%</v-list-item-title>
                                </v-col>
                                <v-col md="7">
                                    <div class="meter text-center" :style="positiveStyles">
                                    </div>
                                </v-col>
                            </v-row>
                        </v-list-item-content>
                    </v-list-item>
            </v-list>
    </v-card>
</template>

<script>
export default {
    name: "EmotionMeter",
    data() {
        return {
            load: false,
            emotions: {
                negative: 5,
                neutral: 10,
                positive: 85,
            }
        }
    },
    computed: {
        negativeStyles() {
            return this.load ?  {backgroundColor: 'red', width: this.emotions.negative + '%'} : {width: '0%'}
        },
        neutralStyles() {
            return this.load ?  {backgroundColor: 'gray', width: this.emotions.neutral + '%'} : {width: '0%'}
        },
        positiveStyles() {
            return this.load ?  {backgroundColor: 'green', width: this.emotions.positive + '%'} : {width: '0%'}
        },
    },
    mounted() {
        setTimeout(() => this.load = true,1)
    }
}
</script>

<style scoped>
.meter {
    height: 5px;
    border-radius: 20px;
    transition: width .7s
}
</style>
