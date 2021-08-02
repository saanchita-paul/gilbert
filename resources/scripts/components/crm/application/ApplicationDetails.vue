<template>
    <v-container>
        <v-card class="pa-4 new-application">
            <v-row>
                <v-col cols="12" class="pb-0">
                    <v-btn><v-icon left dark>mdi-arrow-left</v-icon>Back to Edit</v-btn>
                    <h2 class="large-title my-5 pt-5 primary--text">Please confirm your application details.</h2>
                </v-col>

                <v-col cols="6">
                    <p class="font-weight-bold">Lead Details</p>
                        <table width="100%" class="application-info">
                            <tr>
                                <td class="font-weight-bold">Lead Name:</td>
                                <td>Firstname Lastname</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Tenancy Type:</td>
                                <td>Renter</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Date of Birth:</td>
                                <td>02/11/1956</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Mobile No:</td>
                                <td>0410758792</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Email:</td>
                                <td>firstname.lastname@gmail.com</td>
                            </tr>
                        </table>
                </v-col>

                <v-col cols="6">
                    <p class="font-weight-bold">Moving Details</p>
                    <table width="100%" class="application-info">
                            <tr>
                                <td class="font-weight-bold">Moving Date:</td>
                                <td>09/11/2022</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Address:</td>
                                <td>1/65 Bourke Rd, Camberwell. 3124 VIC</td>
                            </tr>
                    </table>
                </v-col>

                <v-col cols="12">
                    <v-row>
                        <v-col cols="6">
                            <p class="font-weight-bold mb-0">Service Interests</p>
                            <v-row>
                                <v-col cols="3">
                                    <div class="leade-badge text-center elevation-3">
                                        <p class="mb-0">
                                            <small>Power</small>
                                        </p>
                                        <v-icon color="yellow">mdi-flash</v-icon>
                                    </div>
                                </v-col>

                                <v-col cols="3">
                                    <div class="leade-badge text-center elevation-3">
                                        <p class="mb-0">
                                            <small>Gas</small>
                                        </p>
                                        <v-icon color="red">mdi-fire</v-icon>
                                    </div>
                                </v-col>

                                <v-col cols="3">
                                    <div class="leade-badge text-center ">
                                        <p class="mb-0">
                                            <small>Water</small>
                                        </p>
                                        <v-icon color="grey lighten-1">mdi-water</v-icon>
                                    </div>
                                </v-col>

                                <v-col cols="3">
                                    <div class="leade-badge text-center">
                                        <p class="mb-0">
                                            <small>Internet</small>
                                        </p>
                                        <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-col>
                        <v-col cols="6">
                            <p class="font-weight-bold mb-1">Additional Instructions</p>
                            <p>Additional Instructions goes here. </p>
                            <p class="mb-0">Maybe it’s extra long so I have to write something down to 
                            show how it will look like when a property manager has soooo much
                            things to say on his client.</p>
                        </v-col>
                    </v-row>
                </v-col>

                <v-col cols="12">
                    <div class="d-flex justify-end">
                        <v-btn class="mx-4">Back to Edit</v-btn>
                        <v-btn color="primary">Confirm</v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
export default {
  name: "ApplicationDetails",
};
</script>

<style scoped>
</style>
