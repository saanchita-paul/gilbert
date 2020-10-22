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
                    <td>{{ item.full_name }}</td>
                    <td>
                        <div style="height: 25px; width: 25px; background-color: orange; border-radius: 3px"></div>
                    </td>
                    <td>
                        <div
                            class="mgs-sentiment"
                            :style="`backgroundColor: ${getColor(item.user_message.emotion)}`"
                        >
                            {{ item.user_message.value }}
                        </div>
                    </td>
                    <td>{{ item.location }}</td>
                    <td>{{ item.time_zone }}</td>
                    <td>{{ item.last_active }}</td>
                    <td class="d-flex justify-center">
                        <v-icon color="#006AFF" @click="openConversation(item.id)">mdi-facebook-messenger</v-icon>
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
                {text: 'Name', value: 'full_name'},
                {text: 'Sentiment', value: 'emotion'},
                {text: 'User message', value: 'user_message', align: 'center'},
                {text: 'Location', value: 'location'},
                {text: 'Timezone', value: 'time_zone'},
                {text: 'Interacted on', value: 'last_active'},
                {text: '', sortable: false, value: 'last_active', align: 'center'},
            ],
            desserts: [
                {
                    id: 1245784,
                    full_name: 'MR SKU',
                    user_message: {emotion: 'positive', value: 'booking mover'},
                    location: 'Camberwell',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR SKU',
                    user_message: {emotion: 'neutral', value: 'okay'},
                    location: 'Camberwell',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR SKU',
                    user_message: {emotion: 'positive', value: 'booking mover'},
                    location: 'Camberwell',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR SKU',
                    user_message: {emotion: 'negative', value: 'I am angry'},
                    location: 'Camberwell',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
                {
                    id: 1245784,
                    full_name: 'MR SKU',
                    user_message: {emotion: 'positive', value: 'thanks'},
                    location: 'Camberwell',
                    time_zone: 'GTM +6',
                    emotion: 24,
                    nps: 4.0,
                    last_active: '10 minutes ago',
                },
            ],
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
        }
    }
}
</script>
<style scoped>
.mgs-sentiment {
    text-align: center !important;
    padding: 5px 10px;
    color: white;
    margin: 5px !important;
    border-radius: 27px 20px 20px 0px;
}
</style>
