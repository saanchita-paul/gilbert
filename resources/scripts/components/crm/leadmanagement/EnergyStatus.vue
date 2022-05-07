<template>
    <div>
        <p
            v-if="this.status"
            class="py-0 my-0 text-center active-power-subtitle"
            :style="{color: this.status.color}"
        >
            {{this.status.text}}
        <p
            v-else
            class="py-0 my-0 text-center active-power-subtitle"
        >
            -
        </p>
        <p
            v-if="false"
            class="ma-0 fontStyleQuote text-center"
        >
            Supplier: {{ this.provider }}
        </p>
        <p
            v-if="false"
            class="ma-0 fontStyleQuote text-center"
        >
            Plan: {{ this.plan }}
        </p>
        <p
            class="ma-0 fontStyleQuote text-center"
        >
            Quote ID: {{ this.quoteReference }}
        </p>
        <p
            class="reason ma-0" v-for="reason in reasons" :key="reason">
            {{reason}}
        </p>
    </div>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
    name: "EnergyStatus",
    props: {
        type: {
            require: true,
        },
        leadSummary: {
            require: true
        }
    },
    computed: {
        status() {
            let utilityStatus = this.type === 'power' ?
                UtilityStoreService.getPowerStatus()
                : UtilityStoreService.getGasStatus();
            return utilityStatus ? LeadApplicationService.mapStatus(utilityStatus) : null
        },
        service() {
            return this.leadSummary.connection_services?.find(service => service.service_type === this.type);
        },
        quoteReference(){
            return this.service?.quote_reference ? this.service.quote_reference : '-'
        },
        reasons() {
            const reasons = this.service?.reasons;
            if (Array.isArray(reasons)) {
                return reasons.map(reason => reason?.reason_text || '')
            }
            return []
        },
        provider() {
            return this.type === 'power' ? UtilityStoreService.getPowerProvider() : UtilityStoreService.getGasProvider();
        },
        plan() {
            return this.type === 'power' ? UtilityStoreService.getPowerPlan() : UtilityStoreService.getGasPlan();
        }
    }
}
</script>

<style scoped>
.active-power-subtitle {
    font-size: 12px !important;
}
.reason {
    font-size: .8em;
}
.fontStyleQuote{
    font-family: Roboto;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: 13px;
    letter-spacing: 0em;
    text-align: center;
}
.dangerText {
    color: red;
}
</style>
