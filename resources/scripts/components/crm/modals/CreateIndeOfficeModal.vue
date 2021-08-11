<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <ValidationObserver :ref="currentRef">
                           <component :is="currentComponent" v-model="agency"
                                      @updateOffice = "updateOffice"
                                      @updateAllocator = "updateAllocator"
                                      @updateProfile = "updateProfile"
                           > </component>
                    </ValidationObserver>
                    <ProgressBar :total-step="totalStep" :current-index="currentCompIndex" ></ProgressBar>
                    <v-row>
                        <v-col cols="12">
                            <div class="d-flex justify-space-between">
                                <v-btn @click="cancel"
                                >Cancel
                                </v-btn>
                                <v-btn @click="goNextOrSave"
                                       color="primary"
                                >
                                   {{nextOrSave}}
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgencyDetailsModal from "@scripts/components/crm/modals/AgencyDetailsModal";
import ProgressBar from "@scripts/components/crm/ProgressBar";
import CommissionProfile from "@scripts/components/crm/office/CommissionProfile";
import AllocatorDetails from "@scripts/components/crm/office/AllocatorDetails";
import OfficeDetails from "@scripts/components/crm/OfficeDetails";


const agencyForm = ['OfficeDetails','AllocatorDetails', 'CommissionProfile'];
const formRef = ['create_office','create_allocator', 'create_commission'];
export default {
name: "CreateIndeOfficeModal",
    components:{
        AgencyDetailsModal,
        OfficeDetails,
        CommissionProfile,
        AllocatorDetails,
        ProgressBar,
    },
    props:['dialog'],
    data() {
        return {
            currentComponent: "OfficeDetails",
            currentRef : 'create_office',
            currentCompIndex : 0,
            totalStep: 3,
            agency: {
                office: {

                },
                allocator: {

                },
                profile: {

                }
            },
            validatedMessage : null,
        }
    },
    computed: {
        nextOrSave: function () {
            return this.currentCompIndex < 2?'Next':'Save';
        }
    },
    methods: {

      async  goNextOrSave() {
            if((this.totalStep -1 ) === this.currentCompIndex) {
                this.$emit('goToNext', this.agency);
                return;

            }
            if( await this.isValidateForm()) {
                this.currentCompIndex ++;
                this.currentComponent = agencyForm[ this.currentCompIndex];
                this.currentRef = formRef[this.currentCompIndex];

            } else
            {
                return;
            }

        },

       async isValidateForm() {

            if(this.currentCompIndex == 0) {
                return await this.$refs.create_office.validate();
            }
           if(this.currentCompIndex == 1) {
               return await this.$refs.create_allocator.validate();
           }
           if(this.currentCompIndex == 2) {
               return await this.$refs.create_commission.validate();
           }
        },

        cancel() {
            this.$emit('cancelDialog');
        },

        updateOffice(officeData) {
            this.agency.office = officeData;
        },

        updateAllocator(allocator) {
            this.agency.allocator = allocator;
        },

        updateProfile(profile) {
            this.agency.profile = profile;
        }
    }
}
</script>

<style scoped>

</style>
