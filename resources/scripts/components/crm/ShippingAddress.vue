<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="700px"
        >
            <v-card>
                <ValidationObserver ref="shipping_address">
                    <v-container fluid>
                        <v-row class="section-dialogs">
                            <v-col cols="12">
                                <div class="dialogs-title">
                                    <p>Shipping Address</p>
                                </div>

                                <div class="dialogs-area pt-1">
                                    <!-- <p class="title">Service Address</p> -->
                                    <v-row>
                                        <v-col cols="12" class="py-0 mt-4" v-if="!showSearchFields">
                                            <v-row>
                                                <v-col cols="12" class="py-0">
                                                    <v-menu offset-y v-model="showMenu">
                                                        <template v-slot:activator="{ on }">
                                                            <ValidationProvider name="Shipping Address" rules="required"
                                                                                v-slot="{ errors }">
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
                                                                <v-list-item-title>
                                                                    <div class="mannualAddress" @click="selectMannual">
                                                                        Enter my address manually
                                                                    </div>
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
                                                    <ValidationProvider name="Unit No" v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Unit No"
                                                            outlined
                                                            dense
                                                            :readonly="!serviceAddress.mannual_address"
                                                            v-model="serviceAddress.unit_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street No" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street No.*"
                                                            outlined
                                                            dense
                                                            :readonly="!serviceAddress.mannual_address"
                                                            v-model="serviceAddress.street_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Street Name" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street Name.*"
                                                            outlined
                                                            dense
                                                            :readonly="!serviceAddress.mannual_address"
                                                            v-model="serviceAddress.street_name_only"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street Type" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="serviceAddress.street_type"
                                                                  :items="street_type"
                                                                  :readonly="!serviceAddress.mannual_address"
                                                                  label="Street Type*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="City/Suburb" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="City/Suburb*"
                                                            outlined
                                                            dense
                                                            :readonly="!serviceAddress.mannual_address"
                                                            v-model="serviceAddress.city"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="State/Territory" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="serviceAddress.state"
                                                                  :items="states"
                                                                  :readonly="!serviceAddress.mannual_address"
                                                                  label="State/Territory*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Postcode" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Postcode*"
                                                            outlined
                                                            dense
                                                            :readonly="!serviceAddress.mannual_address"
                                                            v-model="serviceAddress.postcode"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                            </v-row>
                                        </v-col>

                                        <v-col cols="12" class="py-0 pb-4" v-if="showSearchFields">
                                            <p class="shippingAddress" @click="shippingAddress">
                                                <v-icon small style="text-decoration: none;  padding-bottom: 4px;">
                                                    mdi-plus-circle
                                                </v-icon>
                                                <span style="text-decoration: underline;"> {{
                                                        shippingDetails.is_same ? 'Add a different shipping address' : 'Keep the shipping address same as service address'
                                                    }} </span></p>
                                        </v-col>


                                    </v-row>

                                    <!-- shipping address starts -->
                                    <template v-if="!shippingDetails.is_same && showSearchFields">
                                        <v-col style="margin: 0px; padding: 0px;" cols="12" class="pb-0 mt-2 mx-0"
                                               v-if="!showSearchFieldsShipping">
                                            <v-menu offset-y v-model="showMenu">
                                                <template v-slot:activator="{ on }">
                                                    <ValidationProvider name="Shipping Address" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Search address"
                                                            outlined
                                                            dense
                                                            placeholder="Type house address here"
                                                            append-icon="mdi-magnify"
                                                            v-model="search_address_text"
                                                            :error-messages=" errors[0]"
                                                            @keyup.native="onShippingStreetChanged"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-list v-if="searchResultShipping.length">
                                                    <v-list-item
                                                        v-for="place in searchResultShipping"
                                                        :key="place.id"
                                                        @click="onShippingAddressSelected(place)"
                                                    >
                                                        <v-list-item-title v-text="place.address_text">
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                    <v-list-item>
                                                        <v-list-item-title>
                                                            <div class="mannualAddress" @click="selectMannualShipping">
                                                                Enter my address manually
                                                            </div>
                                                        </v-list-item-title>
                                                    </v-list-item>
                                                </v-list>
                                            </v-menu>
                                        </v-col>
                                        <v-col style="margin: 0px; padding: 0px;" cols="12"
                                               v-if="showSearchFieldsShipping">
                                            <v-row>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Unit No" v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Unit No"
                                                            outlined
                                                            dense
                                                            :readonly="!shippingDetails.mannual_address"
                                                            placeholder="Unit No"
                                                            v-model="shippingDetails.unit_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street No" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street No.*"
                                                            outlined
                                                            dense
                                                            :readonly="!shippingDetails.mannual_address"
                                                            placeholder="Street No"
                                                            v-model="shippingDetails.street_number"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Street Name" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Street Name.*"
                                                            outlined
                                                            dense
                                                            :readonly="!shippingDetails.mannual_address"
                                                            placeholder="Street Name*"
                                                            v-model="shippingDetails.street_name_only"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="3" class="py-0">
                                                    <ValidationProvider name="Street Type" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="shippingDetails.street_type"
                                                                  :items="street_type"
                                                                  :readonly="!shippingDetails.mannual_address"
                                                                  label="Street Type*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="City/Suburb" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="City/Suburb*"
                                                            outlined
                                                            dense
                                                            :readonly="!shippingDetails.mannual_address"
                                                            placeholder="City/Suburb*"
                                                            v-model="shippingDetails.city"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="State/Territory" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-select outlined dense
                                                                  v-model="shippingDetails.state"
                                                                  :items="states"
                                                                  :readonly="!shippingDetails.mannual_address"
                                                                  label="State/Territory*"
                                                                  :error-messages=" errors[0]"
                                                                  placeholder="Please Select">
                                                        </v-select>
                                                    </ValidationProvider>
                                                </v-col>
                                                <v-col cols="6" class="py-0">
                                                    <ValidationProvider name="Postcode" rules="required"
                                                                        v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Postcode*"
                                                            outlined
                                                            dense
                                                            :readonly="!shippingDetails.mannual_address"
                                                            placeholder="Postcode*"
                                                            v-model="shippingDetails.postcode"
                                                            :error-messages=" errors[0]"
                                                        ></v-text-field>
                                                    </ValidationProvider>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </template>

                                    <v-col cols="12" style="margin: 0px; padding: 0px;" class="py-0"
                                           v-if="showSearchFieldsShipping && !shippingDetails.is_same">
                                        <p class="newAddress" @click="newAddressShipping"><span
                                            style="text-decoration: underline;"> I want to search for a new address </span>
                                        </p>
                                    </v-col>


                                    <!-- shipping address ends -->


                                </div>
                            </v-col>
                            <v-col cols="12" class="sticky-bottom">
                                <v-row>
                                    <v-col cols="6" class="py-0">
                                        <v-btn @click="closeServiceAddress" block>Cancel</v-btn>
                                    </v-col>
                                    <v-col cols="6" class="py-0">
                                        <v-btn @click="onSubmit" block color="primary">Save</v-btn>
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
import {merge, isEmpty} from "lodash-es";
import MapService from "@scripts/services/MapService";
import {street_type} from "@scripts/data/constants/StreetType";
import Store from '@scripts/store/index';
import ApplicationSummaryConstantService from "@scripts/services/ApplicationSummaryConstantService";

