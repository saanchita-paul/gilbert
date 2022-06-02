<template>
    <v-card>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
        <v-col cols="12">
            <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>
            <div class="d-flex align-content-lg-space-around mt-2">
                <ServiceProvider
                    @onSelectProvider="onSelectProvider(provider.name)"
                    :selectedProvider="selectedProvider"
                    v-for="provider in providers"
                    :key="provider.name"
                    :provider="provider">
                </ServiceProvider>
            </div>
        </v-col>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
        <v-col cols="12">
            <p class="sub-title">Select an internet Plan.</p>

            <div class="d-flex" >
                <div class="d-flex" v-for="plan in plans" :key="plan.text">
                    <SolePlan
                        @soleDialog="soleDialog"
                        :isActive="selectedPlan"
                        :plan="plan"
                        @click.native="selectPlan(plan)">
                    </SolePlan>
                </div>
            </div>
        </v-col>
        <v-col cols="12">
            <div class="d-flex justify-end py-4 px-4" style="width: 100%; background-color: white;">
                <v-btn
                    :disabled="isDisable()"
                    color="#542E89"
                    @click="submit"
                    class="white--text"
                >
                    Submit for connection
                </v-btn>
            </div>
        </v-col>
    </v-card>
</template>

<script>
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";
import SolePlan from "@scripts/components/crm/leadmanagement/SolePlan";
import ServiceProvideres from "@scripts/data/ServiceProvideres";

export default {
name: "InternetService.",
components:
    {SolePlan, ServiceProvider},
props: {
    leadSummary: {
        require: true
    }
},
    data() {
        return {
            viewPlanDialog: false,
            plans: null,
            selectedProvider: 'starter_speed',
            selectedPlan: null,
        }
    },
    methods: {
        soleDialog(){
            this.viewPlanDialog = !this.viewPlanDialog;
        },
        onSelectProvider(providerId) {
            this.selectedProvider = providerId

            const selectedProvider = this.providers.find(dt=> {
                return dt.name === this.selectedProvider
            })

            this.plans = selectedProvider.plans;
            this.selectedPlan = selectedProvider.default_plan;
        },
        selectPlan(plan){
           this.selectedPlan = plan.name;
        },
        isDisable() {
            return true;
        },
        submit() {
            this.$eventBus.$emit("busUtilitySubmit", "internet");
        },
    },
    computed:{
        providers() {
            return ServiceProvideres.filter((dt)=> {
                return dt.service_type === 'internet';
            });
        },
    },
    mounted() {
        this.onSelectProvider('telstra');
    }
}
</script>
