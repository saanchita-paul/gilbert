<template>
    <div class="your-plan" :class="{'active':isActive === sumoPlanName}">
        <p :style="{background: plan.bgColor}">{{ sumoPlanName }}</p>
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
            this.$emit('soleDialog');
        }
    },
    computed:{
        sumoPlanName(){
            if(this.sumoPlanDetails){
                return this.sumoPlanDetails?.plan_name ?? "";
            } else{
                return '';
            }
        }
    }
}
</script>

<style scoped>

</style>
