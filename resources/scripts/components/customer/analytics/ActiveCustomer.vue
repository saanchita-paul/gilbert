<template>
    <v-card >
        <v-card-title class="teal darken-2 white--text">
            <v-icon size="28" color="white">mdi-clock</v-icon>
            <span class="ml-2">RECENT ACTIVE CUSTOMERS</span>
        </v-card-title>
        <v-divider/>
        <v-list dense>
            <span v-for="i in 3" :key="i">
                    <v-list-item>
                        <v-list-item-avatar>
                            <v-img :src="'https://cdn.vuetifyjs.com/images/lists/1.jpg'"></v-img>
                        </v-list-item-avatar>
                    <v-list-item-content>
                        <v-row>
                            <v-col md="8">
                                <v-list-item-title>MR Green Lemon</v-list-item-title>
                            </v-col>
                            <v-col md="4">
                                <v-list-item-subtitle>10 hours ago</v-list-item-subtitle>
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
    name: "ActiveCustomer"
}
</script>

<style scoped>

</style>
