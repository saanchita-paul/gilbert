<template>
    <v-card>
        <v-data-table
            :headers="headers"
            :items="desserts"
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
                        <v-avatar size="40"><img src="https://cdn.vuetifyjs.com/images/lists/1.jpg"></v-avatar>
                    </td>
                    <td>{{ item.first_name }}</td>
                    <td>{{ item.last_name }}</td>
                    <td>{{ item.user_message }}</td>
                    <td>{{ item.time_zone }}</td>
                    <td><div style="height: 25px; width: 25px; background-color: orange; border-radius: 3px"></div></td>
                    <td>TBD</td>
                    <td>{{ item.last_active }}</td>
                    <td class="d-flex justify-center">
                        <v-icon @click="openConversation(item.id)">mdi-facebook-messenger</v-icon>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
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
                {text: 'First name', value: 'first_name'},
                {text: 'Last name', value: 'last_name'},
                {text: 'User message', value: 'user_message'},
                {text: 'Timezone', value: 'time_zone'},
                {text: 'Sentiment', value: 'emotion'},
                {text: 'NPS', value: 'nps'},
                {text: 'Interacted on', value: 'last_active'},
                {text: '', sortable: false, value: 'last_active', align: 'center'},
            ],
            desserts: [
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    user_message: 'booking mover',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    user_message: 'booking mover',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    user_message: 'booking mover',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    user_message: 'booking mover',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    user_message: 'booking mover',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
            ],
        }
    },
    methods: {
        openConversation(id) {
            this.$router.push({name: `customers.conversation`, query: {customer: id}})
        }
    }
}
</script>
<style scoped>

</style>