export default {
    name: "ShippingAddress",
    components: {
        Search
    },
    props: {
        dialog: {
            required: true
        },
        shippingDetails: {
            required: true
        },
        serviceAddress: {
            required: true,
        }
    },
    data() {
        return {
            currentAddress: {},
            checkbox: true,
            showMenu: false,
            showAdditionalMenu: false,
            searchResult: [],
            states: ApplicationSummaryConstantService.getStatesDD(),
            showSearchFields: true,
            searchResultShipping: [],
            showSearchFieldsShipping: true,
            search_address_text: ''
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

        this.onShippingStreetChanged = debounce(() => {
            if (this.search_address_text.length > 0) {
                MapService.getStreetAddressesByKeyword(this.search_address_text)
                    .then((data) => {
                        this.searchResultShipping = data;
                        this.showMenu = this.searchResultShipping.length > 0
                    });

            }
        }, 250);


    },
    computed: {
        street_type() {
            return street_type;
        },
    },
    mounted() {
        this.currentAddress = {...this.shippingDetails};
        this.getInitData();
    },
    methods: {
        getInitData() {
            if (this.shippingDetails.is_same) {
                this.makeItSameAddress();
            }
        },

        makeItSameAddress() {
            this.shippingDetails.unit_number = this.serviceAddress.unit_number;
            this.shippingDetails.street_number = this.serviceAddress.street_number;
            this.shippingDetails.street_name_only = this.serviceAddress.street_name_only;
            this.shippingDetails.street_name = this.serviceAddress.street_name;
            this.shippingDetails.street_type = this.serviceAddress.street_type;
            this.shippingDetails.street_address = this.serviceAddress.street_address;
            this.shippingDetails.state = this.serviceAddress.state;
            this.shippingDetails.postcode = this.serviceAddress.postcode;
            this.shippingDetails.city = this.serviceAddress.city;
            this.shippingDetails.country = this.serviceAddress.country;
            this.shippingDetails.address_text = this.serviceAddress.address_text;
        },
        shippingAddress() {
            this.shippingDetails.is_same = !this.shippingDetails.is_same;

            if (this.shippingDetails.is_same) {
                this.makeItSameAddress();
            }
        },
        selectAddress() {
            this.showSearchFields = true;
        },
        selectShippingAddress() {
            this.showSearchFieldsShipping = true;
        },
        selectMannualShipping() {
            this.showSearchFieldsShipping = true;
            this.search_address_text = null
            // this.shippingDetails.address_text = null;
            this.shippingDetails.street_address = null;
            this.shippingDetails.city = null;
            this.shippingDetails.postcode = null;
            this.shippingDetails.state = null;
            this.shippingDetails.street_number = null;
            this.shippingDetails.unit_number = null;
            this.shippingDetails.street_name = null;
            this.shippingDetails.street_name_only = null;
            this.shippingDetails.street_type = null;
            this.shippingDetails.mannual_address = true;
        },
        selectMannual() {
            this.showSearchFields = true;

            this.search_address_text = null;
            // this.shippingDetails.address_text = null;
            this.shippingDetails.street_address = null;
            this.shippingDetails.city = null;
            this.shippingDetails.postcode = null;
            this.shippingDetails.state = null;
            this.shippingDetails.state_short = null;
            this.shippingDetails.street_number = null;
            this.shippingDetails.unit_number = null;
            this.shippingDetails.street_name = null;
            this.shippingDetails.street_name_only = null;
            this.shippingDetails.street_type = null;
            this.shippingDetails.mannual_address = true;

        },
        newAddressShipping() {
            this.showSearchFieldsShipping = false;
            this.search_address_text = null;
            this.shippingDetails.mannual_address = false;
            this.shippingDetails.address_text = null;
            this.searchResultShipping = [];
        },
        newAddress() {
            this.showSearchFields = false;
            this.shippingDetails.mannual_address = false;
            this.search_address_text = null;
            // this.shippingDetails.address_text = null;
            this.searchResult = [];
        },
        closeServiceAddress() {
            if (!this.checkIfAddressIsValid()) {
                this.shippingDetails.address_text = "";
            }
            merge(this.shippingDetails, this.currentAddress);
            this.$emit('close');
        },
        onShippingAddressSelected(place) {
            this.searchResultShipping = []
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                    this.shippingDetails.unit_number = data.unit_number,
                        this.shippingDetails.street_number = data.street_number,
                        this.shippingDetails.street_name = data.street_name,
                        this.shippingDetails.street_name_only = data.street_name_only,
                        this.shippingDetails.address_text = data.address_text,
                        this.search_address_text = data.address_text,
                        this.shippingDetails.country = data.country,
                        this.shippingDetails.state = data.state,
                        this.shippingDetails.street_type = data.street_type,
                        this.shippingDetails.street_number = data.street_number,
                        this.shippingDetails.address_unit = data.address_unit,
                        this.shippingDetails.street_address = data.street_address,
                        this.shippingDetails.city = data.city,
                        this.shippingDetails.postcode = data.postcode,
                        this.selectShippingAddress();
                });
        },
        onAddressSelected(place) {
            this.searchResult = [];
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                    // this.shippingDetails = { ...this.shippingDetails, ...data }

                    this.shippingDetails.address_text = data.address_text;
                    this.shippingDetails.street_address = data.street_address;
                    this.shippingDetails.city = data.city;
                    this.shippingDetails.country = data.country;
                    this.shippingDetails.postcode = data.postcode;
                    this.shippingDetails.state = data.state;
                    this.shippingDetails.state_short = data.state_short;
                    this.shippingDetails.unit_number = data.unit_number;
                    this.shippingDetails.street_number = data.street_number;
                    this.shippingDetails.street_name = data.street_name;
                    this.shippingDetails.street_name_only = data.street_name_only;
                    this.shippingDetails.street_type = data.street_type;
                    this.shippingDetails.unit_number = data.unit_number;

                    this.selectAddress();
                });
        },
        checkIfAddressIsValid() {

            //if shippings address same not same and other required fields are not empty
            if (!this.shippingDetails.is_same &&
                (
                    this.shippingDetails.street_number == null || this.shippingDetails.street_number == "" ||
                    this.shippingDetails.street_name_only == null || this.shippingDetails.street_name_only == "" ||
                    this.shippingDetails.street_type == null || this.shippingDetails.street_type == "" ||
                    this.shippingDetails.state == null || this.shippingDetails.state == "" ||
                    this.shippingDetails.city == null || this.shippingDetails.city == "" ||
                    this.shippingDetails.postcode == null || this.shippingDetails.postcode == ""
                )) {
                return false;
            } else if (
                this.shippingDetails.street_number == null || this.shippingDetails.street_number == "" ||
                this.shippingDetails.street_name_only == null || this.shippingDetails.street_name_only == "" ||
                this.shippingDetails.street_type == null || this.shippingDetails.street_type == "" ||
                this.shippingDetails.state == null || this.shippingDetails.state == "" ||
                this.shippingDetails.city == null || this.shippingDetails.city == "" ||
                this.shippingDetails.postcode == null || this.shippingDetails.postcode == ""
            ) {
                return false;
            }
            return true;
        },
        setAddressTextAndStreetAddress() {

            if (!this.checkIfAddressIsValid()) {
                this.shippingDetails.address_text = "";
                return;
            }

            let unit_number = isEmpty(this.shippingDetails.unit_number) ? "" : this.shippingDetails.unit_number + " /";

            this.shippingDetails.street_address = unit_number + ' ' + this.shippingDetails.street_number + ' ' + this.shippingDetails.street_name_only;


            if (this.shippingDetails.mannual_address || this.shippingDetails.address_text == "" || this.shippingDetails.address_text == null) {
                let unit_number = isEmpty(this.shippingDetails.unit_number) ? "" : this.shippingDetails.unit_number + " /";

                this.shippingDetails.address_text = unit_number + ' ' + this.shippingDetails.street_number + ' ' + this.shippingDetails.street_name_only + ' ' + this.shippingDetails.street_type + ' ' + this.shippingDetails.city + ' ' + this.shippingDetails.state + ' ' + this.shippingDetails.postcode + ' ' + this.shippingDetails.country;
            }

            unit_number = isEmpty(this.shippingDetails.unit_number) ? "" : this.shippingDetails.unit_number + " /";

            this.shippingDetails.street_address = unit_number + ' ' + this.shippingDetails.street_number + ' ' + this.shippingDetails.street_name_only;

            if (this.shippingDetails.mannual_address || this.shippingDetails.address_text == "" || this.shippingDetails.address_text == null) {
                this.shippingDetails.address_text = unit_number + ' ' + this.shippingDetails.street_number + ' ' + this.shippingDetails.street_name_only + ' ' + this.shippingDetails.street_type + ' ' + this.shippingDetails.city + ' ' + this.shippingDetails.state + ' ' + this.shippingDetails.postcode + ' ' + this.shippingDetails.country;
            }
        },
        mapStreetName() {
            this.shippingDetails.street_name = this.shippingDetails.street_name_only;
        },
        checkAddressText() {
            if (this.shippingDetails.address_text == null) {
                return false;
            }
            return true;
        },
        async onSubmit() {
            console.log("onSubmit");
            // return;
            this.setAddressTextAndStreetAddress();
            this.mapStreetName();
            // if(!this.checkAddressText()) return;

            let v = await this.$refs.shipping_address.validate();
            console.log("v", v);
            if (!v) {
                return false;
            }
            this.$emit('saveAddress');
        },
    },
};
</script>

<style scoped lang="scss">
.sticky-bottom {
    position: sticky;
    bottom: 0px;
}

.mannualAddress {
    font-weight: bold;

    &:hover {
        cursor: pointer;
    }
}

.newAddress {
    font-weight: bold;

    &:hover {
        cursor: pointer;
    }
}

.shippingAddress {
    &:hover {
        cursor: pointer;
    }
}
</style>
