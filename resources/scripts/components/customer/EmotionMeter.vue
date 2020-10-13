<template>
    <v-card>
        <v-card-title>
            EMOTION METER
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col md="4">
                    <h4>Negative</h4>
                </v-col>
                <v-col md="8">
                    <div class="meter text-center" :style="negativeStyles">
                    </div>
                </v-col>
                <v-col md="4">
                    <h4>Neutral</h4>
                </v-col>
                <v-col md="8">
                    <div class="meter text-center" :style="neutralStyles"></div>
                </v-col>
                <v-col md="4">
                    <h4>Positive</h4>
                </v-col>
                <v-col md="8">
                    <div class="meter text-center" :style="positiveStyles"></div>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: "EmotionMeter",
    data() {
        return {
            load: false,
            emotions: {
                negative: 16,
                neutral: 57,
                positive: 27,
            }
        }
    },
    computed: {
        negativeStyles() {
            return this.load ?  {backgroundColor: 'red', width: this.emotions.negative + '%'} : {width: '0%'}
        },
        neutralStyles() {
            return this.load ?  {backgroundColor: 'blue', width: this.emotions.neutral + '%'} : {width: '0%'}
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
    color: white;
    border-radius: 20px;
    height: 100%;
    width: 25%;
    transition: width .7s
}
</style>
