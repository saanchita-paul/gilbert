<template>
    <div class="internet-plan" style="cursor: pointer" :class="{'selected' : plan.name === selectedPlan}">

        <div class="plan-title-header">
            <div class="d-flex align-center">
                <v-img
                    class="mr-2"
                    max-width="30"
                    src="/assets/images/logo/providers/goodtel_logo.png"
                ></v-img>
                <h3>{{ plan.title }}</h3>
            </div>
        </div>

        <div class="pa-4">
            <p class="mb-0 text-internet">{{ plan.name }}</p>
            <p class="text-internet">{{ plan.mbps }}</p>
            <p class="black--text font-weight-bold">{{ plan.amount }}</p>
        </div>

        <div class="view-plan">
            <v-btn block outlined rounded @click="reviewPlan" style="border: 1px solid #85639A !important;">
                View Plan Details
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
            this.$emit('view', this.plan);
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
    border: 1px solid #85639A;
    margin-right: 10px;
    text-align: left;
    border-radius: 32px;
    flex-basis: 250px;
    opacity: 0.3;
}

.plan-title-header {
    background-color: #85639A;
    color: white;
    padding: 20px 10px;
    border-radius: 32px 32px 0 0;
}

.internet-plan.active {
    opacity: .9;
    box-shadow: 0 3px 3px -2px rgba(0, 0, 0, .2), 0 3px 4px 0 rgba(0, 0, 0, .14), 0 1px 8px 0 rgba(0, 0, 0, .12) !important;
}

.selected {
    opacity: .9;
}

.view-plan {
    padding: 0 15px;
    margin-bottom: 10px
}
.text-internet {
    color: #85639A;
    font-weight: 700;
}
</style>
