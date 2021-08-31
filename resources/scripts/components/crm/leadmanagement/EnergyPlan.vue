<template>
        <div class="d-flex">
            <div class="your-plan" :class="{active: plan.key === selectedPlan}">
                <p>{{plan.title}}</p>
                <div class="pa-4">
                    <v-btn @click="reviewPlan" block outlined class="mb-3">Review Plan Details</v-btn>
                    <v-btn @click="selectPlan(plan)" block outlined class="mb-3">Select Plan</v-btn>
                </div>
            </div>
        </div>
</template>

<script>
export default {
name: "EnergyPlan",
    props:['selectedPlan','plan'],


    methods: {
        reviewPlan() {
            this.$emit('view', this.plan);
        },

        selectPlan(plan) {
           this.$emit('selectPlan', plan);
        },

        isActive() {
            if(this.plan.id === this.selectedPlan) {
                this.selectPlan(this.plan);
            }
        }

    },
    mounted() {
    this.isActive();

    }
}
</script>

<style scoped>

</style>
