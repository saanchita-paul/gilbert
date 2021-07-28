<template>
    <v-container>
        <v-card>
            <v-container>
                <LeadMetrics></LeadMetrics>
            </v-container>
        </v-card>
        
        <v-container>
                <v-row>
                    <v-col cols="8">
                        <v-text-field
                                label="Search"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                            ></v-text-field>
                    </v-col>
                        <v-col cols="4" class="text-right">
                            <v-btn  v-if="!isAgencyCreating" @click="addAgency"
                                color="primary"
                                dark
                                ><v-icon
                                    left
                                    dark
                                >
                                add
                                </v-icon> Add New Agency
                            </v-btn>
                        </v-col>
                </v-row>
        </v-container>
       
        <v-card>
            <CrmDataTable></CrmDataTable>
        </v-card>
        
        

        <v-row>
            <v-col cols="12" v-if="isAgencyCreating">
                <component v-bind:is="currentComponent"></component>
                    <ProgressBar></ProgressBar>
                <div class="d-flex justify-space-between">
                    <v-btn @click="cancel"
                    >Cancel
                    </v-btn>
                    <v-btn @click="addAgency"
                        color="primary"
                    >Save
                    </v-btn>
                </div>
            </v-col>
        </v-row>

       
    </v-container>
</template>

<script>
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import AllocatorDetails from "@scripts/components/crm/AllocatorDetails";
import CommissionProfile from "@scripts/components/crm/CommissionProfile";
import OfficeDetails from "@scripts/components/crm/OfficeDetails";
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import ProgressBar from "@scripts/components/crm/ProgressBar";
import CrmDataTable from "@scripts/components/crm/CrmDataTable";
const agencyForm = ['AgencyDetails','AllocatorDetails', 'CommissionProfile', 'OfficeDetails','OfficeDetails'];
export default {
    name: "RealStateAgencyPage",
    components: {
        LeadMetrics,
        OfficeDetails, 
        CommissionProfile,
        AllocatorDetails,
        AgencyDetails,
        ProgressBar,
        CrmDataTable
    },
    data(){
        return {
            currentComponent:'',
            isAgencyCreating: false,
            agencyFormIndex: -1

        }
    },

    methods: {
        addAgency() {
            this.isAgencyCreating = true;

            if(this.agencyFormIndex === 4 ) {
                this.isAgencyCreating = false;
                this.agencyFormIndex === -1;
            }

            this.agencyFormIndex++;
            this.currentComponent = agencyForm[this.agencyFormIndex];

        },
        cancel() {
            this.isAgencyCreating = false;
            this.agencyFormIndex === -1;
        }
    }

}
</script>

<style scoped>

</style>
