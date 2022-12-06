<template>
    <v-card>
        <v-col cols="12">
            <p class="mb-0 sub-title">Our Available NBN Suppliers and their plans</p>
            <div class="d-flex align-content-lg-space-around mt-2">
                <div class="d-flex align-center">Supplier:</div>
                <InternetServiceProvider
                    @onSelectProvider="onSelectProvider(provider.name)"
                    :selectedProvider="selectedProvider"
                    v-for="provider in providers"
                    :key="provider.name"
                    :provider="provider">
                </InternetServiceProvider>
            </div>
        </v-col>

        <v-col cols="12">
            <div class="d-flex">
                <div class="d-flex" v-for="plan in plans" :key="plan.text">
                    <InternetPlan
                        @view="view"
                        :isActive="selectedPlan"
                        :plan="plan"
                        @click.native="selectPlan(plan)">
                    </InternetPlan>
                </div>
            </div>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <div class="d-flex justify-end py-4 px-4" style="width: 100%; background-color: white;">
                <v-btn
                    :disabled="isDisable()"
                    color="#542E89"
                    @click="submit"
                    class="white--text"
                >
                    Submit for NBN
                </v-btn>
            </div>
        </v-col>

        <v-dialog v-model="viewPlanDetails" max-width="450">
            <v-card>
                <InternetPlanDetails
                />
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import InternetServiceProvider from "@scripts/components/crm/leadmanagement/InternetServiceProvider";
import InternetPlan from "@scripts/components/crm/leadmanagement/InternetPlan";
import InternetProviders from "@scripts/data/InternetProviders";
import InternetPlanDetails from "@scripts/components/internet/goodtel/InternetPlanDetails";

export default {
    name: "InternetService.",
    components: {InternetPlan, InternetServiceProvider, InternetPlanDetails},
    props: {
        leadSummary: {
            require: true
        }
    },
    data() {
        return {
            viewPlanDetails: false,
            plans: null,
            selectedProvider: '',
            selectedPlan: null,
        }
    },
    computed: {
        providers() {
            return InternetProviders.map(provider => provider) || [];
        },
    },
    mounted() {
    },
    methods: {
        view() {
            this.viewPlanDetails = !this.viewPlanDetails;
        },
        onSelectProvider(providerId) {
            this.selectedProvider = providerId;

            const selectedProvider = this.providers.find(dt => {
                return dt.name === this.selectedProvider;
            })

            this.plans = selectedProvider.plans;
            // this.selectedPlan = selectedProvider.default_plan;
        },
        selectPlan(plan) {
            this.selectedPlan = plan.name;
        },
        isDisable() {
            return true;
        },
        submit() {
        },
    }
}
</script>
