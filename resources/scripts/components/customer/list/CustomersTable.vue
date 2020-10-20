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
                    <td>{{ item.gender }}</td>
                    <td>{{ item.time_zone }}</td>
                    <td>TBD</td>
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
                    text: 'Avatar',
                    align: 'start',
                    sortable: false,
                    value: 'avatar',
                },
                {text: 'Firstname', value: 'first_name'},
                {text: 'Lastname', value: 'last_name'},
                {text: 'Gender', value: 'gender'},
                {text: 'Time Zone', value: 'time_zone'},
                {text: 'Emotions', value: 'emotion'},
                {text: 'NPS', value: 'nps'},
                {text: 'Interacted On', value: 'last_active'},
                {text: '', sortable: false, value: 'last_active', align: 'center'},
            ],
            desserts: [
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    gender: 'male',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    gender: 'male',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    gender: 'male',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    gender: 'male',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    first_name: 'MR',
                    last_name: 'SKU',
                    gender: 'male',
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
