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
                    <div class="meter text-center" :style="{backgroundColor: 'red', width: '16%'}">
                        16%
                    </div>
                </v-col>
                <v-col md="4">
                    <h4>Neutral</h4>
                </v-col>
                <v-col md="8">
                    <div class="meter text-center" :style="{backgroundColor: 'blue', width: '57%'}">57%</div>
                </v-col>
                <v-col md="4">
                    <h4>Positive</h4>
                </v-col>
                <v-col md="8">
                    <div class="meter text-center" :style="{backgroundColor: 'green', width: '27%'}">27%</div>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
name: "EmotionMeter"
}
</script>

<style scoped>
.meter {
    color: white;
    border-radius: 20px;
    height: 100%;
    width: 25%;
}
</style>
