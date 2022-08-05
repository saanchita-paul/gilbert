<template>
    <div class="stepper-wrapper">
        <div class="stepper-item" v-for="item in agentStatus" :key="item.stepCounter"
             :class="{completed: item.active}">
            <v-tooltip bottom :disabled="!item.active">
                <template v-slot:activator="{ on, attrs }">
                    <div class="step-name">{{ item.stepName }}</div>
                    <div class="step-counter" v-on="on">{{ item.stepCounter }}</div>
                </template>
                <div>
                    <v-card
                        max-width="250"
                        outlined
                        elevation="4"
                        shaped
                    >
                        <v-card-title>
                            <v-icon right color="#542E89">mdi-circle-outline</v-icon>
                            &nbsp;
                            <h5 style="color: #542E89">{{ item.stepName }}</h5>
                        </v-card-title>
                        <v-card-text>
                            <p style="color: #263238">{{ item.description }}</p>
                        </v-card-text>
                    </v-card>
                </div>
            </v-tooltip>
        </div>
    </div>

</template>

<script>

export default {
    name: "AgentProgressStatus",
    props: ["agentStatus"],
    data() {
        return {}
    },
}
</script>

<style scoped>
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.stepper-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.stepper-item::before {
    position: absolute;
    content: "";
    border-bottom: 4px solid #dddddd;
    width: 100%;
    top: 35px;
    left: -50%;
    z-index: 2;
}

.stepper-item::after {
    position: absolute;
    content: "";
    border-bottom: 4px solid #dddddd;
    width: 100%;
    top: 35px;
    left: 50%;
    z-index: 2;
}

.stepper-item .step-name {
    /*color: #542E89;*/
}

.stepper-item .step-counter {
    position: relative;
    z-index: 4;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #FFFFFF;
    border: 4px solid #dddddd;
    margin-bottom: 6px;
    cursor: pointer;
}

.stepper-item.completed .step-counter {
    background-color: #542E89;
    border-color: #542E89;
    color: #FFFFFF;
}

.stepper-item.completed::before {
    position: absolute;
    content: "";
    border-bottom: 4px solid #542E89;
    width: 100%;
    top: 35px;
    left: -50%;
    z-index: 3;
}


.stepper-item:first-child::before {
    content: none;
}

.stepper-item:last-child::after {
    content: none;
}

.v-tooltip__content {
    background-color: transparent;
}
</style>
