<template>
    <div v-if="isLoaded">
        <!-- <draggable v-model="plans"  @start="drag=true" @end="shuffleData">
            <div v-for="element in plans" :key="element.id">
               {{element.id}}  {{element.key}}
            </div>
            <div slot="header">Plan List</div>
        </draggable> -->
        <div class="card-body">
            <v-card class="mx-auto" max-width="1024" outlined>
                <div class="header-text">
                    <h2>Chatbot</h2>
                    <h4>Retailer Plans</h4>
                    <p>Drag the plans to re-arrangee how we display plans in the chatbot</p>
                    <!-- <v-data-table
                        :headers="headers"
                        :items="plans"
                        class="elevation-1"
                        hide-default-footer
                        disable-pagination
                    >
                        <template v-slot:item.plan_name="{ item }"> {{ item.key }} </template>
                        <template v-slot:item.retailer="{ item }"> {{ item.retailer }} </template>
                        <template v-slot:item.power_sales="{ item }"> {{ item.power_sales }} </template>
                        <template v-slot:item.gas_sales="{ item }"> {{ item.gas_sales }} </template>
                    </v-data-table> -->
                    <table>
                        <thead>
                            <tr>
                                <th >Plan Name</th>
                                <th >Retailer</th>
                                <th >Power Sales</th>
                                <th >Gas Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <draggable v-model="plans"  @start="drag=true" @end="shuffleData">
                            <div v-for="element in plans" :key="element.id">
                                <tr>
                                    <td class="data-style">{{element.key}}</td>
                                    <td class="data-style">{{ element.retailer }}</td>
                                    <td class="data-style">{{ element.power_sales }}</td>
                                    <td class="data-style">{{ element.gas_sales }}</td>
                                </tr>
                            </div>
                            </draggable>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </div>
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

.card-body {
    margin-top: 40px;
    
}
.card-body .header-text {
    margin: 20px;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

.data-style {
    width: 20% !important;
}
</style>
