<template>
    <v-row>
        <v-col sm="8">
            <h2 class="black--text app-title-big">{{title}}</h2>
        </v-col>
        <v-col sm="4" class="menu-color py-0">
            <slot></slot>
<!--            <date-range-picker v-model="dateRange"/>-->
        </v-col>
    </v-row>
</template>

<script>
export default {
    name: "PageHeader",
    props: ['title']
}
</script>

<style scoped>

</style>
