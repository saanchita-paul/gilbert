<template>
    <div class="your-plan" :class="{ active: plan.name === isActive }">
        <p :style="{ background:  plan.bgColor }">{{ getPlanTitle }}</p>
        <div class="pa-4">
            <v-btn href="https://firstenergy-stage-signup.uconx.com.au/agent-login" target="_blank" block outlined
                   class="mb-3">
                Agent Login
            </v-btn>
        </div>
    </div>
</template>

<script>
export default {
    name: "FirstEnergyPlan",
    props: {
        plan: {
            require: true
        },
        isActive: {
            require: false
        },
    },
    computed: {
        getPlanTitle() {
            return this.plan?.title;
        }
    },
    methods: {
        reviewPlan() {
            this.$emit("toggleDialog");
        }
    }
};
</script>

<style scoped>
</style>
