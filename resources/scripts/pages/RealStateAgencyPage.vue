<template>
    <v-container>
        <LeadMetrics></LeadMetrics>
        <v-row>
            <v-col cols="12">
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
                <v-col cols="12" v-if="isAgencyCreating">
                    <component v-bind:is="currentComponent"></component>
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

       <ProgressBar></ProgressBar>
    </v-container>
</template>

<script>
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import AllocatorDetails from "@scripts/components/crm/AllocatorDetails";
import CommissionProfile from "@scripts/components/crm/CommissionProfile";
import OfficeDetails from "@scripts/components/crm/OfficeDetails";
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import ProgressBar from "@scripts/components/crm/ProgressBar";
const agencyForm = ['AgencyDetails','AllocatorDetails', 'CommissionProfile', 'OfficeDetails','OfficeDetails'];
export default {
    name: "RealStateAgencyPage",
    components: {LeadMetrics, OfficeDetails, CommissionProfile, AllocatorDetails, AgencyDetails, ProgressBar},
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
