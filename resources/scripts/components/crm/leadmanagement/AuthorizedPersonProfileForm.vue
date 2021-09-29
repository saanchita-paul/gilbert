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
                            <v-col cols="12">
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
                                                v-model="authorized_person.phone"
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
    props:['dialog','authorized_person_data'],
    data()
    {
      return {
          authorized_person:{
              first_name :'',
              middle_name :'',
              last_name:'',
              email:'',
              role: '',
              phone: ''
          },
          roles:[
              {
                  value: 1,
                  text:'Enquiry Only',


              },
              {
                  value: 2,
                  text:'Fully Authorised',
              },
              {
                  value: 3,
                  text:'Financially Responsible'
              }
          ]
      }
    },
    methods:{
        submitForm() {

            this.$emit('saveAuthroizedPerson', this.authorized_person);
        },

        closeModal() {
            this.$emit('closeModal');
        },

        syncData() {
            this.authorized_person.first_name = this.authorized_person_data.first_name
            this.authorized_person.last_name = this.authorized_person_data.last_name
            this.authorized_person.email = this.authorized_person_data.email
            this.authorized_person.middle_name = this.authorized_person_data.middle_name
            this.authorized_person.phone = this.authorized_person_data.phone
            this.authorized_person.role = this.authorized_person_data.role
            this.authorized_person.id = this.authorized_person_data.id

        }
    },
    mounted() {
        this.syncData();
    }
}
</script>

<style scoped>

</style>
