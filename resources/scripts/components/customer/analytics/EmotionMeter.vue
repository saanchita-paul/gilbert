<template>
    <v-card class="px-3">
        <v-card-text>
            <v-row>
                <v-col md="4">
                    <p  class="info-value  primary--text" >
                        {{emotions.positive}}%
                        <span class="app-title primary--text-small">positive</span>
                    </p>
                    <p class="app-title-small primary--text" v-text="'Sentiment status'"></p>
                </v-col>
                <v-col md="8">
                    <div class="emotions-container mt-1">
                        <div class="emo app-title-small  white--text" :style="negativeStyles">NEGATIVE</div>
                        <div class="emo app-title-small white--text" :style="neutralStyles">NEUTRAL</div>
                        <div class="emo app-title-small white--text" :style="positiveStyles">POSITIVE</div>
                    </div>
                </v-col>
            </v-row>
        </v-card-text>
        <span>
<!--                    <v-card-title class="card-bg-color app-title">-->
<!--&lt;!&ndash;         <v-icon size="28" color="white">mdi-emoticon</v-icon>&ndash;&gt;-->
<!--            Engagement satisfaction-->
<!--                        &lt;!&ndash;            <span class="ml-2 title">Emotion Meter</span>&ndash;&gt;-->
<!--        </v-card-title>-->
<!--        <v-divider></v-divider>-->
<!--        <div class="emotions-container">-->
<!--            <div class="emotion">-->
<!--                <v-icon color="green" size="140">mdi-emoticon-happy-outline</v-icon>-->
<!--                <p class="app-title primary&#45;&#45;text">{{emotions.positive}}%</p>-->
<!--            </div>-->
<!--            <div class="emotion">-->
<!--                <v-icon color="yellow" size="140">mdi-emoticon-neutral-outline</v-icon>-->
<!--                <p class="app-title primary&#45;&#45;text">{{emotions.neutral}}%</p>-->
<!--            </div>-->
<!--            <div class="emotion">-->
<!--                <v-icon color="red" size="140">mdi-emoticon-sad-outline</v-icon>-->
<!--                <p class="app-title primary&#45;&#45;text">{{emotions.negative}}%</p>-->
<!--            </div>-->
<!--        </div>-->
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
                negative: 22,
                neutral: 30,
                positive: 48,
            }
        }
    },
    computed: {
        negativeStyles() {
            return  {backgroundColor: 'red', width: this.emotions.negative + '%', borderTopLeftRadius: '5px', borderBottomLeftRadius: '5px'}
        },
        neutralStyles() {
            return  {backgroundColor: 'orange', width: this.emotions.neutral + '%'}
        },
        positiveStyles() {
            return {backgroundColor: 'green', width: this.emotions.positive + '%', borderTopRightRadius: '5px', borderBottomRightRadius: '5px'}
        },
    },
    mounted() {
        setTimeout(() => this.load = true,1)
    }
}
</script>

<style scoped>

.emotions-container {
    border-radius: 5px;
    display: flex;
    flex-direction: row;
    height: 100%;
    width: 100%;
    align-items: center
}
.emo {
    height: 35px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.meter {
    height: 5px;
    border-radius: 20px;
    transition: width .7s
}
</style>
