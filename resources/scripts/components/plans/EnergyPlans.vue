<template>
    <div v-if="isLoaded">
        <draggable v-model="plans"  @start="drag=true" @end="shuffleData">
            <div v-for="element in plans" :key="element.id">
               {{element.id}}  {{element.key}}
            </div>
            <div slot="header">Plan List</div>
        </draggable>
    </div>


</template>

<script>
import draggable from 'vuedraggable'
import EnergyPlanService from "@scripts/services/plans/EnergyPlanService";
export default {
    name: "EnergyPlans",
    components:{
        draggable
    },
    data() {
        return {
            plans :  [],
            isLoaded : false,
        };
    },
    mounted() {
        this.loadEnergyPlan();
    },
    methods: {
        async shuffleData() {
            await EnergyPlanService.updateEnergyPlans(this.plans);
        },

        async loadEnergyPlan() {
            this.plans = await EnergyPlanService.getEnergyPlan()
            this.isLoaded = true;
        }
    }
}
</script>

<style scoped>

</style>
