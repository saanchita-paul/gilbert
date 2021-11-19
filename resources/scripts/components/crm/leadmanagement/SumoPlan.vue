<template>
    <div class="your-plan" :class="{'active':isActive === sumoPlanDetails.plan_name}">
        <p :style="{background: plan.bgColor}">{{sumoPlanDetails.plan_name}}</p>
        <div class="pa-4">
            <v-btn @click="reviewPlan" block outlined class="mb-3">Review Plan Details</v-btn>
        </div>
    </div>
</template>

<script>
export default {
name: "SumoPlan",
    props: ['plan', 'isActive', 'sumoPlanDetails'],
    methods:{
        reviewPlan()
        {
            console.log('detail clicked')
            this.$emit('soleDialog');
            console.log(this.$attrs)
        }
    }
}
</script>

<style scoped>

</style>
