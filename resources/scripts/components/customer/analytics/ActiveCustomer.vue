<template>
    <v-card>
        <v-card-title  class="card-bg-color app-title">
<!--            <v-icon size="28" color="white">mdi-clock</v-icon>-->
            Latest 5 Customers
<!--            <span class="ml-2 title">Recent Customers</span>-->
        </v-card-title>
        <v-divider/>
        <v-list dense class="px-4">
            <span v-for="i in 3" :key="i">
                    <v-list-item>
                        <v-list-item-avatar>
                            <v-img :src="'https://cdn.vuetifyjs.com/images/lists/1.jpg'"></v-img>
                        </v-list-item-avatar>
                    <v-list-item-content>
                        <v-row>
                            <v-col md="5">
                                <v-list-item-title>MR Green Lemon</v-list-item-title>
                            </v-col>
                            <v-col md="2">
                                <v-list-item-subtitle>GTM +7</v-list-item-subtitle>
                            </v-col>
                            <v-col md="3">
                                <v-list-item-subtitle>10 hours ago</v-list-item-subtitle>
                            </v-col>
                            <v-col md="2">
                                <v-icon @click="onOpenChat">mdi-facebook-messenger</v-icon>
                            </v-col>
                        </v-row>
                    </v-list-item-content>
                </v-list-item>
                <v-divider v-if="i < 3"></v-divider>
            </span>
        </v-list>
    </v-card>
</template>

<script>
export default {
    name: "ActiveCustomer",
    methods: {
        onOpenChat() {
            this.$router.push({name: 'customers.conversation'})
        }
    }
}
</script>

<style scoped>

</style>
