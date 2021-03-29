<template>
    <v-app>
        <div v-if="isLoaded">
            <v-row>
                <v-col  cols="6" class="pt-0">
                    <v-row>
                        <v-app-bar>
                            <v-icon medium class="customer-list-back-button pr-5 header-icon"> mdi-arrow-left </v-icon>
                            <v-text-field label="Search leads" filled dense hide-details prepend-inner-icon="mdi-magnify" class="max-height-70 pr-5"></v-text-field>
                        </v-app-bar>
                    </v-row>
                    <v-row>
                        <v-col cols="12"  class="pt-0 pr-0 customer-list">
                            <v-expansion-panels v-model="activeModel">
                                <v-expansion-panel
                                    v-for="(item,i) in customerList"
                                    :key="i"
                                >
                                    <v-expansion-panel-header @click="onPanelClicked(i, item.id)"  class="py-0" v-bind:class="{ 'expansion-header-background-active ': activeModel === i }">
                                        <template v-slot:actions>
                                            <v-icon v-bind:class="{ 'white': activeModel === i }">
                                                mdi-menu-down
                                            </v-icon>
                                        </template>
                                        <template>
                                            <v-row align-center class="header color--text" v-if="activeModel === i">
                                                <v-col class="avatar-containner pr-0">
                                                    <v-avatar>
                                                        <v-img v-bind:src="item.profile_pic" class="rejected"/>
                                                    </v-avatar>
                                                </v-col>
                                                <v-col cols="8" class="pt-1 pr-2">
                                                    <h3 class="mb-0 font-weight-bold profile-title">{{item.name}}</h3>
                                                    <p class="mb-0 last-interactive profile-subtitle">Interact {{item.last_interactive_time}}</p>
                                                </v-col>
                                                <v-col cols="6">
                                                    <p class="mb-0 expand-header-info">HOOD UID: {{item.hood_uid}}</p>
                                                    <p class="mb-0 expand-header-info">Messenger ID: {{item.messager_id}}</p>
                                                </v-col>
                                                <v-col cols="6" >
                                                    <p class="mb-0 expand-header-info">Email: {{item.email}}</p>
                                                    <p class="mb-0 expand-header-info">Ph: {{item.ph}}</p>
                                                </v-col>
                                                <v-col cols="8">
                                                    <p class="mb-0 expand-header-info">Billing Preference: {{item.billing_preference}}</p>
                                                </v-col>
                                                <v-col >
                                                    <v-btn  rounded small color="white primary--text">View Profile</v-btn>
                                                </v-col>

                                            </v-row>
                                            <v-row align-center class="header color--text" v-else @click="closePanel">
                                                <v-col class="avatar-containner pr-0">
                                                    <v-avatar>
                                                        <v-img v-bind:src="item.profile_pic" class="rejected"/>
                                                    </v-avatar>
                                                </v-col>
                                                <v-col cols="10" class="pt-0 pt-5">
                                                    <h3 class="mb-0 font-weight-bold profile-title">{{item.name}}</h3>
                                                    <p class="mb-0 last-interactive profile-subtitle">Interact {{item.last_interactive_time}}</p>
                                                </v-col>
                                            </v-row>
                                        </template>

                                    </v-expansion-panel-header>
                                    <v-expansion-panel-content class="pa-0 body-bg">
                                        <CustomerHelpDesk :customer="customerinfo" />
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="6" class="pt-0" style="max-height: 100vh;overflow: auto">
                    <CustomerMessenger :customer="customerinfo"></CustomerMessenger>
                </v-col>
            </v-row>
        </div>
    </v-app>
</template>

<script>

import CustomerDetails from "@scripts/models/CustomerDetails";
import CustomerService from "@scripts/services/CustomerService";
import CustomerHelpDesk from "@scripts/pages/customer/customer-profile/CustomerHelpDesk";
import CustomerMessenger from "@scripts/components/customer/CustomerMessenger";
import ApplicationService from "@scripts/services/ApplicationService";

export default {
    name: "CustomerList",
    data() {
        return {
            customerinfo: this.getCustomerDetails(),
            isLoaded: false,
            activeModel: null,
            pageIndex: 0,
            customerList: [],
            customerMessages: null,
            customerId: this.$route.query.customerId || null
        }
    },
    components:{
        CustomerHelpDesk,
        CustomerMessenger
    },

    props: {
    },
    watch: {
        '$route': {
            handler() {
                this.customerId = this.$route.query.customerId
                this.load();
            },
            deep: true
        },
    },
    async mounted() {
        await this.getCustomerList(this.pageIndex);
        await this.load();
        this.isLoaded = true;
    },
    methods: {
        async load() {
            await this.loadCustomer();
            await this.setActiveModel();
        },
        setActiveModel() {
            if (this.customerId) {
                const index = this.customerList.findIndex(customer => customer?.id.toString() === this.customerId?.toString())
                this.activeModel = index === -1 ? null : index
            }
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
        },

        async getCustomerMessages (customerId) {
            this.customerMessages = await CustomerService.getCustomerMessages(customerId);
        },
        async loadCustomer() {
            if (!this.customerId) {
                const id = this.customerList[0]?.id;
                if (id) {
                    await this.$router.push({name: 'helpdesk', query: {customerId: id}})
                }
            } else {
                await this.getCustomerDetailsData(this.customerId);
            }
        },
        onPanelClicked(index, customerId) {
            if (this.activeModel !== index) {
                this.$router.push({name: 'helpdesk', query: { customerId, test: ApplicationService.getRandomString() }})
            }
        },
        closePanel() {
            this.activeModel = null;
            console.log('CLOSE PANEL', this.activeModel)
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
.customer-list {
    max-height: 100vh;
    overflow: auto
}

.customer-list .v-expansion-panel::before {
    box-shadow: none;
}

</style>
