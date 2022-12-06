<template>
    <div class="d-flex">
        <div class="your-plan bg" :class="{active: plan.key === selectedPlan}">
            <p>{{ plan.title }}</p>
            <div class="pa-4">
                <span>{{ plan.name }}</span>
                <span>{{ plan.amount }}</span>
            </div>
            <div class="pa-4">
                <v-btn @click="reviewPlan" block outlined rounded class="mb-3">View Plan</v-btn>
            </div>
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

<style lang="scss" scoped>
.bg {
    p {
        background-color: #85639A;
    }
}
</style>
