<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="700px"
        >
            <v-card>
                <ValidationObserver ref="edit_address">
                    <v-container fluid>
                        <v-row class="section-dialogs">
                            <v-col cols="12">
                                <div class="dialogs-title">
                                    <p>Connection Address</p>
                                </div>

                                <div class="dialogs-area pt-1">
                                    <!-- <p class="title">Service Address</p> -->
                                    <v-row>
                                        <v-col cols="12" class="py-0 mt-4">
                                            <v-row>
                                                <v-col cols="12" class="py-0" v-if="!showSearchFields">
                                                    <v-menu offset-y v-model="showMenu">
                                                        <template v-slot:activator="{ on }">
                                                            <ValidationProvider name="Service Address" rules="required"  v-slot="{ errors }">
                                                                <v-text-field
                                                                    label="Search address"
                                                                    outlined
                                                                    dense
                                                                    placeholder="Type house address here"
                                                                    append-icon="mdi-magnify"
                                                                    :error-messages=" errors[0]"
                                                                    v-model="search_address_text"
                                                                    @keyup.native="onStreetChanged"
                                                                ></v-text-field>
                                                            </ValidationProvider>
                                                        </template>
                                                        <v-list v-if="searchResult.length">
                                                            <v-list-item
                                                                v-for="place in searchResult"
                                                                :key="place.id"
                                                                @click="onAddressSelected(place)"
                                                            >
                                                                <v-list-item-title v-text="place.address_text">
                                                                </v-list-item-title>
                                                            </v-list-item>
                                                            <v-list-item>
                                                                <v-list-item-title >
                                                                    <div class="mannualAddress" @click="selectMannual"> Enter my address manually </div>
                                                                </v-list-item-title>
                                                            </v-list-item>
                                                        </v-list>
                                                    </v-menu>
                                                </v-col>
                                            </v-row>
                                        </v-col>

                                        <v-col cols="12" v-if="showSearchFields">
                                            <v-row>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Unit No"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Unit No"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.mannual_address"
                                                            v-model="propertyDetails.unit_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street No" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street No.*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.mannual_address"
                                                            v-model="propertyDetails.street_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Street Name" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street Name.*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.mannual_address"
                                                            v-model="propertyDetails.street_name_only"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street Type" rules="required"  v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="propertyDetails.street_type"
                                                                  :items="street_type"
                                                                  :readonly="!propertyDetails.mannual_address"
                                                                  label="Street Type*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="City/Suburb*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.mannual_address"
                                                            v-model="propertyDetails.city"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="State/Territory" rules="required"  v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="propertyDetails.state"
                                                                  :items="states"
                                                                  item-value="text"
                                                                  item-text="text"
                                                                  :readonly="!propertyDetails.mannual_address"
                                                                  label="State/Territory*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Postcode" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Postcode*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.mannual_address"
                                                            v-model="propertyDetails.postcode"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                            </v-row>
                                        </v-col>

                                        <v-col cols="12" class="py-0" v-if="showSearchFields">
                                            <p class="newAddress" @click="newAddress"> <span style="text-decoration: underline;"> I want to search for a new address </span> </p>
                                        </v-col>

                                        <v-col cols="12" class="py-0 pb-4" v-if="showSearchFields">
                                            <p class="billingAddress" @click="billingAddress">  <v-icon small style="text-decoration: none;  padding-bottom: 4px;"> mdi-plus-circle </v-icon> <span style="text-decoration: underline;"> {{ propertyDetails.is_billing_same ? 'Add a different billing address' : 'Keep the billing address same as service address' }} </span> </p>
                                        </v-col>


                                    </v-row>

                                    <!-- billing address starts -->
                                    <template v-if="!propertyDetails.is_billing_same && showSearchFields">
                                        <v-col  style="margin: 0px; padding: 0px;" cols="12" class="pb-0 mt-2 mx-0" v-if="!showSearchFieldsBilling">
                                            <v-menu offset-y v-model="showMenu">
                                                <template v-slot:activator="{ on }">
                                                    <ValidationProvider name="Billing Address" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Search address"
                                                            outlined
                                                            dense
                                                            placeholder="Type house address here"
                                                            append-icon="mdi-magnify"
                                                            v-model="billing_search_address_text"
                                                            :error-messages=" errors[0]"
                                                            @keyup.native="onBillingStreetChanged"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-list v-if="searchResultBilling.length">
                                                    <v-list-item
                                                        v-for="place in searchResultBilling"
                                                        :key="place.id"
                                                        @click="onBillingAddressSelected(place)"
                                                    >
                                                        <v-list-item-title v-text="place.address_text">
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title >
                                                            <div class="mannualAddress" @click="selectMannualBilling"> Enter my address manually </div>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                        </v-col>
                                        <v-col style="margin: 0px; padding: 0px;"  cols="12" v-if="showSearchFieldsBilling">
                                            <v-row>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Unit No"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Unit No"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.billing_mannual_address"
                                                            placeholder="Unit No"
                                                            v-model="propertyDetails.billing_unit_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street No" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street No.*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.billing_mannual_address"
                                                            placeholder="Street No"
                                                            v-model="propertyDetails.billing_street_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Street Name" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street Name.*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.billing_mannual_address"
                                                            placeholder="Street Name*"
                                                            v-model="propertyDetails.billing_street_name_only"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street Type" rules="required" v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="propertyDetails.billing_street_type"
                                                                  :items="street_type"
                                                                  :readonly="!propertyDetails.billing_mannual_address"
                                                                  label="Street Type*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="City/Suburb*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.billing_mannual_address"
                                                            placeholder="City/Suburb*"
                                                            v-model="propertyDetails.billing_city"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="State/Territory" rules="required"  v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="propertyDetails.billing_state"
                                                                  :items="states"
                                                                  item-text="text"
                                                                  item-value="text"
                                                                  :readonly="!propertyDetails.billing_mannual_address"
                                                                  label="State/Territory*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Postcode" rules="required"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Postcode*"
                                                            outlined
                                                            dense
                                                            :readonly="!propertyDetails.billing_mannual_address"
                                                            placeholder="Postcode*"
                                                            v-model="propertyDetails.billing_postcode"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </template>

                                    <v-col cols="12"  style="margin: 0px; padding: 0px;"  class="py-0" v-if="showSearchFieldsBilling && !propertyDetails.is_billing_same">
                                        <p class="newAddress" @click="newAddressBilling"> <span style="text-decoration: underline;"> I want to search for a new address </span> </p>
                                    </v-col>


