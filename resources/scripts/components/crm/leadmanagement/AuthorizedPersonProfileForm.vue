<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <ValidationObserver>
            <v-card>
                <ValidationObserver ref="edit_address">
                    <v-container fluid>
                        <v-row class="section-dialogs">
                            <v-col cols="12">
                                <div class="dialogs-title">
                                    <p>Authorised Person</p>
                                </div>
                                <div class="dialogs-area pt-5">
                                    <p class="title">Secondary Account Holder</p>
                                </div>
                            </v-col>
                            <v-col cols="10">
                                <v-row >
                                    <v-col cols="12">
                                        <ValidationProvider name="First Name" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                label="First Name*"
                                                v-model="authorized_person.first_name"
                                                dense
                                                hide-details="auto"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12">
                                        <ValidationProvider name="Middle Name" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                v-model="authorized_person.middle_name"
                                                label="Middle Name*"
                                                dense
                                                hide-details="auto"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12">
                                        <ValidationProvider name="Last Name" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                v-model="authorized_person.last_name"
                                                outlined
                                                dense
                                                label="Last Name*"
                                                hide-details="auto"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12">
                                        <ValidationProvider name="Email address" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                v-model="authorized_person.email"
                                                label="Email address*"
                                                dense
                                                hide-details="auto"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12">
                                        <ValidationProvider name="Contact Number" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                v-model="authorized_person.contact"
                                                label="Contact Number(optional)"
                                                dense
                                                hide-details="auto"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12">
                                        <ValidationProvider name="Authorised Person's role" rules="required"  v-slot="{ errors }">
                                            <v-select
                                                outlined dense hide-details="auto"
                                                :items="roles"
                                                item-text="text"
                                                item-value="value"
                                                v-model="authorized_person.role"
                                                :error-messages=" errors[0]"
                                                placeholder="Mr">
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>

                            </v-col>
                            <v-col  cols="12">
                                <v-row >
                                    <v-col cols="6" class="py-0">
                                        <v-btn @click="closeModal" block>Cancel</v-btn>
                                    </v-col>
                                    <v-col cols="6" class="py-0">
                                        <v-btn @click="submitForm" block color="primary">Save</v-btn>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-container>
                </ValidationObserver>
            </v-card>
            </ValidationObserver>
        </v-dialog>
    </v-row>

</template>

<script>
export default {
name: "AuthorizedPersonProfileForm",
    props:['dialog'],
    data()
    {
      return {
          authorized_person:{
              first_name :'',
              middle_name :'',
              last_name:'',
              email:'',
              role: '',
              contact: ''
          },
          roles:[
              {
                  value: 'enquiry_only',
                  text:'Enquiry Only',


              },
              {
                  value:'fully_authoried',
                  text:'Fully Authorised',
              },
              {
                  value:'financially_responsible',
                  text:'Financially Responsible'
              }
          ]
      }
    },
    methods:{
        submitForm() {

        },
        closeModal() {
            this.$emit('closeModal');
        }
    }
}
</script>

<style scoped>

</style>
