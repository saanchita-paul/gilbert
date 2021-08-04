<template>
    <v-container>
        <v-card class="pa-4 new-application">
            <v-row>
                <v-col cols="12" class="pb-0">
                    <v-btn @click="cancelDialog"><v-icon left dark>mdi-arrow-left</v-icon>Back to Edit</v-btn>
                    <h2 class="large-title my-5 pt-5 primary--text">Please confirm your application details.</h2>
                </v-col>

                <v-col cols="6">
                    <p class="font-weight-bold">Lead Details</p>
                        <table width="100%" class="application-info">
                            <tr>
                                <td class="font-weight-bold">Lead Name:</td>
                                <td>{{ application.first_name + ' ' + application.last_name}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Tenancy Type:</td>
                                <td>{{ application.tenancy_type }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Date of Birth:</td>
                                <td>{{ application.date_of_birth }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Mobile No:</td>
                                <td>{{ application.phone }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Email:</td>
                                <td>{{ application.email }}</td>
                            </tr>
                        </table>
                </v-col>

                <v-col cols="6">
                    <p class="font-weight-bold">Moving Details</p>
                    <table width="100%" class="application-info">
                            <tr>
                                <td class="font-weight-bold">Moving Date:</td>
                                <td>{{ application.moving_date }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address:</td>
                                <td>{{ service_address }}</td>
                            </tr>
                    </table>
                </v-col>

                <v-col cols="12">
                    <v-row>
                        <v-col cols="6">
                            <p class="font-weight-bold mb-0">Service Interests</p>
                            <v-row>
                                <v-col cols="3">
                                    <div class="leade-badge text-center elevation-3"
                                         :class="application.service_interests.includes('power') ? 'div_enabled' : 'div_disabled' ">
                                        <p class="mb-0">
                                            <small :class="application.service_interests.includes('power') ? 'enabled' : 'disabled'">Power</small>
                                        </p>
                                        <v-icon :disabled="!application.service_interests.includes('power')" color="yellow">mdi-flash</v-icon>
                                    </div>
                                </v-col>

                                <v-col cols="3">
                                    <div class="leade-badge text-center elevation-3"
                                         :class="application.service_interests.includes('gas') ? 'div_enabled' : 'div_disabled' ">
                                        <p class="mb-0">
                                            <small :class="application.service_interests.includes('gas') ? 'enabled' : 'disabled'">Gas</small>
                                        </p>
                                        <v-icon :disabled="!application.service_interests.includes('gas')" color="red">mdi-fire</v-icon>
                                    </div>
                                </v-col>

                                <v-col cols="3">
                                    <div class="leade-badge text-center elevation-3"
                                         :class="application.service_interests.includes('water') ? 'div_enabled' : 'div_disabled' ">
                                        <p class="mb-0">
                                            <small :class="application.service_interests.includes('water') ? 'enabled' : 'disabled'">Water</small>
                                        </p>
                                        <v-icon :disabled="!application.service_interests.includes('water')" color="blue">mdi-water</v-icon>
                                    </div>
                                </v-col>

                                <v-col cols="3">
                                    <div class="leade-badge text-center elevation-3"
                                         :class="application.service_interests.includes('internet') ? 'div_enabled' : 'div_disabled' ">
                                        <p class="mb-0">
                                            <small :class="application.service_interests.includes('internet') ? 'enabled' : 'disabled'">Internet</small>
                                        </p>
                                        <v-icon :disabled="!application.service_interests.includes('internet')" color="green">mdi-wifi</v-icon>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="6">
                            <p class="font-weight-bold mb-1">Additional Instructions</p>
                            <p>{{ application.additional_instruction }}</p>
                        </v-col>
                    </v-row>
                </v-col>

                <v-col cols="12">
                    <div class="d-flex justify-end">
                        <v-btn @click="cancelDialog" class="mx-4">Back to Edit</v-btn>
                        <v-btn @click="saveApplication" color="primary">Confirm</v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
export default {
    name: "AgentConfirmApplicationDetails",
    props:['application'],
    methods: {
        cancelDialog() {
            this.$emit('cancelDialog');
        },
        saveApplication() {
            this.$emit('saveApplication');
        }
    },
    computed: {
        service_address() {
            return this.application.address_unit + ' ' + this.application.address_apartment + ', '
                + this.application.city + ', ' + this.application.state + ', '  + this.application.country
                + ' ' + this.application.postcode;
        }
    },
};
</script>

<style scoped>
    .enabled {
        color: black;
    }
    .disabled {
        color: gray;
    }
    .div_enabled {
        border-color: gray;
        cursor: pointer;
    }
    .div_disabled {
        border-color: lightgray;
        cursor: pointer;
    }
</style>
