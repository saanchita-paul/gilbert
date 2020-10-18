<template>
    <v-card>
        <v-card-title>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
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
                >
                    <td>{{ item.id }}</td>
                    <td>{{item.full_name}}</td>
                    <td>{{item.time_zone}}</td>
                    <td>{{item.emotion}}</td>
                    <td>{{item.nps}}</td>
                    <td>{{item.last_active}}</td>
                    <td class="d-flex justify-center">
                        <v-icon @click="openConversation(item.id)">mdi-message-arrow-right</v-icon>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
export default {
    data () {
        return {
            search: '',
            headers: [
                {
                    text: '#ID',
                    align: 'start',
                    filterable: false,
                    value: 'id',
                },
                { text: 'Name', value: 'full_name' },
                { text: 'Time Zone', value: 'time_zone' },
                { text: 'Emotions', value: 'emotion' },
                { text: 'NPS', value: 'nps' },
                { text: 'Last Active', value: 'last_active' },
                { text: 'Go to Conversation', value: 'last_active', align: 'center' },
            ],
            desserts: [
                {
                    id: 1245784,
                    full_name: 'MR Sku',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR Sku',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR Sku',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR Sku',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR Sku',
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
            this.$router.push({name: `messenger`, query: {customer: id}})
        }
    }
}
</script>
<style scoped>

</style>
