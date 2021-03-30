<template>
    <v-card>
        <v-card-title class="widget-title pb-0">
            Customers needing urgent assistance
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="customers"
            :page.sync="pagination.page"
            :items-per-page="pagination.per_page"
            :server-items-length="pagination.total"
            class="elevation-1"
        >
            <template
                v-slot:body="{ items }"
            >
                <tbody>
                <tr
                    v-for="(item, index) in items"
                    :key="index"
                    class="py-1"
                    :class="{'error-sentiment-background': item.issue_status === 'need_assistance' || item.issue_status === 'in_progress',}"
                >
                    <td :class="{'error-sentiment-border': item.issue_status === 'need_assistance' || item.issue_status === 'in_progress', 'error-sentiment-transparen':  !(item.issue_status === 'need_assistance' || item.issue_status === 'in_progress')}">
                        <v-row align-center class="header color--text">
                            <v-col class="avatar-containner pr-2 avater-remove-growing pl-1">
                                <v-avatar>
                                    <v-img v-bind:src="item.profile_pic" :color="item.sentiment.color"  v-bind:class="{'bad': item.sentiment.text === 'BAD', 'good': item.sentiment.text === 'Good', 'neural':item.sentiment.text === 'Nuetral'}"/>
                                </v-avatar>
                            </v-col>
                            <v-col cols="7" class="pt-0 pt-5">
                                <p class="mb-0 fontweight600 font-size14 font-colorblack mb-0">{{ item.name }}</p>
                                <p class="mb-0 last-interactive font-size12 font-color-gray">Interact
                                    {{ item.last_interactive_time }}</p>
                            </v-col>
                        </v-row>
                    </td>
                    <td class="fontweight400 font-size12 font-colorblack pl-4">
                        {{ item.issue_status | mapInterventionStatus }}
                    </td>
                    <td class="fontweight400 font-size14 font-colorblack pl-4">
                        {{ item.connection_status }}
                    </td>
                    <td class="fontweight400 font-size14 font-colorblack">
                        <p small class="sentiment py-1 mb-0" v-bind:style="{backgroundColor: item.sentiment.color}">
                            <span class="font-size10 fontweight400 mb-0"
                                  :color="item.sentiment.color">{{ item.sentiment.text }}</span></p>
                    </td>
                    <td class="pl-4">
                        <p class="fontweight400 font-size14 font-colorblack mb-0">{{ item.location }}</p>
                        <!--                            <p class="font-size12 font-color-gray mb-0 ">GMT+11</p>-->

                    </td>
                    <td class="fontweight400 font-size14 font-colorblack pl-4">
                        <p class="fontweight400 font-size14 font-colorblack mb-0 pl-0"> {{ item.connection_date }}</p>
                        <p class="font-size12 font-color-gray mb-0 pl-0">{{ item.connection_time }}</p>
                    </td>
                    <td>
                        <v-btn small  @click="openProfile(item.id)" class="action-btn-ass px-2 font-size12">PROFILE</v-btn>
                        <v-btn small  @click="openConversation(item.id)" class="action-btn px-2 font-size12">CHAT
                            <v-icon>mdi-arrow-right</v-icon>
                        </v-btn>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
import CustomerService from "@scripts/services/CustomerService";
import {mapSentiment, mapSentimentColor} from "@scripts/data/SentimentColor";
import Pagination from "@scripts/models/Pagination";
import {merge} from "lodash-es";
import {mapInterventionStatus} from "@scripts/data/CustomerDataMapper";

export default {
    data() {
        return {
            search: '',
            headers: [
                {text: 'Customer Details', align: 'start', value: 'customer_details', sortable: false},
                {text: 'Issue Status', value: 'issue_status', align: 'start', sortable: false},
                {text: 'Connection Status', value: 'connection_status', align: 'center', sortable: false},
                {text: 'Sentiment', value: 'user_sentiment', align: 'center', sortable: false},
                {text: 'Location', value: 'location', align: 'start', sortable: false},
                {text: 'Connection Date', value: 'connection_date', align: 'start', sortable: false},
                {text: '', sortable: false, value: 'profile', align: 'start',},
                {text: '', sortable: false, value: 'chat', align: 'start'},
            ],
            customers: [],
            pagination: new Pagination(),
            page: 1,
            pageCount: 0,
            itemsPerPage: 0,
            total: 0
        }
    },
    watch: {
        'pagination.page'(pageNew, pageOld) {
            if (pageNew !== pageOld) {
                this.load(pageNew)
            }
        }
    },

    async mounted() {
        await this.load(this.pagination.page);
    },
    methods: {
        async load(page) {
            let response = await CustomerService.getCustomerTableData(page);
            merge(this.pagination, response.pagination)
            this.customers = response.data;
        },
        getColor(sentiment) {
            return mapSentiment(sentiment).color;
        },

        getSentimentText(sentiment) {
            return mapSentiment(sentiment).text;
        },

        openConversation(id) {
            this.$router.push({name: `helpdesk`})
        },

        openProfile(id) {
            console.log('id', id);
            this.$router.push({name: `customer.details`, params: {id: id}})
        },
    },
    filters: {
        mapInterventionStatus (value) {
            if(value) {
                return mapInterventionStatus(value);
            }
           return '';
        }
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
    color: white;
    border-radius: 4px;
    font-size: 12px !important;
}

.action-btn-ass {
    font-size: 12px !important;
    background: #828282 !important;
    color: white;
    border-radius: 4px;
}

.font-size10 {
    font-size: 10px;
}

.error-sentiment-border {
    border-left: 4px solid #E91E63;
}

.error-sentiment-transparen {
    border-left: 4px solid transparent;
}

.error-sentiment-background {
    background: #F7EAE3;
}


.sentiment {
    border-radius: 5px;
    text-align: center;
    color: white;
}

.avater-remove-growing {
    flex-grow: 0 !important;
}

.bad {
    border: 2px solid rgb(233, 30, 99);
}
.good {
    border: 2px solid rgb(76, 175, 80);

}
.neural {
    border: 2px solid rgb(189, 189, 189);

}

</style>
