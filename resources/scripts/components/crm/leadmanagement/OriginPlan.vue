<template>
    <div class="your-plan" :class="{ active: isActive === plan.name }">
        <p :style="{ background: plan.bgColor }">{{ plan.title }}</p>
        <div class="pa-4">
            <v-btn @click="reviewPlan" block outlined class="mb-3"
                >Review Plan Details</v-btn
            >
        </div>
    </div>
</template>

<script>
import OriginService from "@scripts/modules/origin/services/OriginService";
import OriginMapper from "@scripts/modules/origin/api/mappers/OriginMapper";

export default {
    name: "OriginPlan",
    // props: ["plan", "isActive"],
    props: {
        plan: {
            require: false
        },
        isActive: {
            require: false
        },
        serviceType: {
            require: true
        },
        selectedPlan: {
            require: false
        },
        leadSummary: {
            require: true
        },
    },
    data() {
        return {
            planDetails: null,
        }
    },
    computed: {

    },
    mounted() {
        if('plans' in this.leadSummary){
            this.planDetails = OriginMapper.mapOriginData(this.leadSummary);
        }
        else{
            this.getOriginData();
        }
        console.log('Origin plan mounted IN Origin Plan component');
    },
    watch: {
        isBothEnergySubmit() {
            this.getOriginData()
        },
        getNMIPrefix() {
            this.getOriginData()
        },
    },
    methods: {
        reviewPlan() {
            this.$emit("toggleDialog");
        },
        async getOriginData() {
            let query = null;
            if(this.isBothEnergySubmit) {
                query = {
                    state: this.state,
                    postcode: this.leadSummary.postcode,
                    nmi_prefix: this.getNMIPrefix,
                }
            } else {
                query = {
                    service_type: this.service_Type,
                    state: this.state,
                    postcode: this.leadSummary.postcode,
                    nmi_prefix: this.getNMIPrefix,
                }
            }

            this.planDetails = await OriginService.getOriginData(query);
            console.log('Origin Plan Details: ', this.planDetails);
        },
    }
};
</script>

<style scoped></style>
