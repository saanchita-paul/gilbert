<template>
    <div class="pa-2 mx-4" >
        <v-row class="mx-2" style=" min-height: 35px;">
            <v-spacer></v-spacer>
            <v-btn v-show="isFiltered" @click="clearFilter" right>
                <v-icon small>mdi-close</v-icon>
                Clear filters
            </v-btn>
        </v-row>
        <v-row>
            <v-col  class="pb-0">
                <ValidationProvider name="Customer Id">
                    <v-text-field
                        label="Customer Id"
                        type="number"
                        outlined
                        dense
                        placeholder="Customer Id"
                        v-model="customer.id"
                    ></v-text-field>
                </ValidationProvider>
            </v-col>
            <v-col  class="pb-0">
                <ValidationProvider name="Customer Name">
                    <v-text-field
                        label="Customer Name*"
                        outlined
                        dense
                        placeholder="Customer Full Name"
                        v-model="customer.customer_name"
                    ></v-text-field>
                </ValidationProvider>
            </v-col>
            <v-col  class="pb-0">
                <v-menu offset-y v-model="showMenu">
                <template v-slot:activator="{ on }">
                    <v-text-field
                        label="Search address"
                        outlined
                        dense
                        placeholder="Type house address here"
                        append-icon="mdi-magnify"
                        v-model="address_text"
                        @keyup.native="onStreetChanged"
                    ></v-text-field>
                </template>
                <v-list>
                    <v-list-item
                        v-for="place in searchResult"
                        :key="place.place_id"
                        @click="onAddressSelected(place)"
                    >
                        <v-list-item-title v-text="place.description">
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-col>
        </v-row>
    </div>
</template>

<script>
import GoogleMapService from "@scripts/services/GoogleMapService";
import debounce from "lodash-es/debounce";
import {omit} from "lodash-es";
import {getStateKey} from "@scripts/data/constants/STATES";

export default {
name: "SearchCustomer",
    data() {
        return {
            customer: {
                address_text: '',
                street_address: '',
                city: '',
                postcode: '',
                state: '',
                street_number: '',
                unit_number: '',
                street_name: '',
                customer_name: '',
                customer_id: '',
                id: ''
            },
            searchResult: [],
            address_text: '',
            showMenu:false
        }
    },
    computed: {
        isFiltered() {
            return this.customer.customer_name || this.customer.id || this.customer.address_text;
        }
    },

    created() {
        this.onStreetChanged = debounce(() => {
            if (this.address_text.length > 0) {
                GoogleMapService.getStreetAddressesByKeyword(this.address_text)
                    .then((data) => {
                        this.searchResult = data;
                        this.showMenu = this.searchResult.length > 0
                    });
            }
        }, 250);

    },
    methods: {
        onAddressSelected(place) {
            GoogleMapService.getAddressDetailsByPlaceId(place.place_id)
                .then((data) => {
                    this.customer.address_text = data.formatted_address;
                    this.address_text = data.formatted_address;
                    this.customer.street_address = data.street;
                    this.customer.city = data.city;
                    this.customer.postcode = data.postcode;
                    this.customer.state = getStateKey(data.state);
                    this.customer.street_number = data.street_number;
                    this.customer.unit_number = data.unit_number;
                    this.customer.street_name = data.street_name? data.street_name.split(' ')[0]: '';
                    // this.mapToModel(data)
                });
        },
        clearFilter() {
            this.customer =   {
                address_text: '',
                    street_address: '',
                    city: '',
                    postcode: '',
                    state: '',
                    street_number: '',
                    unit_number: '',
                    street_name: '',
                    customer_name: '',
                    id: ''
            };
            this.address_text = '';
        }
    },

    watch: {
        customer: {
            handler () {
                this.$emit('fetchCustomer', this.customer);
            },
            deep: true,
        },
    },
}
</script>

<style scoped>

</style>
