<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="700px"
        >
            <v-card>
                <v-container fluid>
        <v-row class="section-dialogs">
            <v-col cols="12">
                <div class="dialogs-title">
                    <p>Service Address</p>
                </div>

                <div class="dialogs-area pt-5">
                    <p class="title">Address</p>
                    <v-row>
                        <v-col cols="12" class="py-0">
                            <v-text-field
                                label="Search Address"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6" class="py-0">
                            <v-text-field
                                v-model="propertyDetails.street_address"
                                label="Address"
                                outlined
                                dense
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6" class="py-0">
                             <v-text-field
                                label="City/Suburb*"
                                outlined
                                dense
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6" class="py-0">
                            <v-text-field
                                label="State/Territory*"
                                outlined
                                dense
                            ></v-text-field>
                        </v-col>
                        <v-col cols="6" class="py-0">
                             <v-text-field
                                label="Postcode*"
                                outlined
                                dense
                                hide-details
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" >
                            <v-checkbox
                                v-model="checkbox"
                                :label="`Is this the same as your billing address?`"
                            ></v-checkbox>
                        </v-col>

                        <v-col cols="6" class="py-0">
                           <v-btn @click="closeServiceAddress" block>Cancel</v-btn>
                        </v-col>
                        <v-col cols="6" class="py-0">
                           <v-btn block color="primary">Save</v-btn>
                        </v-col>


                    </v-row>

                </div>
            </v-col>
     </v-row>
    </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import Search from "@scripts/components/crm/Search";
export default {
  name: "ServiceAddress",
  components: {
        Search
    },
    props: {
        dialog: {
            required: true
        },
        propertyDetails: {
            required: true
        }
    },
    data () {
        return {
            checkbox: true
        }
    },
    methods: {
        closeServiceAddress() {
            this.$emit('close');
        }
    }
};
</script>

