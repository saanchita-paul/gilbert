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
                                    <p>Address</p>
                                </div>

                                <div class="dialogs-area pt-5">
                                    <p class="title">Service Address</p>
                                    <v-row>
                                        <v-col cols="12" class="py-0 mt-4">
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

                                        <!-- <v-col cols="12" class="mt-n12" v-if="propertyDetails.state == 'Victoria'">
                                            <v-checkbox
                                                v-model="propertyDetails.is_renovation_on"
                                                @change="changeIsBillingSame"
                                                :label="`Is renovation going on?`"
                                            ></v-checkbox>
                                        </v-col>

                                        <v-col cols="12" class="mt-n12" v-if="propertyDetails.state == 'Queensland'">
                                            <v-checkbox
                                                v-model="propertyDetails.has_electricity"
                                                :label="`Is the electricity on at the property?`"
                                            ></v-checkbox>
                                        </v-col> -->

                                        <!-- <v-col cols="6" class="py-0 mt-n4" v-if="propertyDetails.state == 'Queensland' && propertyDetails.has_electricity == false">
                                          <ValidationProvider name="Inspection Time" rules="required"  v-slot="{ errors }">
                                            <v-select outlined dense
                                                      v-model="propertyDetails.inspection_time"
                                                      :items="inspectionTimes"
                                                      placeholder="Please select"
                                                      label="Inspection Time*"
                                                      :error-messages=" errors[0]">
                                            </v-select>
                                          </ValidationProvider>
                                        </v-col> -->

                                        <v-col cols="12" class="mt-n12">
                                            <v-checkbox
                                                v-model="propertyDetails.is_billing_same"
                                                @change="changeIsBillingSame"
                                                :label="`This is same as my billing address.`"
                                            ></v-checkbox>
                                        </v-col>


                                    </v-row>


                                  <v-row v-if="!propertyDetails.is_billing_same">
                                    <p class="title pl-3" >Billing Address</p>
                                    <v-col cols="12" class="py-0">
                                      <v-row>
                                        <v-col cols="12" class="py-0">
                                          <v-menu offset-y v-model="showAdditionalMenu">
                                            <template v-slot:activator="{ on }">
                                              <v-text-field
                                                  label="Search address"
                                                  outlined
                                                  dense
                                                  placeholder="Type house address here"
                                                  append-icon="mdi-magnify"
                                                  v-model="propertyDetails.billing_address_text"
                                                  @keyup.native="onbilling_StreetChanged"
                                              ></v-text-field>
                                            </template>
                                            <v-list>
                                              <v-list-item
                                                  v-for="place in searchResult"
                                                  :key="place.place_id"
                                                  @click="onBillingAddressSelected(place)"
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
                                            v-model="propertyDetails.billing_street_address"
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
                                            v-model="propertyDetails.billing_postcode"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                      </ValidationProvider>
                                    </v-col>
                                  </v-row>
                                </div>
                            </v-col>
                          <v-col  cols="12" class="sticky-bottom">
                            <v-row >
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
import GoogleMapService from "@scripts/services/GoogleMapService";
import {isNull} from "lodash-es";
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
            ],
          // inspectionTimes:[
          //   '8AM - 1PM',
          //   '9AM - 2PM',
          //   '10AM - 3PM',
          //   '11AM - 4PM',
          //   '12AM - 5PM',
          //   '1AM - 6PM',
          // ]
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
      this.onbilling_StreetChanged = debounce(() => {
        if (this.propertyDetails.billing_address_text.length > 0) {
          GoogleMapService.getStreetAddressesByKeyword(this.propertyDetails.billing_address_text)
              .then((data) => {
                this.searchResult = data;
                this.showAdditionalMenu = this.searchResult.length > 0
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
                    this.propertyDetails.street_number = data.street_number?data.street_number:null;
                    this.propertyDetails.unit_number = data.unit_number;
                    this.propertyDetails.street_name = data.street_name;

                    if(!isNull( data.unit_number)) {
                        this.propertyDetails.street_address = data.unit_number +'/'+ data.street;
                    }
                });
        },

      onBillingAddressSelected(place) {
        GoogleMapService.getAddressDetailsByPlaceId(place.place_id)
            .then((data) => {
              this.propertyDetails.billing_address_text = data.formatted_address;
              this.propertyDetails.billing_street_address = data.street;
              this.propertyDetails.billing_city = data.city;
              this.propertyDetails.billing_postcode = data.postcode;
              this.propertyDetails.billing_state = data.state;
              this.propertyDetails.billing_street_number = data.street_number;
              this.propertyDetails.billing_unit_number = data.unit_number;
              this.propertyDetails.billing_street_name = data.street_name;

                if(!isNull( data.unit_number)) {
                    this.propertyDetails.billing_street_address = data.unit_number +'/'+ data.street;
                }

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
      },


      async onSubmit() {

          console.log(this.propertyDetails)
          // return;
          let v = await this.$refs.edit_address.validate();
          if (v) {
              // console.log()
              this.$emit('saveAddress', this.propertyDetails);
              this.$eventBus.$emit("address_updated", this.propertyDetails)
          }
          return v;
      },
    },
};
</script>

<style scoped>
.sticky-bottom{
  position: sticky;
  bottom: 0px;
}
</style>
