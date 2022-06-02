<template>
    <v-container class="pa-0 " v-if="loaded">
        <EnergyPlanContainer 
            :planDetails="planDetails"
            :postcode="postcode"
            :state="state"
            :plan="plan"
            :services="services"
        ></EnergyPlanContainer>
    </v-container>
</template>

<script>

import EAPlanService from "@scripts/services/ea/EAPlanService";
import EnergyPlan from "@scripts/models/ea/EnergyPlan";
import GasUsage from "@scripts/components/ea/GasUsage";
import DailySupplyCharge from "@scripts/components/ea/DailySupplyCharge";
import EnergyTooltip from "@scripts/components/ea/EnergyTooltip";
import { merge } from 'lodash-es';
import { SERVICE_TYPES } from "@scripts/models/ea/EnergyPlan";
import {SOLAR_PACK } from "@scripts/models/ea/EnergyPlan";
import EnergyPlanContainer from "@scripts/components/ea/EnergyPlanContainer";

export default {
    name: "EnergyPlanDetails",
    props: ['postcode', 'state', 'plan', 'services'],

    components: {
        EnergyPlanContainer,
        GasUsage,
        EnergyTooltip,
        DailySupplyCharge,
    },

    data() {
        return {
            loaded: false,
            planDetails: new EnergyPlan(),
            solarBuyPackInfo: SOLAR_PACK
        }
    },
    computed: {
        isVictorian() {
            return this.postcode && this.postcode.length === 4 && this.postcode.slice(0, 1).toString() === '3'
        },
        planText() {
            switch (this.service_type) {
                case SERVICE_TYPES.GAS:
                    return 'Gas';
                case SERVICE_TYPES.ELECTRICITY:
                    return 'Electricity';
                default:
                    return 'Electricity & Gas'
            }
        },
        isPlanHasGas() {
            return  !!this.planDetails.rates.gas;
        },
        isPlanHasElectricity() {
            return  !!this.planDetails.rates.electricity;
        },
    },

    filters: {
        estimated_price(value) {
            return Math.ceil(value)
        },
    },
    async mounted() {
        if (this.plan) {
            await this.getPlanDetails();
            this.loaded = true;
        }
    },

    methods: {
        async getPlanDetails() {
            merge(this.planDetails, await EAPlanService.getPlanDetailsByPlanType({
                service_type: this.services,
                plan_type: this.plan,
                postcode: this.postcode,
                state: this.state
            }))

        },
        goToSearch() {
            if (this.$refs.bdid) {
                this.$refs.bdid.scrollIntoView({behavior: "smooth"});
            }
        }
    }
};
</script>

<style scoped>
.header-area {
    background-color: rgb(0, 83, 33);
    color: #ffffff;
}

.outlined {
    border: 1px solid #e9e9e9;
}

.policy {
    flex-wrap: wrap;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    align-content: flex-start;
}
</style>
