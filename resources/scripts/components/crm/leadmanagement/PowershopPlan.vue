<template>
    <div class="inactive ma-2" :class="{ active: isActive === plan.name }">
        <p :style="{ background: plan.bgColor }">{{ plan.name }}</p>
        <div class="pa-4">
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

.inactive {
    background: #dddddd;

}

</style>
