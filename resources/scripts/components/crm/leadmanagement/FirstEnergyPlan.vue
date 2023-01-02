<template>
    <div class="your-plan" :class="{ active: plan.name === isActive }">
        <p :style="{ background:  plan.bgColor }">{{ getPlanTitle }}</p>
        <div class="pa-4">
            <v-btn @click="reviewPlan" block outlined class="mb-3">
                Review Plan Details
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
