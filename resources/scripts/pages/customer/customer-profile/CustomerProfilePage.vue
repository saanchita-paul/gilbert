<template>
    <v-container>
        <page-header :breadcrumbs="getBreadcrumbs" title="Customer Details">
            <date-range-picker v-model="dateRange"/>
        </page-header>
        <v-card>
            <div class="user-card">
                <div class="user-info pt-10 pb-5 px-10">
<!--                    DETAILS-->
                    <v-avatar v-if="!!customer.avatar">
                        <img
                            :src="customer.avatar"
                            alt="Customer Avatar"
                        >
                    </v-avatar>
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
            <component :is="getComponent"></component>
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

.user-menu {
    width: 100%;
}
</style>
