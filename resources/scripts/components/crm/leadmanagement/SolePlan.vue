<template>
    <div class="your-plan" :class="{'active':isActive === plan.name}">
        <p :style="{background: plan.bgColor}">{{plan.title}}</p>
        <div class="pa-4">
            <v-btn @click="reviewPlan" block outlined class="mb-3">Review Plan Details</v-btn>
        </div>
    </div>
</template>

<script>
export default {
name: "SolePlan",
    props: ['plan', 'isActive'],
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
