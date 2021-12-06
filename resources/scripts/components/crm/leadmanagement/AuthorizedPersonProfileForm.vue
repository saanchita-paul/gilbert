<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <ValidationObserver>
            <v-card>
                <ValidationObserver ref="edit_authority">
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

<!--                                    <v-col cols="12">-->
<!--                                    <ValidationProvider name="Title" rules="required"  v-slot="{ errors }">-->
<!--                                        <v-select-->
<!--                                            outlined dense hide-details="auto"-->
<!--                                            :items="titlesDD"-->
<!--                                            v-model="authorized_person.title"-->
<!--                                            :error-messages=" errors[0]"-->
<!--                                            label="Title"-->
<!--                                            placeholder="Mr">-->
<!--                                        </v-select>-->
<!--                                    </ValidationProvider>-->
<!--                                    </v-col>-->


                                    <v-col cols="12">
                                        <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                                            <v-select
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="titlesDD"
                                                v-model="authorized_person.title"
                                                :error-messages="errors[0]"
                                                placeholder="Please choose one"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
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
                                        <ValidationProvider name="Middle Name"   v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                v-model="authorized_person.middle_name"
                                                label="Middle Name"
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
                                        <ValidationProvider name="Email address" rules="required|email"  v-slot="{ errors }">
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
                                        <ValidationProvider name="Contact Number" rules="cv-phone|length:10"  v-slot="{ errors }">
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
                                        <ValidationProvider name="Date Of Birth" rules="required"  v-slot="{ errors }">
                                            <v-menu
                                                v-model="showAuthoritydob"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on, attrs }">

                                                    <ValidationProvider name="Date Of Birth" rules="required|valid-date"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Date Of Birth*"
                                                            placeholder="DD/MM/YYYY"
                                                            outlined
                                                            dense
                                                            v-model="authorized_person.dob"
                                                            v-bind="attrs"
                                                            :error-messages=" errors[0]"
                                                            hide-details="auto"

                                                        >

                                                            <template slot="append">
                                                                <v-icon  v-on="on">mdi-calendar</v-icon>
                                                            </template>

                                                        </v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-date-picker v-model="authorized_person_dob"
                                                               @input="showAuthoritydob = false"></v-date-picker>
                                            </v-menu>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12">
                                        <ValidationProvider name="Authorised Person's role"  v-slot="{ errors }">
                                            <v-select
                                                outlined dense hide-details="auto"
                                                :items="roles"
                                                item-text="text"
                                                label="Authorised Person's role"
                                                placeholder="Authorised Person's role"
                                                item-value="value"
                                                v-model="authorized_person.role"
                                                :error-messages=" errors[0]"
                                                >
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
import {isNull} from "lodash-es";
import DayJs from "dayjs";
import dayjs from "dayjs";

export default {
name: "AuthorizedPersonProfileForm",
    props:['dialog','authorized_person_data','leadId'],
    data()
    {
      return {
          authorized_person:{
              title :'',
              first_name :'',
              middle_name :'',
              last_name:'',
              email:'',
              role: '',
              phone: '',
              id:'',
              connection_application_id: ''
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
          ],
          authorized_person_dob:  (new DayJs((new Date()).setFullYear(2000))).format('YYYY-MM-DD'),
          showAuthoritydob: false,
          titlesDD:[
              'Mr.','Mrs.','Ms.','Miss','Dr.'
          ],
      }
    },
    methods:{
       async submitForm() {
            let v = await this.$refs.edit_authority.validate();
            if(!v) return
            this.$emit('saveAuthroizedPerson', this.authorized_person);
        },

        closeModal() {
            this.$emit('closeModal');
        },

        syncData() {
            this.authorized_person.connection_application_id = this.leadId;
            if(isNull(this.authorized_person_data)) return;
            this.authorized_person.title = this.authorized_person_data.title;
            this.authorized_person.first_name = this.authorized_person_data.first_name;
            this.authorized_person.last_name = this.authorized_person_data.last_name;
            this.authorized_person.email = this.authorized_person_data.email;
            this.authorized_person.dob = !this.authorized_person_data.dob ? '' : dayjs(this.authorized_person_data.dob).format('DD/MM/YYYY');
            this.authorized_person.middle_name = this.authorized_person_data.middle_name;
            this.authorized_person.phone = this.authorized_person_data.phone;
            this.authorized_person.role = this.authorized_person_data.role;
            this.authorized_person.id = this.authorized_person_data.id
            this.authorized_person.connection_application_id = this.authorized_person_data.connection_application_id
        }
    },

    watch:{
        authorized_person_dob() {
            this.authorized_person.dob = (new DayJs(this.authorized_person_dob).format('DD/MM/YYYY'));
        },
    },
    mounted() {
        this.syncData();
    }
}
</script>

<style scoped>

</style>
