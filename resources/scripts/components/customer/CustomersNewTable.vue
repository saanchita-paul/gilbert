<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="customers"
            :search="search"
        >
            <template
                v-slot:body="{ items }"
            >
                <tbody>
                    <tr
                        v-for="(item, index) in items"
                        :key="index"
                        class="py-1"
                    >
                        <td>
                            <v-avatar size="40"><img :src="item.avatar"></v-avatar>
                            <div>
                                <div>Firstname Lastname</div>
                                <div>Interacted 10 minutes ago</div>
                            </div>
                        </td>
                        <td>{{ 'In Progress' }}</td>
                        <td>
                            <div
                                class="mgs-sentiment"
                                :style="`backgroundColor: ${getColor(50)}`"
                            >
                                {{ 'I am good'}}
                            </div>
                        </td>
                        <td>{{ 'Victoria' }}</td>
                        <td>{{ '+ 10:00' }}</td>
                        <td>{{ '10 minutes ago' }}</td>
                        <td class="d-flex justify-center">
                            <v-icon color="#006AFF" @click="openConversation(item.id)">mdi-facebook-messenger</v-icon>
                        </td>
                        <td>
                            <v-icon @click="openProfile(item.id)">mdi-account-arrow-right</v-icon>
                        </td>
                    </tr>
                </tbody>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
import CustomerService from "@scripts/services/CustomerService";

export default {
    data() {
        return {
            search: '',
            headers: [
                {text: 'Customer Details', align: 'start', value: 'customer_details'},
                {text: 'Issue Status', value: 'issue_status'},
                {text: 'Connection Status', value: 'connection_status'},
                {text: 'Sentiment', value: 'user_sentiment', align: 'center'},
                {text: 'Location', value: 'location'},
                {text: 'Connection Date', value: 'connection_date'},
                {text: '', sortable: false, value: 'profile', align: 'center'},
                {text: '', sortable: false, value: 'chat', align: 'center'},
            ],
            customers: []
        }
    },
    methods: {
        getColor(sentiment) {
            switch (sentiment) {
                case 'positive':
                    return 'green';
                case 'negative':
                    return 'red'
                default:
                    return 'orange'
            }
        },
        openConversation(id) {
            this.$router.push({name: `customers.conversation`, query: {customer: id}})
        },
        openProfile(id) {
            this.$router.push({name: `customers.profile`, params: {customerId: id}})
        },
    },
    async mounted() {
        this.customers = await CustomerService.getAllCustomerData()
    }
}
</script>
<style scoped>

</style>
