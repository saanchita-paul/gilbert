<template>
          <v-row>
            <v-col class="section-dialogs" cols="12">
              <div class="dialogs-title">
                <p>Let’s setup a new office</p>
              </div>

              <div class="dialogs-area">
                <p class="title">Office Details</p>
                  <ValidationProvider name="Title" rules="required"  v-slot="{ errors }">
                    <v-text-field v-model = "office.title" @input="updateOffice"
                      label="Office Name Location*"
                      outlined
                      dense
                      :error-messages=" errors[0]"
                    ></v-text-field>
                  </ValidationProvider>
                  <ValidationProvider name="address" rules="required"  v-slot="{ errors }">
                      <v-menu offset-y v-model="showMenu">
                          <template v-slot:activator="{ on }">
                        <v-text-field label="Office Address*"
                                      @input="updateOffice"
                                      v-model = "office.address"
                                      :error-messages=" errors[0]"
                                      outlined dense
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
                  </ValidationProvider>
                  <ValidationProvider name="Contact" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                      <v-text-field
                        v-model = "office.contact"
                        @input="updateOffice"
                        label="Contact Phone Number*"
                        :error-messages=" errors[0]"
                      outlined
                      dense
                    ></v-text-field>
                  </ValidationProvider>
                  <ValidationProvider name="Email" rules="required|email"  v-slot="{ errors }">
                <v-text-field
                    @input="updateOffice"
                    v-model = "office.email"
                  label="Contact Email Address*"
                    :error-messages=" errors[0]"
                  outlined
                  dense
                ></v-text-field>
                  </ValidationProvider>
                  <ValidationProvider name="Abn" rules="cv-phone"  v-slot="{ errors }">
                    <v-text-field  v-model = "office.abn" @input="updateOffice"
                                   label="ABN (Optional)"
                                   outlined dense
                                   :error-messages=" errors[0]"
                    ></v-text-field>
                  </ValidationProvider>
              </div>
            </v-col>
          </v-row>
</template>

<script>
import debounce from "lodash-es/debounce";
import GoogleMapService from "@scripts/services/GoogleMapService";

export default {
  name: "OfficeDetails",
    props:['data'],
    data() {
      return {
          office: {
              id: null,
              title: '',
              contact: '',
              email: '',
              abn: '',
              address: ''
          },
          showMenu: false,
          searchResult: [],
      }
    },

    created() {
        this.onStreetChanged = debounce(() => {
            if (this.office.address.length > 0) {
                GoogleMapService.getStreetAddressesByKeyword(this.office.address)
                    .then((data) => {
                        this.searchResult = data;
                        this.showMenu = this.searchResult.length > 0
                    });
            }
        }, 250);

    },

    methods: {
        updateOffice() {
        this.$emit('updateOffice',this.office);
      },

        updateWithProps()
        {
            this.office = this.data?.office;
        },
        onAddressSelected(place)
        {
            GoogleMapService.getAddressDetailsByPlaceId(place.place_id)
                .then((data) => {
                    this.office.address = data.formatted_address;
                });
        }



    },

    mounted() {
     // this.updateOffice();
     this.updateWithProps();
    },

    watch: {
      agency() {
          this.office = this.agency.office;
      }
    },

};
</script>

<style scoped>
.v-menu__content{
    top:307px !important;
}
</style>
