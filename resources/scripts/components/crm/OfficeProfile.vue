<template>
    <v-container>
        <v-card class="pa-4">
            <v-row>
                <v-col cols="12 pb-0">
                    <v-btn><v-icon left dark>mdi-arrow-left</v-icon>Back to Sunbury Office Metrics</v-btn>
                    <h3 class="page-title my-5 pt-5">Sunbury Office Profile</h3>
                    <p class="sub-title mb-0">Branch/Office Details</p>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Office Branch Location*"
                        outlined
                        dense
                        placeholder="Sunbury"
                    ></v-text-field>
                    <v-text-field
                        label="Office Address*"
                        outlined
                        dense
                        placeholder="398 Bourke Road, Camberwell 3124 VIC"
                    ></v-text-field>
                    <v-text-field
                        label="Phone Number (Optional)"
                        outlined
                        dense
                        placeholder="+61"
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Email Address (Optional)"
                        outlined
                        dense
                        placeholder="example@domain.com"
                    ></v-text-field>
                    <v-text-field
                        label="ABN (Optional)"
                        outlined
                        dense
                        placeholder="00000000000"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" class="py-0">
                    <p class="sub-title mb-0">Branch Allocator Details</p>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Office Branch Location*"
                        outlined
                        dense
                        placeholder="Branch Location"
                    ></v-text-field>
                    <v-text-field
                        label="User ID*"
                        outlined
                        dense
                        placeholder="firstname.lastname@barryplantcamberwell.com.au"
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Email Address*"
                        outlined
                        dense
                        placeholder="example@domain.com"
                    ></v-text-field>

                    <v-text-field
                        label="Phone Number"
                        outlined
                        dense
                        placeholder="+61"
                    ></v-text-field>
                </v-col>

                <v-col cols="12">
                    <p class="sub-title mb-0">Comission Orofiles for this Office.</p>
                </v-col>

               <v-row  class="section-leademetriics pa-4">
                    <v-col cols="3" class="pa-0">
                        <div class="leade-badge">
                            <h3>Power</h3>
                            <div class="leade-icon pb-2">
                                <v-icon color="yellow">mdi-flash</v-icon>
                                <span class="mr-4">$</span>
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                ></v-text-field>
                            </div>
                            <p class="leade-text pr-5 mb-0">Per successful connection</p>
                        </div>
                    </v-col>

                    <v-col cols="3" class="pa-0">
                        <div class="leade-badge">
                            <h3>Gas</h3>
                            <div class="leade-icon pb-2">
                                <v-icon color="red">mdi-fire</v-icon>
                                <span class="mr-4">$</span>
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                ></v-text-field>
                            </div>
                            <p class="leade-text pr-5">Per successful connection</p>
                        </div>
                    </v-col>

                    <v-col cols="3" class="pa-0">
                        <div class="leade-badge">
                            <h3>Internet</h3>
                            <div class="leade-icon pb-2">
                                <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                                <span class="mr-4">$</span>
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                ></v-text-field>
                            </div>
                            <p class="leade-text pr-5">Per successful connection</p>
                        </div>
                    </v-col>

                    <v-col cols="3" class="pa-0">
                        <div class="leade-badge">
                            <h3>Water</h3>
                            <div class="leade-icon pb-2">
                                <v-icon color="grey lighten-1">mdi-water</v-icon>
                                <span class="mr-4">$</span>
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                ></v-text-field>
                            </div>
                            <p class="leade-text pr-5">Per successful connection</p>
                        </div>
                    </v-col>
               </v-row>

            </v-row>
        </v-card>
    </v-container>
    
</template>

<script>
export default {
  name: "OfficeProfile",
};
</script>

<style scoped>
</style>
