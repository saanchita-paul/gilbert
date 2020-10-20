<template>
    <v-card>
        <v-card-title class="card-bg-color app-title">
<!--         <v-icon size="28" color="white">mdi-emoticon</v-icon>-->
            Engagement satisfaction
<!--            <span class="ml-2 title">Emotion Meter</span>-->
        </v-card-title>
        <v-divider></v-divider>
        <div class="emotions-container">
            <div class="emotion">
                <v-icon color="green" size="140">mdi-emoticon-happy-outline</v-icon>
                <p class="app-title primary--text">{{emotions.positive}}%</p>
            </div>
            <div class="emotion">
                <v-icon color="yellow" size="140">mdi-emoticon-neutral-outline</v-icon>
                <p class="app-title primary--text">{{emotions.neutral}}%</p>
            </div>
            <div class="emotion">
                <v-icon color="red" size="140">mdi-emoticon-sad-outline</v-icon>
                <p class="app-title primary--text">{{emotions.negative}}%</p>
            </div>
        </div>
        <span>
<!--            <v-list dense class="px-4">-->
<!--                    <v-list-item>-->
<!--                        <v-list-item-avatar><v-icon color="red">mdi-emoticon-angry</v-icon></v-list-item-avatar>-->
<!--                        <v-list-item-content>-->
<!--                            <v-row>-->
<!--                                <v-col md="">-->
<!--                                    <v-list-item-title>Negative</v-list-item-title>-->
<!--                                </v-col>-->
<!--                                <v-col md="2">-->
<!--                                    <v-list-item-title>{{this.emotions.negative}}%</v-list-item-title>-->
<!--                                </v-col>-->
<!--                                <v-col md="7">-->
<!--                                    <div class="meter text-center" :style="negativeStyles">-->
<!--                                    </div>-->
<!--                                </v-col>-->
<!--                            </v-row>-->
<!--                        </v-list-item-content>-->
<!--                    </v-list-item>-->
<!--                <v-divider></v-divider>-->
<!--                    <v-list-item>-->
<!--                        <v-list-item-avatar color="gray"><v-icon>mdi-emoticon-neutral</v-icon></v-list-item-avatar>-->
<!--                        <v-list-item-content>-->
<!--                            <v-row>-->
<!--                                <v-col md="3">-->
<!--                                    <v-list-item-title>Neutral</v-list-item-title>-->
<!--                                </v-col>-->
<!--                                <v-col md="2">-->
<!--                                    <v-list-item-title>{{this.emotions.neutral}}%</v-list-item-title>-->
<!--                                </v-col>-->
<!--                                <v-col md="7">-->
<!--                                    <div class="meter text-center" :style="neutralStyles">-->
<!--                                    </div>-->
<!--                                </v-col>-->
<!--                            </v-row>-->
<!--                        </v-list-item-content>-->
<!--                    </v-list-item>-->
<!--                <v-divider></v-divider>-->
<!--                <v-list-item>-->
<!--                    <v-list-item-avatar><v-icon color="green">mdi-emoticon-excited</v-icon></v-list-item-avatar>-->
<!--                    <v-list-item-content>-->
<!--                            <v-row>-->
<!--                                <v-col md="3">-->
<!--                                    <v-list-item-title>Positive</v-list-item-title>-->
<!--                                </v-col>-->
<!--                                <v-col md="2">-->
<!--                                    <v-list-item-title>{{this.emotions.positive}}%</v-list-item-title>-->
<!--                                </v-col>-->
<!--                                <v-col md="7">-->
<!--                                    <div class="meter text-center" :style="positiveStyles">-->
<!--                                    </div>-->
<!--                                </v-col>-->
<!--                            </v-row>-->
<!--                        </v-list-item-content>-->
<!--                    </v-list-item>-->
<!--            </v-list>-->
        </span>
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
.emotions-container {
    height: 186px;
    display: flex;
    width: 100%;
    justify-content: space-around;
    align-items: center
}
.emotion {
    display: flex;
    flex-direction: column;
    align-items: center
}
.meter {
    height: 5px;
    border-radius: 20px;
    transition: width .7s
}
</style>