<!--                                     billing address ends -->



                                </div>
                            </v-col>
                            <v-col  cols="12" class="sticky-bottom">
                                <v-row >
                                    <v-col cols="6" class="py-0">
                                        <v-btn @click="closeServiceAddress" block>Cancel</v-btn>
                                    </v-col>
                                    <v-col cols="6" class="py-0">
                                        <v-btn @click="onSubmit" block color="primary" :loading="saveButtonLoader">Save</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </ValidationObserver>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import debounce from "lodash-es/debounce";
import GoogleMapService from "@scripts/services/GoogleMapService";
import {isNull, merge} from "lodash-es";
import {isEmpty} from "lodash-es";
import STATES_DD from "@scripts/data/constants/STATES_DD";
import MapService from "@scripts/services/MapService";
import { street_type } from "@scripts/data/constants/StreetType";
import Store from '@scripts/store/index';

export default {
    name: "GgbService",
    components: {
        Search
    },
    props: {
        dialog: {
            required: true
        },
        propertyDetails: {
            required: true
        },
        saveButtonLoader : {
            required : true
        }
    },
    data () {
        return {
            currentAddress: {},
            checkbox: true,
            showMenu: false,
            showAdditionalMenu: false,
            searchResult: [],
            states: [
                {text: 'NSW', value: 'New South Wales'},
                {text: 'VIC', value: 'Victoria'},
                {text: 'QLD', value: 'Queensland'},
                {text: 'SA', value: 'South Australia'},
                {text: 'NT', value: 'Northern Territory'},
                {text: 'TAS', value: 'Tasmania'},
                {text: 'ACT', value: 'Australian Capital Territory'},
                {text: 'WA', value: 'Western Australia'},
            ],
            showSearchFields: true,
            searchResultBilling: [],
            showSearchFieldsBilling: true,
            isBillingAddressSame: true,
            search_address_text: '',
            billing_search_address_text: '',
            street_type: street_type
        }
    },
    created() {
        this.onStreetChanged = debounce(() => {
            if (this.search_address_text.length > 0) {
                MapService.getStreetAddressesByKeyword(this.search_address_text)
                    .then((data) => {
                        this.searchResult = data;
                        this.showMenu = this.searchResult.length > 0
                    });
            }
        }, 250);

        this.onBillingStreetChanged = debounce(() => {
            if (this.billing_search_address_text.length > 0) {
                MapService.getStreetAddressesByKeyword(this.billing_search_address_text)
                    .then((data)=>{
                        this.searchResultBilling = data;
                        this.showMenu = this.searchResultBilling.length > 0
                    });

            }
        }, 250);

    },

    mounted() {
        this.currentAddress = {...this.propertyDetails} ;
    },
    methods: {
        billingAddress(){
            // this.isBillingAddressSame = !this.isBillingAddressSame;
            this.propertyDetails.is_billing_same = !this.propertyDetails.is_billing_same;
        },
        selectAddress(){
            this.showSearchFields = true;
        },
        selectBillingAddress(){
            this.showSearchFieldsBilling = true;
        },
        selectMannualBilling(){
            this.showSearchFieldsBilling = true;
            this.billing_search_address_text = null
            // this.propertyDetails.billing_address_text = null;
            this.propertyDetails.billing_street_address = null;
            this.propertyDetails.billing_city = null;
            this.propertyDetails.billing_postcode = null;
            this.propertyDetails.billing_state = null;
            this.propertyDetails.billing_street_number = null;
            this.propertyDetails.billing_unit_number = null;
            this.propertyDetails.billing_street_name = null;
            this.propertyDetails.billing_street_name_only = null;
            this.propertyDetails.billing_street_type = null;
            this.propertyDetails.billing_mannual_address = true;
        },
        selectMannual(){
            this.showSearchFields = true;
            this.search_address_text = null;
            this.propertyDetails.street_address = null;
            this.propertyDetails.city = null;
            this.propertyDetails.postcode = null;
            this.propertyDetails.state = null;
            this.propertyDetails.state_short = null;
            this.propertyDetails.street_number = null;
            this.propertyDetails.unit_number = null;
            this.propertyDetails.street_name = null;
            this.propertyDetails.street_name_only = null;
            this.propertyDetails.street_type = null;
            this.propertyDetails.mannual_address = true;

        },
        newAddressBilling(){
            this.showSearchFieldsBilling = false;
            this.billing_search_address_text = null;
            this.propertyDetails.billing_mannual_address = false;
            this.propertyDetails.billing_address_text = null;
            this.searchResultBilling = [];
        },
        newAddress(){
            this.showSearchFields = false;
            this.propertyDetails.mannual_address = false;
            this.search_address_text = null;
            this.searchResult = [];
        },
        closeServiceAddress() {
            this.$emit('close');
            if(!this.checkIfAddressIsValid())
            {
                this.propertyDetails.address_text = "";
            }
            merge(this.propertyDetails, this.currentAddress);
            this.$emit('close');
        },
        onBillingAddressSelected(place) {
            this.searchResultBilling = []
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                    this.propertyDetails.billing_unit_number = data.unit_number,
                        this.propertyDetails.billing_street_number = data.street_number,
                        this.propertyDetails.billing_street_name = data.street_name,
                        this.propertyDetails.billing_street_name_only = data.street_name_only,
                        this.propertyDetails.billing_address_text = data.address_text,
                        this.billing_search_address_text = data.address_text,
                        this.propertyDetails.billing_country = data.country,
                        this.propertyDetails.billing_state = data.state,
                        this.propertyDetails.billing_street_type = data.street_type,
                        this.propertyDetails.billing_street_number = data.street_number,
                        this.propertyDetails.billing_address_unit = data.address_unit,
                        this.propertyDetails.billing_street_address = data.street_address,
                        this.propertyDetails.billing_city = data.city,
                        this.propertyDetails.billing_postcode = data.postcode,
                        this.selectBillingAddress();
                });
        },
        onAddressSelected(place) {
            this.searchResult = [];
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                    // this.propertyDetails = { ...this.propertyDetails, ...data }
                    this.st
                    this.propertyDetails.address_text = data.address_text;
                    this.propertyDetails.street_address = data.street_address;
                    this.propertyDetails.city = data.city;
                    this.propertyDetails.country = data.country;
                    this.propertyDetails.postcode = data.postcode;
                    this.propertyDetails.state = data.state;
                    this.propertyDetails.state_short = data.state_short;
                    // this.propertyDetails.street_number = data.street_number?data.street_number:null;
                    this.propertyDetails.unit_number = data.unit_number;
                    this.propertyDetails.street_number = data.street_number;
                    this.propertyDetails.street_name = data.street_name;
                    this.propertyDetails.street_name_only = data.street_name_only;
                    this.propertyDetails.street_type = data.street_type;
                    this.propertyDetails.unit_number = data.unit_number;

                    this.selectAddress();
                });
        },
        changeIsBillingSame()
        {
            this.propertyDetails.billing_address_text = '';
            this.propertyDetails.billing_street_address = '';
            this.propertyDetails.billing_city = '';
            this.propertyDetails.billing_postcode = '';
            this.propertyDetails.billing_state = '';
            this.propertyDetails.billing_street_number = '';
            this.propertyDetails.billing_unit_number ='';
            this.propertyDetails.billing_street_name = '';
            this.propertyDetails.billing_street_name_only = '';
        },

        checkIfAddressIsValid(){

            //if billings address same not same and other required fields are not empty
            if(!this.propertyDetails.is_billing_same &&
                (
                    this.propertyDetails.billing_street_number == null || this.propertyDetails.billing_street_number == "" ||
                    this.propertyDetails.billing_street_name_only == null || this.propertyDetails.billing_street_name_only == "" ||
                    this.propertyDetails.billing_street_type == null || this.propertyDetails.billing_street_type == "" ||
                    this.propertyDetails.billing_state == null || this.propertyDetails.billing_state == "" ||
                    this.propertyDetails.billing_city == null || this.propertyDetails.billing_city == "" ||
                    this.propertyDetails.billing_postcode == null || this.propertyDetails.billing_postcode == ""
                ))
            {
                return false;
            } else if(
                this.propertyDetails.street_number == null || this.propertyDetails.street_number == "" ||
                this.propertyDetails.street_name_only == null || this.propertyDetails.street_name_only == "" ||
                this.propertyDetails.street_type == null || this.propertyDetails.street_type == "" ||
                this.propertyDetails.state == null || this.propertyDetails.state == "" ||
                this.propertyDetails.city == null || this.propertyDetails.city == "" ||
                this.propertyDetails.postcode == null || this.propertyDetails.postcode == ""
            )
            {
                return false;
            }
            return true;
        },

        setAddressTextAndStreetAddress()
        {

            if(!this.checkIfAddressIsValid())
            {
                this.propertyDetails.address_text = "";
                return;
            }

            let unit_number = isEmpty(this.propertyDetails.billing_unit_number) ? "" : this.propertyDetails.billing_unit_number + " /";

            this.propertyDetails.billing_street_address = unit_number + ' ' + this.propertyDetails.billing_street_number + ' ' + this.propertyDetails.billing_street_name_only;


            if(this.propertyDetails.billing_mannual_address || this.propertyDetails.billing_address_text == "" || this.propertyDetails.billing_address_text == null )
            {
                let unit_number = isEmpty(this.propertyDetails.billing_unit_number) ? "" : this.propertyDetails.billing_unit_number + " /";

                this.propertyDetails.billing_address_text = unit_number + ' ' + this.propertyDetails.billing_street_number + ' ' + this.propertyDetails.billing_street_name_only + ' ' + this.propertyDetails.billing_street_type + ' ' + this.propertyDetails.billing_city + ' ' + this.propertyDetails.billing_state + ' ' + this.propertyDetails.billing_postcode + ' ' + this.propertyDetails.billing_country;
            }

            unit_number = isEmpty(this.propertyDetails.unit_number) ? "" : this.propertyDetails.unit_number + " /";

            this.propertyDetails.street_address = unit_number + ' ' + this.propertyDetails.street_number + ' ' + this.propertyDetails.street_name_only;

            if(this.propertyDetails.mannual_address || this.propertyDetails.address_text == "" || this.propertyDetails.address_text == null ){
                this.propertyDetails.address_text = unit_number + ' ' + this.propertyDetails.street_number + ' ' + this.propertyDetails.street_name_only + ' ' + this.propertyDetails.street_type + ' ' + this.propertyDetails.city + ' ' + this.propertyDetails.state + ' ' + this.propertyDetails.postcode + ' ' + this.propertyDetails.country ;
            }
        },
        mapStreetName(){
            this.propertyDetails.street_name = this.propertyDetails.street_name_only;
            this.propertyDetails.billing_street_name = this.propertyDetails.billing_street_name_only;
        },
        checkAddressText()
        {
            if(this.propertyDetails.address_text  == null){
                return false;
            }
            return true;
        },
        async onSubmit()
        {
            // return;
            this.setAddressTextAndStreetAddress();
            this.mapStreetName();
            // if(!this.checkAddressText()) return;

            let v = await this.$refs.edit_address.validate();
            if (v) {
                this.$emit('saveAddress', this.propertyDetails);
            }
            return v;
        },
    },
};
</script>

<style scoped lang="scss">
.sticky-bottom{
    position: sticky;
    bottom: 0px;
}

.mannualAddress{
    font-weight: bold;
    &:hover{
        cursor: pointer;
    }
}
.newAddress{
    font-weight: bold;
    &:hover{
        cursor: pointer;
    }
}
.billingAddress{
    &:hover{
        cursor: pointer;
    }
}
</style>
