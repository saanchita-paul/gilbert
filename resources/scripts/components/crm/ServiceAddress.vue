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
                                    <p>Service Address</p>
                                </div>

                                <div class="dialogs-area pt-5">
                                    <p class="title">Address</p>
                                    <v-row>
                                        <v-col cols="12" class="py-0">
                                            <v-row>
                                                <v-col cols="12" class="py-0">
                                                    <v-menu offset-y v-model="showMenu">
                                                        <template v-slot:activator="{ on }">
                                                            <v-text-field
                                                                label="Search address"
                                                                outlined
                                                                dense
                                                                placeholder="Type house address here"
                                                                append-icon="mdi-magnify"
                                                                v-model="propertyDetails.address_text"
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
                                        </v-col>
                                        <v-col cols="6" class="py-0">
                                            <ValidationProvider name="Address" rules="required"  v-slot="{ errors }">
                                                <v-text-field
                                                    label="Address*"
                                                    outlined
                                                    dense
                                                    v-model="propertyDetails.street_address"
                                                    :error-messages=" errors[0]"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="6" class="py-0">
                                            <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                                                <v-text-field
                                                    label="City/Suburb*"
                                                    outlined
                                                    dense
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
                                                          label="State/Territory*"
                                                          :error-messages=" errors[0]">
                                                </v-select>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="6" class="py-0">
                                            <ValidationProvider name="Postcode" rules="required"  v-slot="{ errors }">
                                                <v-text-field
                                                    label="Postcode*"
                                                    outlined
                                                    dense
                                                    v-model="propertyDetails.postcode"
                                                    :error-messages=" errors[0]"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12" >
                                            <v-checkbox
                                                v-model="propertyDetails.is_billing_same"
                                                :label="`Is this the same as your billing address?`"
                                            ></v-checkbox>
                                        </v-col>

                                        <v-col cols="6" class="py-0">
                                           <v-btn @click="closeServiceAddress" block>Cancel</v-btn>
                                        </v-col>
                                        <v-col cols="6" class="py-0">
                                           <v-btn @click="onSubmit" block color="primary">Save</v-btn>
                                        </v-col>

                                    </v-row>

                                </div>
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
export default {
  name: "ServiceAddress",
  components: {
        Search
    },
    props: {
        dialog: {
            required: true
        },
        propertyDetails: {
            required: true
        }
    },
    data () {
        return {
            checkbox: true,
            showMenu: false,
            searchResult: [],
            states: [
                {text: 'NSW', value: 'New South Wales'},
                {text: 'VIC', value: 'Victoria'},
                {text: 'QLD', value: 'Queensland'},
                {text: 'SA', value: 'South Australia'},
                {text: 'NT', value: 'Northern Territory'},
                {text: 'TAS', value: 'Tasmania'},
                {text: 'ACT', value: 'Australian Capital Territory'},
            ],
        }
    },
    created() {
        this.onStreetChanged = debounce(() => {
            if (this.propertyDetails.address_text.length > 0) {
                GoogleMapService.getStreetAddressesByKeyword(this.propertyDetails.address_text)
                    .then((data) => {
                        this.searchResult = data;
                        this.showMenu = this.searchResult.length > 0
                    });
            }
        }, 250);

    },
    methods: {
        closeServiceAddress() {
            this.$emit('close');
        },
        onAddressSelected(place) {
            GoogleMapService.getAddressDetailsByPlaceId(place.place_id)
                .then((data) => {
                    this.propertyDetails.address_text = data.formatted_address;
                    this.propertyDetails.street_address = data.street;
                    this.propertyDetails.city = data.city;
                    this.propertyDetails.postcode = data.postcode;
                    this.propertyDetails.state = data.state;
                });
        },
        async onSubmit() {
            let v = await this.$refs.edit_address.validate();
            if (v) {
                this.$emit('saveAddress', this.propertyDetails);
            }
            return v;
        },
    }
};
</script>

