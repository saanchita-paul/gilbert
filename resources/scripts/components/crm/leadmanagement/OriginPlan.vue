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

export default {
    name: "OriginPlan",
    props: {
        plan: {
            require: false
        },
        isActive: {
            require: false
        },
    },
    data() {
        return {}
    },
    computed: {
    },
    mounted() {},
    watch: {},
    methods: {
        reviewPlan() {
            this.$emit("toggleDialog");
        },
    }
};
</script>

<style scoped></style>
