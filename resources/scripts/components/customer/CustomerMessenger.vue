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

            <v-container class="fill-height" v-if="customerMessages">
                <v-row class="fill-height pb-2">
                    <v-col>
                        <div v-for="(item, index) in customerMessages.data" :key="index"
                             :class="['d-flex flex-row align-center my-2', item.type === 'RESPONSE' ? 'justify-end': null]">
                            <template v-if="item.type === 'RESPONSE'">
                                <v-card elevation="1" class="pa-2 mr-2 expansion-header-background-active message-card">
                                    <span  class=" mgs-text">{{ item.text_content }}</span>
                                </v-card>
                                <v-avatar v-if="item.isAvatarNeed" color="white" size="45">

                                </v-avatar>
                            </template>

                            <template v-if="item.type === 'RECEIVE'">
                                <v-avatar v-if="item.isAvatarNeed" color="white" size="45">
                                    <img :src="item.customer.profile_pic">
                                </v-avatar>
                                <v-card elevation="1" class="pa-2 ml-2 white message-card">
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
import CustomerService from "@scripts/services/CustomerService";

export default {
    props: ['customer'],
    name: "CustomerMessanger",
    data() {
        return {
            customerMessages: null,
        }
    },
    async mounted() {
        await this.getCustomerMessages(this.customer.id);
    },
    methods: {
        sendToMessenger() {
            window.open(`https://www.facebook.com/messages/t/${this.customer.property_profile_id}`, "_blank");
        },

        async manualInterventionToggle() {
            await CustomerService.toggleManualIntervention(this.customer.id, this.customer.manualInterventionIsActive);
        },

        async getCustomerMessages (customerId) {
            this.customerMessages = await CustomerService.getCustomerMessages(customerId);
            console.log('Customer Messages',this.customerMessages);
        },
    },
    watch: {
        customer () {
            this.getCustomerMessages(this.customer.id);
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

</style>
