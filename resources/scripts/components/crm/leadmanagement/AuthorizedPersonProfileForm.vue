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
                                        <ValidationProvider rules="required" name="Authorised Person's role"  v-slot="{ errors }">
                                            <v-select
                                                outlined dense hide-details="auto"
                                                :items="roles"
                                                item-text="text"
                                                label="Authorised Person's role *"
                                                placeholder="Authorised Person's role *"
                                                item-value="value"
                                                v-model="authorized_person.role"
                                                :error-messages=" errors[0]"
                                                >
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12">
                                        <ValidationProvider name="Id Type" rules="" v-slot="{ errors }">
                                            <v-select outlined dense
                                                      v-model="authorized_person.identification_type"
                                                      :items="idenficationTypeDD"
                                                      item-text="text"
                                                      item-value="value"
                                                      :label="'Id Type'"
                                                      :error-messages=" errors[0]"
                                                      hide-details="auto"
                                                      placeholder="Please select one">
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                    <template v-if="authorized_person.identification_type === 3">
                                        <v-col cols="12" >
                                            <ValidationProvider rules="" name="Medicare Card Number" v-slot="{ errors }">
                                                <v-text-field
                                                    :error-messages="errors[0]"
                                                    v-model="authorized_person.card_number"
                                                    outlined
                                                    dense
                                                    placeholder=" "
                                                    :label="`Medicare Card Number`"
                                                    hide-details="auto"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12">
                                            <ValidationProvider
                                                name="Special Number"
                                                rules=""
                                                v-slot="{ errors }"
                                            >
                                                <v-select
                                                    v-model="authorized_person.special_number"
                                                    :error-messages="errors[0]"
                                                    :label="`Special Number`"
                                                    placeholder="Please select"
                                                    :items="specialNumberDD"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12" >
                                            <v-menu
                                                v-model="showAuthIdExpireDate"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                                hide-details="auto"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <ValidationProvider
                                                        name="Expiry Date"
                                                        rules="medicare-date|medi-expire"
                                                        v-slot="{ errors }"
                                                    >
                                                        <v-text-field
                                                            placeholder="MM/YY"
                                                            :label="`Expiry Date`"
                                                            outlined
                                                            dense
                                                            v-model="authorized_person.expire_date"
                                                            v-bind="attrs"
                                                            :error-messages="errors[0]"
                                                            hide-details="auto"
                                                        >
                                                            <template slot="append">
                                                                <v-icon v-on="on">mdi-calendar</v-icon>
                                                            </template>
                                                        </v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-date-picker
                                                    v-model="expire_date"
                                                    @input="showAuthIdExpireDate = false"
                                                    type="month"
                                                    :min="minExpiredate"
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                        <v-col cols="12">
                                            <ValidationProvider
                                                name="Card Colour"
                                                rules=""
                                                v-slot="{ errors }"
                                            >
                                                <v-select
                                                    v-model="authorized_person.card_color"
                                                    placeholder="Please select"
                                                    :label="`Card Colour`"
                                                    item-text="text"
                                                    item-value="value"
                                                    :items="colorDD"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </v-col>
                                    </template>
                                    <template v-if="authorized_person.identification_type === 1">
                                        <v-col cols="12" >
                                            <ValidationProvider rules="" name="Passport Number" v-slot="{ errors }">
                                                <v-text-field
                                                    :error-messages="errors[0]"
                                                    v-model="authorized_person.card_number"
                                                    outlined
                                                    dense
                                                    placeholder=" "
                                                    hide-details="auto"
                                                    :label="`Passport Number`"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12" >
                                            <ValidationProvider rules="" name="Issuing Country" v-slot="{ errors }">
                                                <v-text-field
                                                    :error-messages="errors[0]"
                                                    v-model="authorized_person.country"
                                                    outlined
                                                    dense
                                                    placeholder=" "
                                                    hide-details="auto"
                                                    :label="`Issuing Country`"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12" >
                                            <v-menu
                                                v-model="showAuthIdExpireDate"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                                hide-details="auto"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <ValidationProvider
                                                        name="Expiry Date"
                                                        rules="valid-date"
                                                        v-slot="{ errors }"
                                                    >
                                                        <v-text-field
                                                            placeholder="DD/MM/YYYY"
                                                            :label="`Expiry Date`"
                                                            outlined
                                                            dense
                                                            v-model="authorized_person.expire_date"
                                                            v-bind="attrs"
                                                            :error-messages="errors[0]"
                                                            hide-details="auto"
                                                        >
                                                            <template slot="append">
                                                                <v-icon v-on="on">mdi-calendar</v-icon>
                                                            </template>
                                                        </v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-date-picker
                                                    v-model="expire_date"
                                                    @input="showAuthIdExpireDate = false"
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </template>
                                    <template v-if="authorized_person.identification_type === 2">
                                        <v-col cols="12">
                                            <ValidationProvider rules="" name="Driver’s License*" v-slot="{ errors }">
                                                <v-text-field
                                                    :error-messages="errors[0]"
                                                    v-model="authorized_person.card_number"
                                                    outlined
                                                    dense
                                                    placeholder=" "
                                                    hide-details="auto"
                                                    :label="`Driver’s License`"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12" >
                                            <ValidationProvider
                                                name="State"
                                                rules=""
                                                v-slot="{ errors }"
                                            >
                                                <v-select
                                                    v-model="authorized_person.state"
                                                    placeholder="Please select"
                                                    :label="`State`"
                                                    item-text="text"
                                                    item-value="value"
                                                    :items="states"
                                                    outlined
                                                    hide-details="auto"
                                                    dense
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-menu
                                                v-model="showAuthIdExpireDate"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on, attrs }">
                                                    <ValidationProvider
                                                        name="Expiry Date"
                                                        rules="|valid-date"
                                                        v-slot="{ errors }"
                                                    >
                                                        <v-text-field
                                                            placeholder="DD/MM/YYYY"
                                                            :label="`Expiry Date`"
                                                            outlined
                                                            dense
                                                            v-model="authorized_person.expire_date"
                                                            v-bind="attrs"
                                                            :error-messages="errors[0]"
                                                            hide-details="auto"
                                                        >
                                                            <template slot="append">
                                                                <v-icon v-on="on">mdi-calendar</v-icon>
                                                            </template>
                                                        </v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-date-picker
                                                    v-model="expire_date"
                                                    @input="showAuthIdExpireDate = false"
                                                ></v-date-picker>
                                            </v-menu>
                                        </v-col>
                                    </template>
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
import { titlesMapperForDropdown } from  "@scripts/data/titleMapper";
import SPECIAL_NUMBER from "@scripts/data/constants/SPECIAL_NUMBER";
import dayJs from "dayjs";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import MEDICARE_COLOR_DD from "@scripts/data/constants/MEDICARE_COLOR_DD";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
export default {
name: "AuthorizedPersonProfileForm",
    props:['dialog','authorized_person_data','leadId'],
    data()
    {
      return {
          specialNumberDD: SPECIAL_NUMBER,
          colorDD: MEDICARE_COLOR_DD,
          idenficationTypeDD: [
              {
                  text: "Passport",
                  value: 1,
              },
              {
                  text: "Driver's License",
                  value: 2,
              },
              {
                  text: "Medicare Card",
                  value: 3,
              },
          ],
          authorized_person:{
              title :'',
              first_name :'',
              middle_name :'',
              last_name:'',
              email:'',
              role: '',
              phone: '',
              id:'',
              connection_application_id: '',
              identification_type: '',
              card_number: '',
              state: '',
              country: '',
              card_color: '',
              special_number: '',
              expire_date: null,
          },
          minExpiredate: new Date().toISOString(),
          showAuthIdExpireDate: false,
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
              },
              {
                  value: 4,
                  text:'Joint Account Holder'
              }
          ],
          authorized_person_dob:  (new DayJs((new Date()).setFullYear(2000))).format('YYYY-MM-DD'),
          showAuthoritydob: false,
          titlesDD: titlesMapperForDropdown,
          expire_date: null,
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
      }
    },
    methods:{
       async submitForm() {
            let v = await this.$refs.edit_authority.validate();
            if(!v) return
            this.$emit('saveAuthorizedPerson', this.authorized_person);
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
            this.authorized_person.connection_application_id = this.authorized_person_data.connection_application_id;
            this.authorized_person.identification_type = this.authorized_person_data.identification_type;
            this.authorized_person.card_number = this.authorized_person_data.card_number;
            this.authorized_person.state = this.authorized_person_data.state;
            this.authorized_person.country = this.authorized_person_data.country;
            this.authorized_person.card_color = this.authorized_person_data.card_color;
            this.authorized_person.special_number = this.authorized_person_data.special_number;
            let expireDateFormat = DATE_FORMAT.DB_DATE;
            this.expire_date = this.authorized_person_data.expire_date;
        },

        isSecondaryIdMedicare() {
            return this.authorized_person.identification_type === IDENTIFICATION.MEDICARE;
        }
    },

    watch:{
        authorized_person_dob() {
            this.authorized_person.dob = (new DayJs(this.authorized_person_dob).format('DD/MM/YYYY'));
        },
        expire_date() {
            if (isNull(this.expire_date)) return;
            if(this.isSecondaryIdMedicare()) {
                this.authorized_person.expire_date = dayJs(this.expire_date).format("MM/YY");
            } else {
                this.authorized_person.expire_date = dayJs(this.expire_date).format(
                    "DD/MM/YYYY"
                );
            }
        },
    },
    mounted() {
        this.syncData();
    }
}
</script>

<style scoped>

</style>
