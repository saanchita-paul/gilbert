<template>
    <div class="your-plan" :class="{ active: plan.name === isActive }">
        <p :style="{ background: '#FA0E6A' }">{{ getPlanTitle }}</p>
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
    computed: {
      getPlanTitle() {
          return this.plan?.marketing_offer_name;
      }
    },
    methods: {
        reviewPlan() {
            // this.$emit("toggleDialog", this.plan);
            this.$emit("toggleDialog");
        }
    }
};
</script>

<style scoped>
.your-plan p {
    min-height: 100px !important;
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>
