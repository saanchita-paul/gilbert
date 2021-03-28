<template>
    <div>
        <v-row  class="customer-list-header" color="white">
            <v-col cols="6" class="pb-0">
                <div class="search-bar px-5 pt-2">
                        <v-icon medium class="customer-list-back-button pr-5 header-icon"> mdi-arrow-left </v-icon>
                        <v-text-field label="Search leads" filled dense  prepend-inner-icon="mdi-magnify" ></v-text-field>
                        <v-icon medium class="customer-list-filter-button pl-5 header-icon trasform270"> settings_input_composite</v-icon>
                </div>
            </v-col>
        </v-row>
        <v-container>
            <CustomersNewTable v-if="isLoaded" class="mt-2"/>
        </v-container>
    </div>
</template>

<script>
import CustomersNewTable from "@scripts/components/customer/CustomersNewTable";
import CustomerService from "@scripts/services/CustomerService";

export default {
    name: "CustomerInsightPage",
    components: {CustomersNewTable},
    data() {
        return {
            customerList: [],
            isLoaded: false,
        }
    },
    mounted() {
        this.loadCustomerData();
    },
    methods: {
      async loadCustomerData () {
          this.customerList = await CustomerService.getCustomerTableData();
          this.isLoaded = true;
          console.log(this.customerList);

      }
    },
}
</script>

<style scoped>
.customer-list-header {
    background-color: white;
    display:flex;
    align-items: center;
}
.search-bar {
    display: flex;
    align-items: baseline;
}

.header-icon{
    color:#323232;
}
.v-text-field__details{
    height: 0px !important;
    min-height: 0px !important;
}

.trasform270 {
    transform: rotate(270deg);
}

</style>
