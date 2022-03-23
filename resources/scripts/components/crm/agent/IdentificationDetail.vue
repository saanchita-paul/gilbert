<template>
    <v-col  cols="12" class="py-0 pl-3" v-if="isLoaded">
       <template v-if="isMedicare()">
           <v-row>
               <v-col cols="6" class="pb-0">
                   <ValidationProvider :rules="`${isTenancyHomeOwner?'':'required-medicare'}`" name="Medicare Card Number" v-slot="{ errors }">
                       <v-text-field
                           :error-messages="errors[0]"
                           v-model="identification.card_number"
                           outlined
                           dense
                           placeholder="Medicare Card Number"
                           :label="`Medicare Card Number${isTenancyHomeOwner?'':'*'}`"
                           @change="updateIdentification"
                       ></v-text-field>
                   </ValidationProvider>
               </v-col>
               <v-col cols="6" class="pb-0">
                       <ValidationProvider
                           name="Special Number"
                           :rules="`${isTenancyHomeOwner?'':'required-special-number'}`"
                           v-slot="{ errors }"
                       >
                           <v-select
                               v-model="identification.special_number"
                               :error-messages="errors[0]"
                               :label="`Special Number${isTenancyHomeOwner?'':'*'}`"
                               placeholder="1/2"
                               :items="specialNumberDD"
                               outlined
                               dense
                               @change="updateIdentification"
                           >
                           </v-select>
                       </ValidationProvider>
               </v-col>
               <v-col cols="6" class="pb-0">
                   <v-menu
                       v-model="showMovingDate"
                       :close-on-content-click="false"
                       :nudge-right="40"
                       transition="scale-transition"
                       offset-y
                       min-width="290px"
                   >
                       <template v-slot:activator="{ on, attrs }">
                           <ValidationProvider
                               name="Expiry Date"
                               :rules="`${isTenancyHomeOwner?'':'required|'}medicare-date|medi-expire`"
                               v-slot="{ errors }"
                           >
                               <v-text-field
                                   placeholder="MM/YY"
                                   :label="`Expiry Date${isTenancyHomeOwner?'':'*'}`"
                                   outlined
                                   dense
                                   v-model="identification.expire_date"
                                   v-bind="attrs"
                                   :error-messages="errors[0]"
                                   @change="updateExpireDatePicker"
                               >
                                   <template slot="append">
                                       <v-icon v-on="on">mdi-calendar</v-icon>
                                   </template>
                               </v-text-field>
                           </ValidationProvider>
                       </template>
                       <v-date-picker
                           v-model="expire_date"
                           @input="showMovingDate = false"
                           type="month"
                           :min="minExpiredate"
                       ></v-date-picker>
                   </v-menu>
               </v-col>
               <v-col cols="6" class="pb-0">
                   <ValidationProvider
                       name="Card Colour"
                       :rules="`${isTenancyHomeOwner?'':'required'}`"
                       v-slot="{ errors }"
                   >
                       <v-select
                           v-model="identification.card_color"
                           placeholder="Yellow"
                           :label="`Card Colour${isTenancyHomeOwner?'':'*'}`"
                           item-text="text"
                           item-value="value"
                           :items="colorDD"
                           outlined
                           dense
                           @change="updateIdentification"
                       >
                       </v-select>
                   </ValidationProvider>
               </v-col>
           </v-row>

       </template>
        <template v-if="isPassport()">
            <v-row>
                <v-col cols="6" class="pb-0">
                    <ValidationProvider :rules="`${isTenancyHomeOwner?'':'required-passport'}`" name="Passport Number" v-slot="{ errors }">
                        <v-text-field
                            :error-messages="errors[0]"
                            v-model="identification.card_number"
                            outlined
                            dense
                            placeholder="Passport Number"
                            :label="`Passport Number${isTenancyHomeOwner?'':'*'}`"
                            @change="updateIdentification"
                        ></v-text-field>
                    </ValidationProvider>
                </v-col>
                <v-col cols="6" class="pb-0">
                    <ValidationProvider :rules="`${isTenancyHomeOwner?'':'required-issuing-country'}`" name="Issuing Country" v-slot="{ errors }">
                        <v-text-field
                            :error-messages="errors[0]"
                            v-model="identification.country"
                            outlined
                            dense
                            placeholder="AUS"
                            :label="`Issuing Country${isTenancyHomeOwner?'':'*'}`"
                            @change="updateIdentification"
                        ></v-text-field>
                    </ValidationProvider>
                </v-col>
                <v-col cols="6" class="pb-0">
                        <v-menu
                            v-model="showMovingDate"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider
                                    name="Expiry Date"
                                    :rules="`${isTenancyHomeOwner?'':'required|'}valid-date`"
                                    v-slot="{ errors }"
                                >
                                    <v-text-field
                                        placeholder="DD/MM/YYYY"
                                        :label="`Expiry Date${isTenancyHomeOwner?'':'*'}`"
                                        outlined
                                        dense
                                        v-model="identification.expire_date"
                                        v-bind="attrs"
                                        :error-messages="errors[0]"
                                        @change="updateExpireDatePicker"
                                    >
                                        <template slot="append">
                                            <v-icon v-on="on">mdi-calendar</v-icon>
                                        </template>
                                    </v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker
                                v-model="expire_date"
                                @input="showMovingDate = false"
                            ></v-date-picker>
                        </v-menu>
                </v-col>
            </v-row>
        </template>
        <template v-if="isDL()">
            <v-row>
                <v-col cols="6" class="pb-0">
                    <ValidationProvider :rules="`${isTenancyHomeOwner?'':'required-driving'}`" name="Driver’s License*" v-slot="{ errors }">
                        <v-text-field
                            :error-messages="errors[0]"
                            v-model="identification.card_number"
                            outlined
                            dense
                            placeholder="License Number"
                            :label="`Driver’s License${isTenancyHomeOwner?'':'*'}`"
                            @change="updateIdentification"
                        ></v-text-field>
                    </ValidationProvider>
                </v-col>
                <v-col cols="6" class="pb-0">
                    <ValidationProvider
                        name="State"
                        :rules="`${isTenancyHomeOwner?'':'required'}`"
                        v-slot="{ errors }"
                    >
                        <v-select
                            v-model="identification.state"
                            placeholder="Victoria"
                            :label="`State${isTenancyHomeOwner?'':'*'}`"
                            item-text="text"
                            item-value="value"
                            :items="statesDD"
                            outlined
                            dense
                            @change="updateIdentification"
                        >
                        </v-select>
                    </ValidationProvider>
                </v-col>
                <v-col cols="6" class="pb-0">
                        <v-menu
                            v-model="showMovingDate"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider
                                    name="Expiry Date"
                                    :rules="`${isTenancyHomeOwner?'':'required|'}valid-date`"
                                    v-slot="{ errors }"
                                >
                                    <v-text-field
                                        placeholder="DD/MM/YYYY"
                                        :label="`Expiry Date${isTenancyHomeOwner?'':'*'}`"
                                        outlined
                                        dense
                                        v-model="identification.expire_date"
                                        v-bind="attrs"
                                        :error-messages="errors[0]"
                                        @change="updateExpireDatePicker"
                                    >
                                        <template slot="append">
                                            <v-icon v-on="on">mdi-calendar</v-icon>
                                        </template>
                                    </v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker
                                v-model="expire_date"
                                @input="showMovingDate = false"
                            ></v-date-picker>
                        </v-menu>
                </v-col>
            </v-row>
        </template>
    </v-col>
