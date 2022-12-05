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
                                                            <ValidationProvider name="Shipping Address" rules="required"  v-slot="{ errors }">
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
                                        :readonly="!shippingDetails.mannual_address"
                                        v-model="shippingDetails.unit_number"
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
                                        :readonly="!shippingDetails.mannual_address"
                                        v-model="shippingDetails.street_number"
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
                                        :readonly="!shippingDetails.mannual_address"
                                        v-model="shippingDetails.street_name_only"
                                        :error-messages=" errors[0]"
                                    ></v-text-field>
                                </ValidationProvider>
                            </v-col>
                             <v-col cols="3" class="py-0">
                                <ValidationProvider name="Street Type" rules="required"  v-slot="{ errors }">
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
                                <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        label="City/Suburb*"
                                        outlined
                                        dense
                                        :readonly="!shippingDetails.mannual_address"
                                        v-model="shippingDetails.city"
                                        :error-messages=" errors[0]"
                                    ></v-text-field>
                                </ValidationProvider>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <ValidationProvider name="State/Territory" rules="required"  v-slot="{ errors }">
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
                                <ValidationProvider name="Postcode" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        label="Postcode*"
                                        outlined
                                        dense
                                        :readonly="!shippingDetails.mannual_address"
                                        v-model="shippingDetails.postcode"
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
                        <p class="shippingAddress" @click="shippingAddress">  <v-icon small style="text-decoration: none;  padding-bottom: 4px;"> mdi-plus-circle </v-icon> <span style="text-decoration: underline;"> {{ shippingDetails.is_shipping_same ? 'Add a different shipping address' : 'Keep the shipping address same as service address' }} </span> </p>
                    </v-col>


                                    </v-row>

         <!-- shipping address starts -->
                    <template v-if="!shippingDetails.is_shipping_same && showSearchFields">
                        <v-col  style="margin: 0px; padding: 0px;" cols="12" class="pb-0 mt-2 mx-0" v-if="!showSearchFieldsShipping">
                                    <v-menu offset-y v-model="showMenu">
                                        <template v-slot:activator="{ on }">
                                            <ValidationProvider name="Shipping Address" rules="required"  v-slot="{ errors }">
                                                <v-text-field
                                                    label="Search address"
                                                    outlined
                                                    dense
                                                    placeholder="Type house address here"
                                                    append-icon="mdi-magnify"
                                                    v-model="shipping_search_address_text"
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
                                            <v-list-item-title >
                                                <div class="mannualAddress" @click="selectMannualShipping"> Enter my address manually </div>
                                            </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                        </v-col>
                        <v-col style="margin: 0px; padding: 0px;"  cols="12" v-if="showSearchFieldsShipping">
                            <v-row>
                                <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Unit No"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Unit No"
                                            outlined
                                            dense
                                            :readonly="!shippingDetails.shipping_mannual_address"
                                            placeholder="Unit No"
                                            v-model="shippingDetails.shipping_unit_number"
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
                                            :readonly="!shippingDetails.shipping_mannual_address"
                                            placeholder="Street No"
                                            v-model="shippingDetails.shipping_street_number"
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
                                            :readonly="!shippingDetails.shipping_mannual_address"
                                            placeholder="Street Name*"
                                            v-model="shippingDetails.shipping_street_name_only"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                 <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Street Type" rules="required" v-slot="{ errors }">
                                        <v-select outlined dense
                                                  v-model="shippingDetails.shipping_street_type"
                                                  :items="street_type"
                                                  :readonly="!shippingDetails.shipping_mannual_address"
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
                                            :readonly="!shippingDetails.shipping_mannual_address"
                                            placeholder="City/Suburb*"
                                            v-model="shippingDetails.shipping_city"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="State/Territory" rules="required"  v-slot="{ errors }">
                                        <v-select outlined dense
                                                  v-model="shippingDetails.shipping_state"
                                                  :items="states"
                                                  :readonly="!shippingDetails.shipping_mannual_address"
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
                                            :readonly="!shippingDetails.shipping_mannual_address"
                                            placeholder="Postcode*"
                                            v-model="shippingDetails.shipping_postcode"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                        </v-col>
                    </template>

                    <v-col cols="12"  style="margin: 0px; padding: 0px;"  class="py-0" v-if="showSearchFieldsShipping && !shippingDetails.is_shipping_same">
                        <p class="newAddress" @click="newAddressShipping"> <span style="text-decoration: underline;"> I want to search for a new address </span> </p>
                    </v-col>


                <!-- shipping address ends -->



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
import {isNull, merge} from "lodash-es";
import {isEmpty} from "lodash-es";
import STATES_DD from "@scripts/data/constants/STATES_DD";
import MapService from "@scripts/services/MapService";
import { street_type } from "@scripts/data/constants/StreetType";
import Store from '@scripts/store/index';

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
          searchResultShipping: [],
          showSearchFieldsShipping: true,
          isShippingAddressSame: true,
          search_address_text: '',
          shipping_search_address_text: ''
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
            if (this.shipping_search_address_text.length > 0) {
            MapService.getStreetAddressesByKeyword(this.shipping_search_address_text)
                .then((data)=>{
                    this.searchResultShipping = data;
                    this.showMenu = this.searchResultShipping.length > 0
                });

            }
        }, 250);


    },
    computed: {
      street_type(){
          return street_type;
      },
    },
    mounted() {
        this.currentAddress = {...this.shippingDetails} ;
    },
    methods: {
        shippingAddress(){
            // this.isShippingAddressSame = !this.isShippingAddressSame;
            this.shippingDetails.is_shipping_same = !this.shippingDetails.is_shipping_same;
        },
        selectAddress(){
            this.showSearchFields = true;
        },
        selectShippingAddress(){
            this.showSearchFieldsShipping = true;
        },
        selectMannualShipping(){
            this.showSearchFieldsShipping = true;
            this.shipping_search_address_text = null
            // this.shippingDetails.shipping_address_text = null;
            this.shippingDetails.shipping_street_address = null;
            this.shippingDetails.shipping_city = null;
            this.shippingDetails.shipping_postcode = null;
            this.shippingDetails.shipping_state = null;
            this.shippingDetails.shipping_street_number = null;
            this.shippingDetails.shipping_unit_number = null;
            this.shippingDetails.shipping_street_name = null;
            this.shippingDetails.shipping_street_name_only = null;
            this.shippingDetails.shipping_street_type = null;
            this.shippingDetails.shipping_mannual_address = true;
        },
        selectMannual(){
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
        newAddressShipping(){
            this.showSearchFieldsShipping = false;
            this.shipping_search_address_text = null;
            this.shippingDetails.shipping_mannual_address = false;
            this.shippingDetails.shipping_address_text = null;
            this.searchResultShipping = [];
        },
        newAddress(){
            this.showSearchFields = false;
            this.shippingDetails.mannual_address = false;
            this.search_address_text = null;
            // this.shippingDetails.address_text = null;
            this.searchResult = [];
        },
        closeServiceAddress() {
            if(!this.checkIfAddressIsValid())
            {
                this.shippingDetails.address_text = "";
            }
            merge(this.shippingDetails, this.currentAddress);
            this.$emit('close');
        },
        onShippingAddressSelected(place) {
            this.searchResultShipping = []
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                        this.shippingDetails.shipping_unit_number = data.unit_number,
                        this.shippingDetails.shipping_street_number = data.street_number,
                        this.shippingDetails.shipping_street_name = data.street_name,
                        this.shippingDetails.shipping_street_name_only = data.street_name_only,
                        this.shippingDetails.shipping_address_text = data.address_text,
                        this.shipping_search_address_text = data.address_text,
                        this.shippingDetails.shipping_country = data.country,
                        this.shippingDetails.shipping_state = data.state,
                        this.shippingDetails.shipping_street_type = data.street_type,
                        this.shippingDetails.shipping_street_number = data.street_number,
                        this.shippingDetails.shipping_address_unit = data.address_unit,
                        this.shippingDetails.shipping_street_address = data.street_address,
                        this.shippingDetails.shipping_city = data.city,
                        this.shippingDetails.shipping_postcode = data.postcode,
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
                    // this.shippingDetails.street_number = data.street_number?data.street_number:null;
                    this.shippingDetails.unit_number = data.unit_number;
                    this.shippingDetails.street_number = data.street_number;
                    this.shippingDetails.street_name = data.street_name;
                    this.shippingDetails.street_name_only = data.street_name_only;
                    this.shippingDetails.street_type = data.street_type;
                    this.shippingDetails.unit_number = data.unit_number;

                    this.selectAddress();
                });
        },
      changeIsShippingSame()
      {
        this.shippingDetails.shipping_address_text = '';
        this.shippingDetails.shipping_street_address = '';
        this.shippingDetails.shipping_city = '';
        this.shippingDetails.shipping_postcode = '';
        this.shippingDetails.shipping_state = '';
        this.shippingDetails.shipping_street_number = '';
        this.shippingDetails.shipping_unit_number ='';
        this.shippingDetails.shipping_street_name = '';
        this.shippingDetails.shipping_street_name_only = '';
      },

      checkIfAddressIsValid(){

          //if shippings address same not same and other required fields are not empty
          if(!this.shippingDetails.is_shipping_same &&
            (
              this.shippingDetails.shipping_street_number == null || this.shippingDetails.shipping_street_number == "" ||
              this.shippingDetails.shipping_street_name_only == null || this.shippingDetails.shipping_street_name_only == "" ||
              this.shippingDetails.shipping_street_type == null || this.shippingDetails.shipping_street_type == "" ||
              this.shippingDetails.shipping_state == null || this.shippingDetails.shipping_state == "" ||
              this.shippingDetails.shipping_city == null || this.shippingDetails.shipping_city == "" ||
              this.shippingDetails.shipping_postcode == null || this.shippingDetails.shipping_postcode == ""
            ))
          {
              return false;
          } else if(
              this.shippingDetails.street_number == null || this.shippingDetails.street_number == "" ||
              this.shippingDetails.street_name_only == null || this.shippingDetails.street_name_only == "" ||
              this.shippingDetails.street_type == null || this.shippingDetails.street_type == "" ||
              this.shippingDetails.state == null || this.shippingDetails.state == "" ||
              this.shippingDetails.city == null || this.shippingDetails.city == "" ||
              this.shippingDetails.postcode == null || this.shippingDetails.postcode == ""
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
             this.shippingDetails.address_text = "";
             return;
          }

          let unit_number = isEmpty(this.shippingDetails.shipping_unit_number) ? "" : this.shippingDetails.shipping_unit_number + " /";

          this.shippingDetails.shipping_street_address = unit_number + ' ' + this.shippingDetails.shipping_street_number + ' ' + this.shippingDetails.shipping_street_name_only;


          if(this.shippingDetails.shipping_mannual_address || this.shippingDetails.shipping_address_text == "" || this.shippingDetails.shipping_address_text == null )
          {
              let unit_number = isEmpty(this.shippingDetails.shipping_unit_number) ? "" : this.shippingDetails.shipping_unit_number + " /";

              this.shippingDetails.shipping_address_text = unit_number + ' ' + this.shippingDetails.shipping_street_number + ' ' + this.shippingDetails.shipping_street_name_only + ' ' + this.shippingDetails.shipping_street_type + ' ' + this.shippingDetails.shipping_city + ' ' + this.shippingDetails.shipping_state + ' ' + this.shippingDetails.shipping_postcode + ' ' + this.shippingDetails.shipping_country;
          }

          unit_number = isEmpty(this.shippingDetails.unit_number) ? "" : this.shippingDetails.unit_number + " /";

          this.shippingDetails.street_address = unit_number + ' ' + this.shippingDetails.street_number + ' ' + this.shippingDetails.street_name_only;

          if(this.shippingDetails.mannual_address || this.shippingDetails.address_text == "" || this.shippingDetails.address_text == null ){
              this.shippingDetails.address_text = unit_number + ' ' + this.shippingDetails.street_number + ' ' + this.shippingDetails.street_name_only + ' ' + this.shippingDetails.street_type + ' ' + this.shippingDetails.city + ' ' + this.shippingDetails.state + ' ' + this.shippingDetails.postcode + ' ' + this.shippingDetails.country ;
          }
      },
     mapStreetName(){
         this.shippingDetails.street_name = this.shippingDetails.street_name_only;
         this.shippingDetails.shipping_street_name = this.shippingDetails.shipping_street_name_only;
     },
     checkAddressText()
     {
        if(this.shippingDetails.address_text  == null){
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
              // console.log()
              this.$emit('saveAddress', this.shippingDetails);
              this.$eventBus.$emit("address_updated", this.shippingDetails);
              Store.commit('setInvalidAddress', false);
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
.shippingAddress{
    &:hover{
        cursor: pointer;
    }
}
</style>
