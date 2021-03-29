<template>
    <v-row>
        <v-app-bar>
            <v-row align-center class="header color--text">
                <v-col class="avatar-containner pr-0">
                    <v-avatar>
                        <v-img v-bind:src="customerinfo.profile_pic" class="rejected"/>
                    </v-avatar>
                </v-col>
                <v-col cols="7" class="pt-0 pt-5">
                    <h3 class="mb-0 font-weight-bold profile-title">{{customerinfo.name}}</h3>
                    <p class="mb-0 last-interactive profile-subtitle">interact {{customerinfo.last_interactive_time}} ago</p>
                </v-col>
                <v-col cols="3" class="messenger-header">
                    <v-switch v-model="customerinfo.manualInterventionIsActive" @click="manualInterventionToggle"></v-switch>
                    <p class="messenger-header-p">Switch to Conversation</p>
                </v-col>
            </v-row>
        </v-app-bar>
        <v-col cols="12" style="height: 78vh;display: flex;">

            <v-container class="fill-height" v-if="customerMessages">
                <v-row class="fill-height pb-2">
                    <v-col>
                        <div v-for="(item, index) in customerMessages.data" :key="index"
                            :class="['d-flex flex-row align-center my-2', item.type === 'RESPONSE' ? 'justify-end': null]">
                            <template v-if="item.type === 'RESPONSE'">
                                <v-card elevation="1" class="pa-2 mr-2 expansion-header-background-active">
                                    <span  class=" mgs-text">{{ item.text_content }}</span>
                                </v-card>
                                <v-avatar v-if="item.isAvatarNeed" color="white" size="50">
                                    <img
                                        :src="item.customer.profile_pic"
                                    >
                                </v-avatar>
                            </template>

                            <template v-if="item.type === 'RECEIVE'">
                                <v-avatar v-if="item.isAvatarNeed" color="white" size="50">
                                    <img
                                        :src="item.customer.profile_pic"
                                    >
                                </v-avatar>
                                <v-card elevation="1" class="pa-2 ml-2  white">
                                    <span  class=" mgs-text">{{ item.text_content }}</span>
                                </v-card>
                            </template>
                        </div>
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

import CustomerDetails from "@scripts/models/CustomerDetails";
import CustomerService from "@scripts/services/CustomerService";
import CustomerHelpDesk from "@scripts/pages/customer/customer-profile/CustomerHelpDesk";

export default {
    props: ['customer'],
    name: "CustomerMessanger",
    data() {
        return {
            customerinfo: this.getCustomerDetails(),
            activeModel: 0,
            pageIndex: 0,
            customerList: [],
            customerMessages: null,
            customerId: this.$route.query.customerId || null
        }
    },
    components:{
        'customer-help-dest':CustomerHelpDesk
    },

    props: {
        // customerId:{
        //     required: false,
        //     type: Number
        // }
    },
    watch: {
        customerId(newId) {

        }
    },
    async mounted() {
        // await this.getCustomerDetailsData(this.customerId);
        await this.getCustomerMessages(this.customerId);
        await this.getCustomerList(this.pageIndex);
        this.loadCustomerId();
    },
    methods: {
        sendToMessenger() {
            window.open(`https://www.facebook.com/messages/t/${this.customerinfo.property_profile_id}`, "_blank");
        },

        async manualInterventionToggle() {
            await CustomerService.toggleManualIntervention(this.customerinfo.id, this.customerinfo.manualInterventionIsActive);
        },

        getCustomerDetails() {
            return new CustomerDetails();
        },

        async getCustomerDetailsData (customerId) {
            this.customerinfo = await CustomerService.getCustomerDetails(customerId);

        },

        async getCustomerList (pageIndex = 1) {
            this.customerList = await CustomerService.getCustomerList(pageIndex);
            let response = await CustomerService.getCustomerList();
            this.customerList = response.data;
            console.log('customerdata', this.customerList[0].id);
        },

        async getCustomerMessages (customerId) {
            this.customerMessages = await CustomerService.getCustomerMessages('2856');
            console.log('Data',this.customerMessages);
        },
        loadCustomerId() {
            if (!this.customerId) {
                const id = this.customerList[0]?.id;
                console.log(this.customerList, "CUSTOMER")
                if (id) {
                    this.$router.push({name: 'helpdesk', query: {customerId: id}})
                }
            }
        }
    },
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
.expansion-header-background {
    background: transparent;
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
.expand-header-info {
    font-weight: 700;
    font-size: 12px;
}

.profile_body_header {
    font-weight: 500 !important;
    font-size: 14px;
    line-height: 24px;
    color: #252733;
    padding-top: 0px;
    padding-bottom: 0px;
}

.profile-info-title {
    font-size: 12px;
    color: rgba(37, 39, 51, 1);
}

.avatar-containner {
    flex-grow: 0;
}
.body_row {
    background: rgba(227, 224, 231, 1);

}
.reason {
    background: rgba(242, 242, 242, 1);
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


</style>