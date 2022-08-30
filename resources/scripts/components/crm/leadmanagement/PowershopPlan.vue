<template>
    <div class="ma-2 pa-2 your-plan" style="min-width: 310px" :class="{ active: plan.name === isActive }">
        <p class=" mb-7 pt-4 pb-5 text-center" :style="{ background: 'white', borderRadius: '5px',  background: '#FA0E6A' }">{{ plan.title }}</p>
        <div class="pa-0">
            <v-btn @click="reviewPlan" block outlined class="mb-3"
            >Review Plan Details</v-btn
            >
        </div>
    </div>
</template>

<script>
export default {
    name: "PowershopPlan",
    props: ["plan", "isActive"],
    methods: {
        reviewPlan() {
            this.$emit("toggleDialog", this.plan);
        }
    }
};
</script>

<style scoped>


</style>
