<template>
    <v-container>
        <page-header :breadcrumbs="getBreadcrumbs" title="Customer Details">
            <date-range-picker v-model="dateRange"/>
        </page-header>
        <v-card>
            <div class="user-card">
                <div class="user-info pt-10 pb-5 px-10">
<!--                    DETAILS-->
                    <div class="d-flex flex-row">
                        <div
                            class="user-avatar-container d-flex flex-column justify-center mr-5"
                             v-if="!!customer && !!customer.avatar"
                        >
                            <v-avatar size="100" class="ml-3">
                                <img
                                    :src="customer.avatar"
                                    alt="Customer Avatar"
                                >
                            </v-avatar>
                            <v-btn
                                class="mt-2"
                                elevation="2"
                                rounded
                                color="primary"
                            >
                                Open Chat
                            </v-btn>
                        </div>
                        <div class="user-detail-container mr-10" v-if="!!customer">
                            <div class="text-h4" v-text="customer.full_name" />
                            <div class="d-flex flex-row mb-5">
                                <div class="text-subtitle-1 mr-5">HOOD UID: {{customer.uin}}</div>
                                <div class="text-subtitle-1">Messenger ID: #{{customer.facebook_id}}</div>
                                <div class="text-subtitle-1 ml-auto mr-16 pr-1">Purchase Cycle TBC</div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="text-subtitle-1 mr-5">Email: {{customer.email}}</div>
                                <div class="text-subtitle-1">Ph: {{customer.phone}}</div>
                                <div class="text-subtitle-1 ml-auto">Est. Moving period {{movingDate}}</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="user-menu">
                    <v-tabs v-model="selectedMenuIndex">
                        <v-tab
                            exact
                            :to="{
                                name: 'customers.profile',
                                params: {customerId: customerId},
                                query: {menu: menu.route}
                            }"
                            v-for="menu in menus"
                            :key="menu.title"
                        >{{ menu.title }}
                        </v-tab>
                    </v-tabs>
                </div>
            </div>
        </v-card>
        <v-card class="mt-3">
            <div v-if="!!customer" class="text-body-1 ml-5 mb-5 pt-5 font-weight-thin" style="color: #A1A3A8">Last updated {{customer.updated_at_human}}</div>
            <component :is="getComponent" :customer="customer"></component>
        </v-card>
    </v-container>
</template>

<script>
import PageHeader from "@scripts/components/common/PageHeader";
import DateRange from "@scripts/models/DateRange";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker";
import ApplicationService from "@scripts/services/ApplicationService";
import CustomerPropertyInfo from "@scripts/components/customer/customer-profile/CustomerPropertyInfo";
import CustomerOrderInfo from "@scripts/components/customer/customer-profile/CustomerOrderInfo";
import CustomerOtherService from "@scripts/components/customer/customer-profile/CustomerOtherService";
import CustomerMovingInfo from "@scripts/components/customer/customer-profile/CustomerMovingInfo";
import CustomerConnectionInfo from "@scripts/components/customer/customer-profile/CustomerConnectionInfo";
import CustomerService from "@scripts/services/CustomerService";
import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default {
    name: "CustomerProfilePage",
    components: {
        PageHeader,
        DateRangePicker,
        CustomerPropertyInfo,
        CustomerConnectionInfo,
        CustomerMovingInfo,
        CustomerOrderInfo,
        CustomerOtherService
    },
    props: ['customerId'],
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Customer',
                    disabled: false,
                    route_name: 'dashboard',
                },
                {
                    text: 'All User',
                    disabled: false,
                    route_name: 'customers.list',
                }, {
                    text: this.customerId,
                    disabled: false,
                    route_name: 'customers.profile',
                },
            ]
        },
        getComponent() {
            if (this.$route.query.menu) {
                return this.components[this.$route.query.menu]
            }
            return this.components.property_info
        },
        movingDate() {
            return this.customer && this.customer.moving_date
                ? new DayJs(this.customer.moving_date).format(DATE_FORMAT.MOVING_DATE_DISPLAY_FORMAT)
                : '';
        }
    },
    data() {
        return {
            dateRange: new DateRange(),
            menus: ApplicationService.getUserProfileMenus(),
            selectedMenuIndex: null,
            components: ApplicationService.getUserProfileComponents(),
            customer: null
        }
    },
    methods: {
        onTabChange() {
            this.$router.push({
                name: 'customers.profile',
                params: {customerId: this.customerId},
                query: {menu: '4'}
            })
            console.log("TAB CHANGES")
        },
        async getCustomerData(customerId) {
            this.customer = await CustomerService.getCustomerDetail(customerId);
        }
    },
    watch: {
        customerId: function (newCustomerId) {
            this.getCustomerData(newCustomerId);
        }
    },
    mounted() {
        this.getCustomerData(this.customerId);
    }
}
</script>

<style scoped>
.user-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.user-detail-container {
    width: 100%;
}

.user-menu {
    width: 100%;
}
</style>
