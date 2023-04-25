<template>
    <v-card>
        <v-toolbar
            dark
            color="#85639A"
        >
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn
                    icon
                    dark
                    @click="closeDialog"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-card-title></v-card-title>
        <v-card-subtitle></v-card-subtitle>
        <v-card-text></v-card-text>
        <v-card-actions>
            <v-btn class="selectButton" color="#85639A" style="border-radius: 10px !important;" @click="closeDialog">
                Choose Plan
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>

export default {
    name: "InternetPlanDetails",
    components: {},
    props: {},
    data() {
        return {}
    },
    computed: {},
    watch: {},
    mounted() {
    },
    methods: {
        closeDialog() {
            this.$emit('toggleDialog')
        }
    },
}
</script>

<style scoped>
.selectButton {
    color: white;
    font-weight: bold;
    font-size: 16px;
    width: 95%;
    padding-bottom: 4px;
    padding-top: 10px;
    border-radius: 8px;
}
</style>