</template>

<script>
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import DayJs from "dayjs";
import {isNull} from "lodash-es";
import dayJs from "dayjs";
import SPECIAL_NUMBER from "@scripts/data/constants/SPECIAL_NUMBER";

export default {
name: "IdentificationDetail",
    props:['indentification', 'isTenancyHomeOwner'],
    data() {
        return {
            identification: null,
            specialNumberDD: SPECIAL_NUMBER,
            colorDD: [
                {
                    text: "Green",
                    value: "GREEN",
                },
                {
                    text: "Blue",
                    value: "BLUE",
                },
                {
                    text: "Yellow",
                    value: "YELLOW",
                },
            ],
            statesDD: [
                { text: "NSW", value: "New South Wales" },
                { text: "VIC", value: "Victoria" },
                { text: "QLD", value: "Queensland" },
                { text: "SA", value: "South Australia" },
                { text: "NT", value: "Northern Territory" },
                { text: "TAS", value: "Tasmania" },
                { text: "ACT", value: "Australian Capital Territory" },
                { text: "WA", value: "Western Australia" }, // TODO state definition can be updated
            ],
            expire_date: null,
            showMovingDate: false,
            isLoaded : false,
            minExpiredate: new Date().toISOString(),
        }
    },
    methods:{
        syncProps() {
            this.identification = this.indentification;
            this.isLoaded = true;
        },
        isMedicare()
        {
            return this.indentification.type === IDENTIFICATION.MEDICARE;
        },

        isPassport()
        {
            return this.indentification.type === IDENTIFICATION.PASSPORT;
        },

        isDL()
        {
            return this.indentification.type === IDENTIFICATION.DL;
        },

        updateExpireDatePicker() {
            if (dayJs(this.identification.expire_date, "DD/MM/YYYY").isValid()) {
                this.expire_date = DayJs(
                    this.identification.expire_date,
                    "DD/MM/YYYY"
                ).format("YYYY-MM-DD");
                this.updateIdentification();
            }
        },

        updateIdentification()
        {
            this.$emit('updateIdentification', this.identification);
        }

    },

    watch: {
        expire_date() {
            if (isNull(this.expire_date)) return;
            if(this.isMedicare())
            {
                this.identification.expire_date = dayJs(this.expire_date).format(
                    "MM/YY"
                );
            } else
            {
                this.identification.expire_date = dayJs(this.expire_date).format(
                    "DD/MM/YYYY"
                );
            }

            this.updateIdentification();
        },


    },

    mounted() {
    this.syncProps();
        console.log(this.indentification);
    }


}
</script>

<style scoped>
  .div_enabled {
    border-color: transparent;
    cursor: pointer;
    background: #5C229A ;
  }
  .div_disabled {
    border-color: gray;
    cursor: pointer;
  }
</style>
