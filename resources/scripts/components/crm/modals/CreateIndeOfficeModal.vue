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
                           <component :is="currentComponent" v-model="agency" :data="agency"
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
                                >{{backOrCancel}}
                                </v-btn>
                                <v-btn @click="goNextOrSave"
                                       color="primary"
                                       :disabled="isDisable"
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
            isDisable: false,
            checkDisability: false,
            checkDisabilitySave: true,
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
            if(this.currentCompIndex < 2) {
                this.isDisable = false;
                return 'Next'
            }
            this.isDisable = !this.checkNumberProfileValidity();
            return 'Save';
        },
        backOrCancel: function () {
            return this.currentCompIndex > 0?'Back':'Cancel';
        },
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

                if(this.currentCompIndex == 2) {
                     this.isDisable = !this.checkNumberProfileValidity();
                }


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
            this.currentCompIndex --;

            if(this.currentCompIndex < 0) {
                this.$emit('cancelDialog');
                return;
            }
            this.currentComponent = agencyForm[ this.currentCompIndex];
            this.currentRef = formRef[this.currentCompIndex];
        },

        updateOffice(officeData) {
            this.agency.office = officeData;
        },

        updateAllocator(allocator) {
            this.agency.allocator = allocator;
        },

         updateProfile(profile) {
            this.agency.profile = profile;
             this.isDisable = !this.checkNumberProfileValidity();

        },

        checkNumberProfileValidity() {
          let gas =  this.agency.profile?.gas
          let power =  this.agency.profile?.power
          let water =  this.agency.profile?.water
          let internet =  this.agency.profile?.internet

            if(isNaN(gas) ||
                isNaN(power)
            ) {
                return false;
            }

            if((gas.length >5 || (Number(gas)<1 || Number(gas)> 99) ) ||
                (power.length >5 || (Number(power)<1 || Number(power)> 99) )
                // (water.length >2 || (Number(water)<1 || Number(water)> 99) ) ||
                // (internet.length >2 || (Number(internet)<1 || Number(internet)> 99) )

            ) return  false;

            return true;
        }







    }
}
</script>

<style scoped>

</style>
