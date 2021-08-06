<template>
        <div class="d-flex">
            <div class="your-plan" :class="{active: plan.id === selectedPlan}">
                <p>{{plan.title}}</p>
                <div class="pa-4">
                    <v-btn @click="reviewPlan(plan.id)" block outlined class="mb-3">Review Plan Details</v-btn>
                    <v-btn @click="selectPlan(plan.id)" block outlined class="mb-3">Select Plan</v-btn>
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
            //todo
        },
        selectPlan(plan) {
           this.$emit('selectPlan', plan);
        },
    },
}
</script>

<style scoped>

</style>
