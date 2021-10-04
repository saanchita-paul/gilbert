<template>
    <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                icon
                v-bind="attrs"
                v-on="on"
            >
                <v-icon small> mdi mdi-alert-circle</v-icon>
            </v-btn>
        </template>
        <span>{{value}}</span>
    </v-tooltip>
</template>

<script>
export default {
    name: "EnergyTooltip",
    props: ['value']
}
</script>

<style scoped>

</style>
