<template>
    <div v-if="isLoaded">
        <div class="card-body">
            <v-card class="mx-auto" max-width="1024" outlined>
                <div class="header-text">
                    <h2>Chatbot</h2>
                    <h4>Retailer Plans</h4>
                    <p>Drag the plans to re-arrangee how we display plans in the chatbot</p>


                  <v-row>

                    <v-col cols="12">
                        <v-row class="bordered-around">
                          <v-col class="font-weight-bold bordered-right" >Card</v-col>
                          <v-col class="font-weight-bold bordered-right"> Plan Name</v-col>
                          <v-col class="font-weight-bold bordered-right"> Retailer</v-col>
                          <v-col class="font-weight-bold bordered-right"> Power Sales </v-col>
                          <v-col class="font-weight-bold" > Gas Salse</v-col>
                        </v-row>
                      <draggable v-model="plans"  @start="drag=true" @end="shuffleData">
                      <v-row v-for="element in plans" :key="element.id"  class="bordered-around">
                          <v-col class="bordered-right">
                            <v-img style="max-width: 150px" :src="element.image" ></v-img>
                          </v-col>
                          <v-col class="bordered-right">{{element.key}}</v-col>
                          <v-col  class="bordered-right">{{ element.retailer }}</v-col>
                          <v-col class="bordered-right">{{ element.power_sales }}</v-col>
                          <v-col>{{ element.gas_sales }}</v-col>

                      </v-row>
                      </draggable>
                    </v-col>

                  </v-row>


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
        },

        // getCardImage(image) {
        //   return `${process.env.MIX_BOT_ROOT_URL}/images/static/plan/${image}.png`;
        // }
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

.bordered-around {
  border: 1px solid #dddddd
}
.bordered-right {
  border-right: 1px solid #dddddd
}


</style>
