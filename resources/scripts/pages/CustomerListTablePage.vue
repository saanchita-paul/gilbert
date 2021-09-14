<template>
    <div>
        <v-row  class="customer-list-header" color="white">
            <v-col cols="6" class="pb-0">
                <div class="search-bar px-5 pt-0 pb-0">
<!--                        <v-icon medium class="customer-list-back-button pr-5 pb-3 header-icon"> mdi-arrow-left </v-icon>-->
                        <v-img src="/assets/images/icons/Back.svg" max-width="24px" class="mb-3"/>
                        <v-text-field label="Search leads" filled dense  prepend-inner-icon="mdi-magnify" class="max-height-70 pr-5" v-if="false"></v-text-field>
                        <v-icon medium class="customer-list-filter-button pa-3 header-icon trasform270" v-if="false"> settings_input_composite</v-icon>
                </div>
            </v-col>
        </v-row>
        <v-container fluid class="pt-9">
            <CustomersNewTable v-if="isLoaded"/>
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

.trasform270 {
    transform: rotate(270deg);
    background: #F2F2F2;
    border-radius: 4px !important;
    position: relative;
    top: 5px;
}
.max-height-70{
    max-height: 70px !important;
}

</style>
