<template>
    <v-row>
        <v-app-bar>
            <v-row align-center class="header color--text">
                <v-col class="avatar-containner pr-0">
                    <v-avatar>
                        <v-img v-bind:src="customer.profile_pic" class="rejected"/>
                    </v-avatar>
                </v-col>
                <v-col cols="7" class="pt-0 pt-5">
                    <h3 class="mb-0 font-weight-bold profile-title">{{customer.name}}</h3>
                    <p class="mb-0 last-interactive profile-subtitle">Interact {{customer.last_interaction}}</p>
                </v-col>
                <v-col cols="3" class="messenger-header">
                    <v-switch v-model="customer.manualInterventionIsActive" @click="manualInterventionToggle"></v-switch>
                    <p class="messenger-header-p">Switch to Conversation</p>
                </v-col>
            </v-row>
        </v-app-bar>
        <v-col cols="12" style="height: 78vh;display: flex;">

            <v-container class="fill-height">
                <v-row class="fill-height pb-2">
                    <v-col cols='12'>
                        <div v-for="(item, index) in customerMessages" :key="index"
                            :class="['d-flex flex-row align-center my-2', item.type === 'RESPONSE' ? 'justify-end': null]">
                            <template v-if="item.type === 'RESPONSE'">
                                <v-card elevation="1" class="pa-2 mr-2 expansion-header-background-active message-card">
                                    <span  class=" mgs-text">{{ item.text_content }}</span>
                                     <p v-if="item.isAvatarNeed" class="message-time-right">{{item.created_at}}</p>
                                </v-card>
                                <v-avatar v-if="item.isAvatarNeed" color="white" size="45" class="avater-alignment-right">

                                </v-avatar>
                            </template>

                            <template v-if="item.type === 'RECEIVE' || item.type === 'STANDBY'">
                                <v-avatar v-if="item.isAvatarNeed" color="white" size="45" class="avater-alignment-left">
                                    <img :src="item.customer.profile_pic">
                                </v-avatar>
                                <v-card elevation="1" class="pa-2 ml-2 white message-card" v-bind:class="{'ml-13':!item.isAvatarNeed}">
                                    <span  class=" mgs-text">{{ item.text_content }}</span>
                                    <p v-if="item.isAvatarNeed" class="message-time-left">{{item.created_at}}</p>
                                </v-card>
                            </template>
                        </div>
                    </v-col>
                    <v-col sm="12" v-if="hasMessages">
                    <infinite-loading  @infinite="infiniteHandler"
                                        spinner="bubbles">
                        <div slot="no-more">No more result</div>
                        <div slot="no-results">No Messages found</div>
                    </infinite-loading>
                    </v-col>

                </v-row>
                <v-btn @click="sendToMessenger" class="move-facebook-messagenger text-lowercase">
                    <span class="pr-3">take me to messenger</span><v-icon class="pr-3">mdi-facebook-messenger</v-icon><v-icon class="pr-0">east</v-icon>
                </v-btn>
            </v-container>

        </v-col>
    </v-row>

</template>

<script>
import CustomerService from "@scripts/services/CustomerService";
import InfiniteLoading from "vue-infinite-loading";
import Pagination from "@scripts/models/Pagination";
import {merge} from "lodash-es";

export default {
    props: ['customer'],
    name: "CustomerMessanger",
    data() {
        return {
            customerMessages: [],
            pageIndex: 0,
            pagination: new Pagination()
        }
    },
    components:{
        InfiniteLoading
    },
    computed: {
        hasMessages() {
            return this.customerMessages.length > 0;
        }
    },
    async mounted() {
        await this.getCustomerMessages(this.customer.id, this.pagination.page);
    },
    methods: {
        sendToMessenger() {
            let facebookPageUrl = process.env.MIX_FACEBOOK_PAGE_URL || 'https://www.facebook.com/Dev-Hood-1924197607612551/inbox';
            window.open(`${facebookPageUrl}`, "_blank");
        },

        async manualInterventionToggle() {
            let manualInterventionRequire = this.customer.manualInterventionIsActive ? 1 : 0;
            await CustomerService.toggleManualIntervention(this.customer.id, manualInterventionRequire);
        },

        async getCustomerMessages (customerId, page = 1) {
            const response = await CustomerService.getCustomerMessages(customerId, page);
            merge(this.pagination, response.pagination)
            this.customerMessages = [...this.customerMessages, ...response.data];
        },
        async infiniteHandler($state) {
            if (this.pagination.page < this.pagination.page_count) {
                await this.getCustomerMessages(this.customer.id, ++this.pagination.page);
                $state.loaded();
            } else {
                $state.complete();
            }
        }
    },
    watch: {
        customer () {
            this.customerMessages = [];
            this.pagination = new Pagination();
            this.getCustomerMessages(this.customer.id, this.pagination.page);
        }
    }
}
</script>

<style scoped>
body {
    font-family: "Roboto" !important;
}
.header {
    display: flex;
    align-items: center;
    line-height: 28px;
    font-size: 16px;
}

.expansion-header-background-active {
    background: linear-gradient(to right bottom, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%);
    color:white;
}

.profile-title {
    font-size: 14px !important;
    font-weight: 500 !important;
    line-height: 28px;
}

.profile-subtitle {
    font-size: 12px;
    font-weight: 400 !important;
    line-height: 14px;
}

.avatar-containner {
    flex-grow: 0;
}

.rejected {
    border: 1px solid red;
    color:red !important;
}
.rejected-text {
    color:red !important;
}
.messenger-header {
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
}
.move-facebook-messagenger {
    border:2px solid white;
    background: #2F80ED !important;
    border-radius: 100px;
    color:white;
    font-size: 12px;
    font-weight: 500;
    padding:8px;
    position:fixed;
    right:15px;
    bottom:15px
}

.messenger-header-p {
    font-size: 12px;
    line-height: normal;
}
.mgs-text {
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
}
.message-card {
    max-width: 260px;
}
.avater-alignment-right {
    align-self: flex-end;
    position: relative;
    top: 27px;
}
.avater-alignment-left {
    align-self: flex-end;
    position: relative;
    top: 22px;
}
.message-time-right {
    position: absolute;
    text-align: end;
    bottom: -38px;
    right: 0px;
    font-size: 10px;
    color: rgba(130, 130, 130, 1);
    min-width: 200px;
}
.message-time-left {
    position: absolute;
    left:0px;
    bottom: -38px;
    font-size: 10px;
    color: rgba(130, 130, 130, 1);
    min-width: 200px;
}

</style>
