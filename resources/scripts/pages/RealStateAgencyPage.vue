<template>
    <div>
        <v-btn  v-if="!isAgencyCreating" @click="addAgency"
            small
            color="primary"
            dark
        > <v-icon
            right
            dark
        >
            add
        </v-icon >
            Add New Agency
        </v-btn>
        <div v-if="isAgencyCreating">
            <component v-bind:is="currentComponent"></component>
            <v-btn @click="cancel"
                   small
                   color="primary"
                   dark
            >Cancel
            </v-btn>
            <v-btn @click="addAgency"
                   small
                   color="primary"
                   dark
            >Save
            </v-btn>
        </div>

    </div>
</template>

<script>
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import AllocatorDetails from "@scripts/components/crm/AllocatorDetails";
import CommissionProfile from "@scripts/components/crm/CommissionProfile";
import OfficeDetails from "@scripts/components/crm/OfficeDetails";
const agencyForm = ['AgencyDetails','AllocatorDetails', 'CommissionProfile', 'OfficeDetails','OfficeDetails'];
export default {
    name: "RealStateAgencyPage",
    components: {OfficeDetails, CommissionProfile, AllocatorDetails, AgencyDetails},
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
