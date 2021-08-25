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
                        <v-text-field label="Office Address*"
                                      @input="updateOffice"
                                      v-model = "office.address"
                                      :error-messages=" errors[0]"
                                      outlined dense></v-text-field>
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
          }
      }
    },

    methods: {
        updateOffice() {
        this.$emit('updateOffice',this.office);
      },

        updateWithProps()
        {
            this.office = this.data?.office;
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
</style>
