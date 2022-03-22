<template>
    <v-container>
        <section class="px-4 new-application">
            <v-row>
                <v-col cols="12" class="pb-0">
<!--                    <v-btn @click="cancelDialog"><v-icon left dark>mdi-arrow-left</v-icon>Back to Edit</v-btn>-->
                    <h2 class="large-title1 my-3 primary--text">Please confirm your application details.</h2>
                </v-col>

                <v-col cols="12" class="pb-0 pl-2">
                    <v-row>
                        <v-col cols="12">
                            <h4 class="mt-5 primary--text">
                                Lead Details
                            </h4>
                        </v-col>
                        <v-col cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Applicant Name:</p>
                        </v-col>
                        <v-col cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{applicant_full_name}}
<!--                                {{ application.first_name + ' ' + application.middle_name + ' ' + application.last_name}}-->
                            </p>
                        </v-col>

                        <v-col cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Occupancy Type:</p>
                        </v-col>
                        <v-col cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{ application.tenancy_type == 1?'Renter':'Owner' }}
                            </p>
                        </v-col>
                        <v-col cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Date of Birth:</p>
                        </v-col>
                        <v-col cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{ application.date_of_birth}}
                            </p>
                        </v-col>
                        <v-col v-if="application.phone_type === 1"  cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Mobile No:</p>
                        </v-col>
                        <v-col v-if="application.phone_type === 1" cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{ application.phone}}
                            </p>
                        </v-col>

                        <v-col v-if="application.phone_type === 2" cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Homephone No:</p>
                        </v-col>
                        <v-col v-if="application.phone_type === 2" cols="7" class="my-0 py-0 ">
                            <p class="mb-1">
                                {{ application.homephone}}
                            </p>
                        </v-col>

                        <v-col v-if="application.phone_type === 3" cols="4" class="my-0 py-0  d-flex justify-end">
                            <p class="font-weight-bold pl-2 mb-1">I. Mobile Number:</p>
                        </v-col>
                        <v-col v-if="application.phone_type === 3" cols="7" class="my-0 py-0 ">
                            <p class="mb-1">
                                {{ application.international_phone }}
                            </p>
                        </v-col>

                        <v-col cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Email:</p>
                        </v-col>
                        <v-col cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{ application.email}}
                            </p>
                        </v-col>



                        <v-col cols="12" class="pb-0">
                            <v-row class="my-0 py-0">
                                <v-col class="my-0 py-0">
                                    <v-checkbox
                                        v-model="application.is_contacted"
                                        readonly
                                        :label="`Applicant consents to be contacted by HOOD`"
                                    ></v-checkbox>
                                </v-col>
                            </v-row>
                        </v-col>



                        <v-col cols="12">
                            <h4 class="mt-5 primary--text">
                                Identification
                            </h4>
                        </v-col>
                        <template v-if="identification.type === 3">
                            <v-col cols="4" class="my-0 py-0 d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Identification Type:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ 'Medicare'}}
                                </p>
                            </v-col>

                            <v-col cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Medicare Number:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.card_number}}
                                </p>
                            </v-col>
                            <v-col cols="4" class="my-0 py-0 d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Special Number:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.special_number}}
                                </p>
                            </v-col>
                            <v-col cols="4" class="my-0 py-0 d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Expiry Date:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.expire_date}}
                                </p>
                            </v-col>
                            <v-col cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Card Colour:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.card_color}}
                                </p>
                            </v-col>
                        </template>

                        <template v-if="identification.type === 1">
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Identification Type:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ 'Passport'}}
                                </p>
                            </v-col>

                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Passport Number:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.card_number}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Issuing Country:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.country}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Expiry Date:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.expire_date}}
                                </p>
                            </v-col>
                        </template>

                        <template v-if="identification.type === 2">
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Identification Type:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ 'License'}}
                                </p>
                            </v-col>

                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Driver’s License:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.card_number}}
                                </p>
                            </v-col>
                            <v-col cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1" >State:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.state}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Expiry Date:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ identification.expire_date}}
                                </p>
                            </v-col>
                        </template>

                        <template v-if="has_authorized">
                            <v-col cols="12">
                                <h4 class="mt-5 primary--text">
                                    Authorised Person's Details
                                </h4>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Applicant Name:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorised_full_name }}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Email:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.email}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Date of Birth:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.dob}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Mobile No:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.phone}}
                                </p>
                            </v-col>
                        </template>

                        <template v-if="has_authorized && authorisedPerson.identification_type === 3">
                            <v-col cols="12">
                                <h4 class="mt-5 primary--text">
                                    Authorised Person's Identification
                                </h4>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Identification Type:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ 'Medicare' }}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Medicare Number:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.card_number}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Special Number:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.special_number}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Expiry Date:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.expire_date}}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Card Color:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.card_color}}
                                </p>
                            </v-col>
                        </template>
                        <template v-if="has_authorized && authorisedPerson.identification_type === 1">
                            <v-col cols="12">
                                <h4 class="mt-5 primary--text">
                                    Authorised Person's Identification
                                </h4>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Identification Type:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ 'Passport' }}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Passport Number:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.card_number}}
                                </p>
                            </v-col>

                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Issuing Country:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.country}}
                                </p>
                            </v-col>

                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Expiry Date:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.expire_date}}
                                </p>
                            </v-col>
                        </template>
                        <template v-if="has_authorized && authorisedPerson.identification_type === 2">
                            <v-col cols="12">
                                <h4 class="mt-5 primary--text">
                                    Authorised Person's Identification
                                </h4>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Identification Type:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ 'License' }}
                                </p>
                            </v-col>
                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Driver's License:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.card_number}}
                                </p>
                            </v-col>

                            <v-col cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1" >State:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.state}}
                                </p>
                            </v-col>

                            <v-col  cols="4" class="my-0 py-0  d-flex ">
                                <p class="font-weight-bold pl-2 mb-1">Expiry Date:</p>
                            </v-col>
                            <v-col cols="7" class="my-0 py-0">
                                <p class="mb-1">
                                    {{ authorisedPerson.expire_date}}
                                </p>
                            </v-col>
                        </template>
                        <v-col cols="12">
                            <h4 class="mt-4 primary--text">
                                Connection Details
                            </h4>
                        </v-col>



                        <v-col  cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Moving Date:</p>
                        </v-col>
                        <v-col cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{ application.moving_date}}
                            </p>
                        </v-col>
                        <v-col  cols="4" class="my-0 py-0  d-flex ">
                            <p class="font-weight-bold pl-2 mb-1">Address:</p>
                        </v-col>
                        <v-col cols="7" class="my-0 py-0">
                            <p class="mb-1">
                                {{  application.address_text}}
                            </p>
                        </v-col>



