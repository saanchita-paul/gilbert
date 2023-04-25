<template>
    <div class="internet-plan" style="cursor: pointer" :class="{'selected' : plan.name === selectedPlan}">

        <div class="plan-title-header">
            <div class="d-flex align-center">
                <v-img
                    class="mr-2"
                    max-width="30"
                    :src="plan.logo"
                ></v-img>
                <h3 class="font-24">{{ plan.provider }}</h3>
            </div>
        </div>

        <div class="pa-4 font-16">
            <p class="mb-0 text-internet">{{ plan.display_name }}</p>
            <p class="text-internet">{{ plan.mbps }}</p>
            <p class="black--text font-weight-bold mb-0">{{ '$' + plan.price + '/month' }}</p>
        </div>

        <div class="view-plan">
            <v-btn
                block
                outlined
                color="primary"
                :href="plan.details_url"
                target="_blank"
                class="font-weight-bold border-radius-14 border-1 font-16"
            >
                View Plan
            </v-btn>
        </div>

        <div class="view-plan">
            <v-btn
                v-if="plan.name === selectedPlan"
                block
                color="#85639A"
                class="font-weight-bold border-radius-14 font-16 white--text"
            >
                Plan Selected!
            </v-btn>
            <v-btn
                v-else
                block
                class="font-weight-bold border-radius-14 border-1 font-16"
            >
                Select Plan
            </v-btn>
        </div>
    </div>
</template>

<script>
export default {
    name: "InternetPlan",
    props: ['selectedPlan', 'plan'],


    methods: {
        reviewPlan() {
            this.$emit('reviewPlan', this.plan);
        },

        selectPlan(plan) {
            this.$emit('selectPlan', plan);
        },

        isActive() {
            if (this.plan.id === this.selectedPlan) {
                this.selectPlan(this.plan);
            }
        }

    },
    mounted() {
        this.isActive();
    }
}
</script>

<style scoped>
.internet-plan {
    border: 2px solid #85639A;
    margin-right: 10px;
    text-align: left;
    border-radius: 30px;
    flex-basis: 250px;
    opacity: 0.5;
}

.plan-title-header {
    background-color: #85639A;
    color: white;
    padding: 10px;
    border-radius: 26px 26px 0 0;
}

.internet-plan.active {
    opacity: 1;
    box-shadow: 0 3px 3px -2px rgba(0, 0, 0, .2), 0 3px 4px 0 rgba(0, 0, 0, .14), 0 1px 8px 0 rgba(0, 0, 0, .12) !important;
}

.selected {
    opacity: 1;
}

.view-plan {
    padding: 0 15px;
    margin-bottom: 10px
}

.text-internet {
    color: #85639A;
    font-weight: 700;
}

.border-radius-14 {
    border-radius: 14px !important;
}

.border-1 {
    border: 1px solid #85639A !important;
}

.font-24 {
    font-size: 24px;
}

.font-16 {
    font-size: 16px;
}
</style>
