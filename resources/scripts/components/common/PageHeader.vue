<template>
    <v-row class="main-breadcrumbs">
        <v-col class="my-0 py-0" sm="12">
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:item="{ item }">
                    <v-breadcrumbs-item
                        :to="{name: item.route_name}"
                        :disabled="item.disabled"
                        exact
                    >
                        {{ item.text }}
                    </v-breadcrumbs-item>
                </template>
            </v-breadcrumbs>
        </v-col>
        <v-col sm="6" class="mt-0 pt-0">
                <h2 style="margin-top: 5px" class="black--text app-title-big">{{title}}</h2>
        </v-col>
        <v-col sm="6" class="menu-color py-0">
            <slot></slot>
        </v-col>
    </v-row>
</template>

<script>
export default {
    name: "PageHeader",
    props: ['title', 'breadcrumbs'],
    mounted() {
        console.log(this.getBreadcrumbs)
    },
    computed: {
        getBreadcrumbs() {
            return this.$route.path
        }
    }
}
</script>

<style scoped>

</style>