<!-- 
                        <v-col cols="12">
                            <v-row>
                                <v-col cols="10">
                                    <p class="font-weight-bold mb-0">Service Interests</p>
                                    <v-row>
                                        <v-col cols="3" class="px-1">
                                            <div class="leade-badge text-center elevation-3"
                                                 :class="application.service_interests.includes('power') ? 'div_enabled' : 'div_disabled' ">
                                                <p class="mb-0">
                                                    <small :class="application.service_interests.includes('power') ? 'enabled' : 'disabled'">Power</small>
                                                </p>
                                                <v-icon :disabled="!application.service_interests.includes('power')" color="yellow">mdi-flash</v-icon>
                                            </div>
                                        </v-col>
                                        <v-col cols="3" class="px-1">
                                            <div class="leade-badge text-center elevation-3"
                                                 :class="application.service_interests.includes('gas') ? 'div_enabled' : 'div_disabled' ">
                                                <p class="mb-0">
                                                    <small :class="application.service_interests.includes('gas') ? 'enabled' : 'disabled'">Gas</small>
                                                </p>
                                                <v-icon :disabled="!application.service_interests.includes('gas')" color="red">mdi-fire</v-icon>
                                            </div>
                                        </v-col>
                                        <v-col cols="3" class="px-1">
                                            <div class="leade-badge text-center elevation-3"
                                                 :class="application.service_interests.includes('water') ? 'div_enabled' : 'div_disabled' ">
                                                <p class="mb-0">
                                                    <small :class="application.service_interests.includes('water') ? 'enabled' : 'disabled'">Water</small>
                                                </p>
                                                <v-icon :disabled="!application.service_interests.includes('water')" color="blue">mdi-water</v-icon>
                                            </div>
                                        </v-col>
                                        <v-col cols="3" class="px-1">
                                            <div class="leade-badge text-center elevation-3"
                                                 :class="application.service_interests.includes('internet') ? 'div_enabled' : 'div_disabled' ">
                                                <p class="mb-0">
                                                    <small :class="application.service_interests.includes('internet') ? 'enabled' : 'disabled'">Internet</small>
                                                </p>
                                                <v-icon :disabled="!application.service_interests.includes('internet')" color="#9C27B0">mdi-wifi</v-icon>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-col> -->

                    </v-row>
                </v-col>
                <v-col cols="12">
                    <h4 class="my-5">
                        Additional Instructions
                    </h4>
                    <div class="gray-background pa-3">
                        <p>Additional Instruction goes here</p>
                        <p>{{application.additional_instruction}}</p>
                    </div>
                </v-col>
                <v-col cols="12">
                    <div class="d-flex justify-end">
                        <v-btn @click="cancelDialog" class="mx-4">Back to Edit</v-btn>
                        <v-btn @click="saveApplication" color="primary">Confirm</v-btn>
                    </div>
                </v-col>

            </v-row>
        </section>
    </v-container>
</template>

<script>
export default {
    name: "AgentConfirmApplicationDetails",
    props:['application', 'identification', 'authorisedPerson', 'has_authorized'],
    methods: {
        cancelDialog() {
            this.$emit('cancelDialog');
        },
        saveApplication() {
            this.$emit('saveApplication');
        }
    },
    computed: {
            applicant_full_name()
            {
                return this.application.middle_name?
                    this.application.title + ' ' +
                    this.application.first_name
                    + ' ' + this.application.middle_name
                    + ' ' + this.application.last_name:
                    this.application.title + ' ' + this.application.first_name + ' ' + this.application.last_name;
            },
            authorised_full_name(){
                return this.authorisedPerson.middle_name?
                    this.authorisedPerson.title + ' ' +
                    this.authorisedPerson.first_name
                    + ' ' + this.authorisedPerson.middle_name
                    + ' ' + this.authorisedPerson.last_name:
                    this.authorisedPerson.title + ' ' +
                    this.authorisedPerson.first_name
                    + ' ' + this.authorisedPerson.last_name;
            },
            is_authorised()
            {
             return true;
            }
    },
};
</script>

<style scoped>
        .large-title1{
            font-size: 24px;
        }
    .div_enabled {
        border-color: transparent;
        cursor: pointer;
        background: #5C229A ;
    }
    .div_disabled {
        border-color: gray;
        cursor: pointer;
    }
    .enabled {
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0.03em;
        text-align: center;
        color: #FFFFFF;
    }
    .disabled {
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        letter-spacing: 0.03em;
        text-align: center;
        color: gray;
    }
    .gray-background {
        background: #FAFAFA;
        font-size: 16px !important;
    }
        .gray-background > p {
            color: #7E8A8F;
        }
</style>