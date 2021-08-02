<template>
    <v-row>
            <v-col class="section-dialogs" cols="12">
                <div class="dialogs-title">
                    <p>Setup Independent Agency</p>
                </div>

                <div class="dialogs-area">
                    <p class="title">Agency Details</p>
                    <v-text-field
                        label="Office Address*"
                        placeholder="398 Bourke Road, Camberwell 3124 VIC"
                        outlined
                        dense
                    ></v-text-field>
                    <v-text-field
                        label="Contact Phone Number*"
                        placeholder=""
                        outlined
                        dense
                    ></v-text-field>


                    <v-text-field
                        label="Email Address*"
                        placeholder="firstname.lastname@barryplantcamberwell.com.au"
                        outlined
                        dense
                    ></v-text-field>
                    <v-text-field
                        label="ABN (Optional)"
                        placeholder="398 Bourke Road, Camberwell 3124 VIC"
                        outlined
                        dense
                    ></v-text-field>
                </div>
            </v-col>
        </v-row>
</template>

<script>
export default {
name: "SetupIndeAgency"
}
</script>

<style scoped>

</style>
