<template>
    <div>
        <v-col cols="12" class="d-flex">
            <v-checkbox
                v-model="isSubmitBothPowerAndGas"
            ></v-checkbox>
            <p class="checkbox-text">Submit both Power and Gas</p>
        </v-col>
        
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
        <v-col cols="12">
            <p class="mb-0 sub-title">
                Which supplier would you like to connect with?
            </p>
            <div class="d-flex align-content-lg-space-around mt-2">
                <ServiceProvider
                    @onSelectProvider="onSelectProvider(provider.name)"
                    v-for="provider in providers"
                    :key="provider.name"
                    :selectedProvider="selectedPowerProvider"
                    :provider="provider"
                ></ServiceProvider>
            </div>
        </v-col>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
    </div>
</template>

<script>
import ServiceProvideres from "@scripts/data/ServiceProvideres";
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";


export default {
    name: "GasService",
    props: {
        leadSummary: {
            require: true
        },
    },
    components: {
        ServiceProvider,
    },
    data() {
        return {
            isSubmitBothPowerAndGas: false,
            selectedProvider: null
        };
    },
    computed: {
        providers() {
            return ServiceProvideres.filter(dt => {
                return dt.service_type === "energy";
            });
        },
    },
    methods: {}
};
</script>

<style lang="scss" scoped>
.checkbox-text {
    font-size: 18px;
    margin-top: 18px;
}
</style>
