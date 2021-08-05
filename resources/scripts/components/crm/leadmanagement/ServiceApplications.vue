<template>
    <v-row>
        <v-col cols="12">
            <p class="sub-title">Service Applications</p>
            <div class="d-flex">
                    <div>
                        <p class="mb-0">Energy</p>
                        <div class="d-flex">
                            <div class="service-box active">
                                <p class="mb-0"><v-icon color="yellow">mdi-flash</v-icon> Power</p>
                            </div>
                            <div class="service-box active">
                                <p class="mb-0"><v-icon color="red">mdi-fire</v-icon> Gas</p>
                                <img src="/assets/images/SupplierLogo.png" width="60px">
                            </div>
                    </div>
                    </div>
                    <div>
                        <p class="mb-0 ml-2">Water</p>
                        <div class="service-box">
                            <p class="mb-0"><v-icon color="grey lighten-1">mdi-water</v-icon> water</p>
                        </div>
                    </div>
                    <div>
                        <p class="mb-0 ml-2">Internet</p>
                        <div class="service-box">
                            <p class="mb-0"><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</p>
                        </div>
                    </div>
            </div>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>
            <img class="my-3" src="/assets/images/SupplierLogo.png" width="150px">
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <p class="sub-title">Select a plan for [POWER1] and [GAS2]</p>

            <div class="d-flex">
                <div class="your-plan" v-for="plan in plans" :key="plan.id" :class="{active:plan.active}">
                    <p>{{plan.title}}</p>
                    <div class="pa-4">
                        <v-btn @click="reviewPlan(plan.id)" block outlined class="mb-3">Review Plan Details</v-btn>
                        <v-btn @click="selectPlan(plan.id)" block outlined class="mb-3">Select Plan</v-btn>
                    </div>
                </div>
<!--                <div class="your-plan">-->
<!--                    <p>No Frills (Home)</p>-->
<!--                    <div class="pa-4">-->
<!--                        <v-btn block outlined class="mb-3">Review Plan Details</v-btn>-->
<!--                        <v-btn block outlined class="mb-3">Select Plan</v-btn>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="your-plan">-->
<!--                    <p>Basic Home</p>-->
<!--                    <div class="pa-4">-->
<!--                        <v-btn block outlined class="mb-3">Review Plan Details</v-btn>-->
<!--                        <v-btn block outlined class="mb-3">Select Plan</v-btn>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </v-col>

    </v-row>
</template>

<script>
export default {
  name: "ServiceApplications",
    props:{
      plans : {
          require: true
      }
    },
    methods: {
        reviewPlan() {
            //todo
        },
        selectPlan() {
            //todo
        }
    }
};
</script>

<style scoped>
</style>
