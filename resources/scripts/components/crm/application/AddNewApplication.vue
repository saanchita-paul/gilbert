<template>
    <v-container>
        <v-card class="pa-4 new-application">
            <v-row>
                <v-col cols="12 pb-0">
                    <v-btn><v-icon left dark>mdi-arrow-left</v-icon>Back to my Dashboard</v-btn>
                    <h3 class="page-title my-5 pt-5">Add a new Application</h3>
                    <p class="sub-title mb-0">Contact Details  <small class="font-weight-thin">Personal details or your applicant.</small>  <small class="font-weight-thin float-right">All fields are mandatory*</small></p>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Firstname*"
                        outlined
                        dense
                        placeholder="Firstname"
                    ></v-text-field>
                    <v-text-field
                        label="Email*"
                        outlined
                        dense
                        placeholder="example@domain.com"
                    ></v-text-field>
                    <v-select outlined dense label="Tenancy Type*" placeholder="Please Select">
                    </v-select>
                </v-col>

                <v-col cols="6">
                   
                    <v-text-field
                        label="Lastname*"
                        outlined
                        dense
                        placeholder="Lastname*"
                    ></v-text-field>
                    <v-text-field
                        label="Mobile number*"
                        outlined
                        dense
                        placeholder="+61 410"
                    ></v-text-field>
                    
                    <v-text-field
                        label="Date of Birth*"
                        placeholder="DD/MM/YYYY"
                        outlined
                        dense
                        append-icon="mdi-calendar"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" class="py-0">
                    <p class="sub-title mb-0">Moving Details <small class="font-weight-thin">Information about your lead’s move.</small></p>
                </v-col>
                <v-col cols="12" class="pb-0">
                    <v-row>
                        <v-col cols="6" class="py-0">
                            <v-text-field
                                label="Moving Date*"
                                placeholder="DD/MM/YYYY"
                                outlined
                                dense
                                append-icon="mdi-calendar"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="6" class="pt-0">
                    <v-text-field
                        label="To Address Unit"
                        outlined
                        dense
                        placeholder="2/56"
                    ></v-text-field>
                    <v-text-field
                        label="City"
                        outlined
                        dense
                        placeholder="Camberwell"
                    ></v-text-field>
                    <v-text-field
                        label="Country"
                        outlined
                        dense
                        placeholder="Australia"
                    ></v-text-field>
                </v-col>

                <v-col cols="6" class="pt-0">
                    <v-text-field
                        label="Apartment, suite, etc.."
                        outlined
                        dense
                        placeholder="House / Apartment, Bldg, etc"
                    ></v-text-field>
                    <v-select outlined dense label="State" placeholder="Please Select">
                    </v-select>
                    <v-text-field
                        label="Postcode"
                        outlined
                        dense
                        placeholder="3429"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" class="pb-0">
                    <p class="sub-title mb-0">Service Interests</p>
                </v-col>

                <v-col cols="3">
                    <div class="leade-badge text-center">
                        <h4>Power</h4>
                        <v-icon color="yellow">mdi-flash</v-icon>
                    </div>
                </v-col>

                <v-col cols="3">
                    <div class="leade-badge text-center">
                        <h4>Gas</h4>
                        <v-icon color="red">mdi-fire</v-icon>
                    </div>
                </v-col>

                <v-col cols="3">
                    <div class="leade-badge text-center">
                        <h4>Water</h4>
                        <v-icon color="grey lighten-1">mdi-water</v-icon>
                    </div>
                </v-col>

                <v-col cols="3">
                    <div class="leade-badge text-center">
                        <h4>Internet</h4>
                        <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                    </div>
                </v-col>

                <v-col cols="12">
                    <p class="sub-title  mt-5">Additional Instructions</p>
                    <div class="grey lighten-3 pa-3 grey--text">
                        <p>Additional Instructions goes here. </p>  

                        <p>Maybe it’s extra long so I have to write something down to show how it
                             will look like when a property manager has soooo much things to say about his lead.</p>
                        <p>Maybe another ine of the agent forgot to say something about the lead.</p>
                    </div>
                </v-col>

                

                <v-col cols="12">
                    <div class="d-flex justify-end">
                        <v-btn class="mx-4">Cancel</v-btn>
                        <v-btn color="primary">Submit</v-btn>
                    </div>
                </v-col>

            </v-row>
        </v-card>
    </v-container>
</template>

<script>
export default {
  name: "AddNewApplication",
};
</script>

<style scoped>
</style>
