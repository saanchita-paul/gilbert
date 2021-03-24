<template>
    <v-card>
        <v-card-title class="font-size16 fontweight400 pb-0">
            Customers needing urgernt assistance
        </v-card-title>
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
                        :class="{'error-sentiment': error}"
                    >
                        <td :class="{'error-sentiment': error}">
                            <v-row align-center class="header color--text">
                                <v-col class="avatar-containner pr-0">
                                    <v-avatar >
                                        <v-img v-bind:src="item.profile_pic"  :color="`${getColor(item.sentiment)}`"/>
                                    </v-avatar>
                                </v-col>
                                <v-col cols="7" class="pt-0 pt-5">
                                    <p class="mb-0 fontweight600 font-size14 font-colorblack mb-0">{{item.name}}</p>
                                    <p class="mb-0 last-interactive font-size12 font-color-gray" >interact {{item.last_interactive_time}} ago</p>
                                </v-col>
                            </v-row>
                        </td>
                        <td class="fontweight400 font-size12 font-colorblack">
                            {{ item.issue_status }}
                        </td>
                        <td class="fontweight400 font-size14 font-colorblack">
                            {{ item.connection_status }}
                        </td>
                        <td class="fontweight400 font-size14 font-colorblack">
                            <v-btn small :color="`${getColor(item.sentiment)}`" ><p class="font-size10 fontweight400 mb-0">{{ item.sentiment }}</p></v-btn>
                        </td>
                        <td >
                            <p class="fontweight400 font-size14 font-colorblack mb-0">{{ item.location }}</p>
                            <p class="font-size12 font-color-gray mb-0 ">GMT+11</p>

                        </td>
                        <td class="fontweight400 font-size14 font-colorblack">
                            <p class="fontweight400 font-size14 font-colorblack mb-0"> {{ item.connection_date }}</p>
                            <p class="font-size12 font-color-gray mb-0 ">6:30 PM</p>
                        </td>
                        <td>
                            <v-btn @click="openProfile(item.id)" class="action-btn-ass">PEOPLE</v-btn>
                            <v-btn @click="openConversation(item.id)" class="action-btn">CHAT<v-icon small>mdi-arrow-right</v-icon></v-btn>
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
                {text: 'Customer Details', align: 'center', value: 'customer_details',},
                {text: 'Issue Status', value: 'issue_status',align: 'center'},
                {text: 'Connection Status', value: 'connection_status',align: 'center'},
                {text: 'Sentiment', value: 'user_sentiment', align: 'center'},
                {text: 'Location', value: 'location', align: 'center'},
                {text: 'Connection Date', value: 'connection_date', align: 'center'},
                {text: '', sortable: false, value: 'profile', align: 'center'},
                {text: '', sortable: false, value: 'chat', align: 'center'},
            ],
            customers: [],
            error: true,
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
            this.$router.push({name: `helpdesk`})
        },
        openProfile(id) {
            this.$router.push({name: `customer.details`, params:{id: id}})
        },
    },
    async mounted() {
        this.customers = await CustomerService.getCustomerTableData();
        console.log(this.customers);
    }
}
</script>
<style scoped>
.font-size14 {
    font-size: 14px;
}
.font-size12 {
    font-size: 12px;
}
.font-size16 {
    font-size: 12px;
}

.fontweight600 {
    font-weight: 600;
}
.fontweight400 {
    font-weight: 400;
}
.font-colorblack {
    color: rgba(0, 0, 0, 1);
}
.font-color-gray {
    color: rgba(197, 199, 205, 1);
}
.action-btn {
    background: linear-gradient(133.34deg, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%);
    color:white;
    border-radius: 10px;
}
.action-btn-ass {
    background: #828282 !important;
}

.font-size10{
    font-size: 10px;
}

.error-sentiment {
    border-left: 4px solid #E91E63;
    background: #F7EAE3;
}

</style>
