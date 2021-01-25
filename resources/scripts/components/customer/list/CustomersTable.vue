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
                    </td>
                    <td>{{ item.first_name }}</td>
                    <td>
                        <div style="height: 25px; width: 25px; background-color: orange; border-radius: 3px"></div>
                    </td>
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
                {
                    text: '',
                    align: 'start',
                    sortable: false,
                    value: 'avatar',
                },
                {text: 'Name', value: 'full_name'},
                {text: 'Sentiment', value: 'emotion'},
                {text: 'User message', value: 'user_message', align: 'center'},
                {text: 'Location', value: 'location'},
                {text: 'Timezone', value: 'time_zone'},
                {text: 'Interacted on', value: 'last_active'},
                {text: '', sortable: false, value: 'last_active', align: 'center'},
                {text: '', sortable: false, value: 'view', align: 'center'},
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
