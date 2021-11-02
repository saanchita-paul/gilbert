<template>
    <div class="your-plan active">
        <p :style="{background: plan.bg}">{{plan.text}}</p>
        <div class="pa-4">
            <v-btn @click="reviewPlan" block outlined class="mb-3">Review Plan Details</v-btn>
        </div>
    </div>
</template>

<script>
export default {
name: "SolePlan",
    props: ['plan'],
    methods:{
        reviewPlan()
        {

        }
    }
}
</script>

<style scoped>

</style>
