<template>
    <v-container fluid>
        <ValidationObserver ref="office_details">
            <v-card class="hood-card" v-if="isLoaded">
                <v-row>
                    <v-col cols="12 pb-0">
                        <v-btn @click="goToOffice"><v-icon left dark>mdi-arrow-left</v-icon>Back</v-btn>
                        <h3 class="page-title my-5 pt-5">{{office.name}} Office Profile</h3>
                        <p v-if="office.agency_type == 0" class="sub-title mb-0">Agency</p>
                    </v-col>

                    <v-col cols="6" v-if="office.agency_type == 0">
                        <ValidationProvider name="Agency Name" rules="required"  v-slot="{ errors }">
                            <v-text-field
                                label="Agency Name*"
                                outlined
                                dense
                                v-model="office.agency_name"
                                :error-messages=" errors[0]"
                                placeholder="Agency Name"
                            ></v-text-field>
                        </ValidationProvider>
                    </v-col>

                </v-row>
            <v-row>
                <v-col cols="12 pb-0">
<!--                    <v-btn @click="goToOffice"><v-icon left dark>mdi-arrow-left</v-icon>Back to Sunbury Office Metrics</v-btn>-->
<!--                    <h3 class="page-title my-5 pt-5">{{office.name}} Office Profile</h3>-->
                    <p class="sub-title mb-0">Branch/Office Details</p>
                </v-col>

                <v-col cols="6">
                    <ValidationProvider name="Office Branch Location" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            label="Office Branch Location*"
                            outlined
                            dense
                            v-model="office.name"
                            :error-messages=" errors[0]"
                            placeholder="Sunbury"
                        ></v-text-field>
                    </ValidationProvider>
                    <ValidationProvider name="Office Address" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            label="Office Address*"
                            v-model="office.address"
                            outlined
                            dense
                            placeholder="398 Bourke Road, Camberwell 3124 VIC"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                    <ValidationProvider name="Phone Number" rules="cv-phone|length:10"  v-slot="{ errors }">
                        <v-text-field
                            label="Phone Number (Optional)"
                            outlined
                            dense
                            v-model="office.phone"
                            placeholder="+61"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>

                    <ValidationProvider name="Office Account Manager"  v-slot="{ errors }">
                       <HoodAgentDropdown :selectedAgentId="hoodAgentId"
                        @onChangeAgent="onChangeAgent" />
                    </ValidationProvider>


                </v-col>

                <v-col cols="6">
                    <ValidationProvider name="Email Address" rules="email"  v-slot="{ errors }">
                        <v-text-field
                            label="Email Address (Optional)"
                            outlined
                            v-model="office.email"
                            dense
                            placeholder="example@domain.com"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                    <ValidationProvider name="ABN" rules="cv-phone"  v-slot="{ errors }">
                    <v-text-field
                        label="ABN (Optional)"
                        outlined
                        dense
                        v-model="office.abn"
                        placeholder="00000000000"
                        :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>

                    <ValidationProvider name="Rent Roll" rules="required|numeric"  v-slot="{ errors }">
                        <v-text-field
                            label="Rent Roll*"
                            outlined
                            dense
                            v-model="office.rent_roll"
                            placeholder="Rent Roll"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>

                </v-col>

                <v-col cols="12" class="py-0">
                    <p class="sub-title mb-0">Branch Allocator Details</p>
                </v-col>

                <v-col cols="6">
                    <ValidationProvider name="First Name" rules="required"  v-slot="{ errors }">

                    <v-text-field
                        label="First Name*"
                        v-model="agent.first_name"
                        outlined
                        dense
                        placeholder="First Name"
                        :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>

                    <ValidationProvider name="Email Address" rules="email"  v-slot="{ errors }">
                    <v-text-field
                        label="Email Address*"
                        v-model="agent.email"
                        outlined
                        dense
                        placeholder="example@domain.com"
                        :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>

                    <ValidationProvider name="User ID"   v-slot="{ errors }">
                    <v-text-field
                        label="User ID*"
                        v-model="agent.f_id_12"
                        outlined
                        dense
                        placeholder="firstname.lastname@barryplantcamberwell.com.au"
                        :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>
                </v-col>

                <v-col cols="6">
                    <ValidationProvider name="Last Name" rules="required"  v-slot="{ errors }">
                    <v-text-field
                        label="Last Name *"
                        v-model="agent.last_name"
                        outlined
                        dense
                        placeholder="Last Name"
                        :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>
                    <ValidationProvider name="Phone Number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                    <v-text-field
                        label="Phone Number"
                        v-model="agent.phone"
                        outlined
                        dense
                        placeholder="+61"
                        :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>
                </v-col>

                <v-col cols="12">
                    <p class="sub-title mb-0">Comission Profiles for this Office.</p>
                </v-col>

               <v-row  class="section-leademetriics pa-4">
                    <v-col cols="3" class="pa-0 px-3">
<!--                        <div class="leade-badge">-->
                            <h4 class="mb-2"> <v-icon color="yellow">mdi-flash</v-icon> Electricity</h4>
<!--                            <div class="leade-icon pb-2">-->
<!--                                <v-icon color="yellow">mdi-flash</v-icon>-->
<!--                                <span class="mr-4">$</span>-->
                                <ValidationProvider name="Power" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
                                <v-text-field
                                    v-model="commission.power"
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                    :error-messages=" errors[0]"
                                ></v-text-field>
                                </ValidationProvider>
<!--                            </div>-->
                            <p class="leade-text pr-5 mb-0">Per successful connection</p>
<!--                        </div>-->
                    </v-col>

                    <v-col cols="3" class="pa-0 pr-3">
<!--                        <div class="leade-badge">-->
                            <h4  class="mb-2"> <v-icon color="red">mdi-fire</v-icon> Gas</h4>
<!--                            <div class="leade-icon pb-2">-->

<!--                                <span class="mr-4">$</span>-->
                                <ValidationProvider name="Gas" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
                                <v-text-field
                                    v-model="commission.gas"
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                    :error-messages=" errors[0]"
                                ></v-text-field>
                                </ValidationProvider>
<!--                            </div>-->
                            <p class="leade-text pr-5">Per successful connection</p>
<!--                        </div>-->
                    </v-col>

                    <v-col cols="3" class="pa-0 pr-3">
<!--                        <div class="leade-badge">-->
                            <h4  class="mb-2"> <v-icon color="green">mdi-wifi</v-icon> Internet</h4>
<!--                            <div class="leade-icon pb-2">-->

<!--                                <span class="mr-4">$</span>-->
                               <ValidationProvider name="Internet" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
                                <v-text-field
                                    v-model="commission.internet"
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                    :error-messages=" errors[0]"
                                ></v-text-field>
                               </ValidationProvider>
<!--                            </div>-->
                            <p class="leade-text pr-5">Per successful connection</p>
<!--                        </div>-->
                    </v-col>

                    <!-- <v-col cols="3" class="pa-0">
                        <div class="leade-badge">
                            <h3>Water</h3>
                            <div class="leade-icon pb-2">
                                <v-icon color="grey lighten-1">mdi-water</v-icon>
                                <span class="mr-4">$</span>
                               <ValidationProvider name="Water" rules="numeric|max:2|min_value:1|required"  v-slot="{ errors }">
                                <v-text-field
                                    disabled
                                    v-model="commission.water"
                                    outlined
                                    dense
                                    hide-details
                                    placeholder="50"
                                ></v-text-field>
                               </ValidationProvider>
                            </div>
                            <p class="leade-text pr-5">Per successful connection</p>
                        </div>
                    </v-col> -->

                   <v-col cols="3" class="pa-0 pr-3">
<!--                       <div class="leade-badge">-->
                           <h4  class="mb-2">Sponsorship</h4>
<!--                           <div class="leade-icon pb-2">-->
<!--                               <v-icon color="green">mdi-wifi</v-icon>-->
<!--                               <span class="mr-4">$</span>-->
                               <ValidationProvider name="Sponsorship" rules="numeric|required"  v-slot="{ errors }">
                                   <v-text-field
                                       v-model.number="commission.sponsorship"
                                       outlined
                                       dense
                                       hide-details
                                       placeholder="10,000"
                                       :error-messages=" errors[0]"
                                   ></v-text-field>
                               </ValidationProvider>
<!--                           </div>-->
                           <p class="leade-text pr-5">Per Annum</p>
<!--                       </div>-->
                   </v-col>

               </v-row>

            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-btn @click="cancelChange">Cancel</v-btn>
                    <v-btn @click="saveChange">Save</v-btn>
                </v-col>
            </v-row>
        </v-card>
        </ValidationObserver>
        <CreateSuccessfulModal v-if="updateConfirmFlag" :is-update="updateConfirmFlag" :dialog="updateConfirmFlag" :title="office.name" @cancel="cancelSuccessfulModal">
        </CreateSuccessfulModal>
    </v-container>


</template>

<script>
import OfficeService from "@scripts/services/crm/OfficeService";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import HoodAgentDropdown from "@scripts/components/crm/office/HoodAgentDropdown";

export default {
  name: "OfficeProfile",
    components: {
        CreateSuccessfulModal,
        HoodAgentDropdown,
    },
    data() {
      return {
          hood_users: [],
          updateConfirmFlag: false,
          data: null,
          isLoaded : false,
          activeOffice: null,
          office: {
              id: null,
              name: null,
              address: null,
              phone: null,
              email: null,
              abn: null,
              agency_name: null,
              agency_type: null,
              agency_id: null,
              rent_roll: null,
              account_manager: null,
              hood_agent_id: null,
          },
          commission: {
              gas: null,
              water: null,
              power: null,
              internet: null,
              sponsorship: null
          },
          commissionObject: null,
          agent: {
              first_name: null,
              last_name: null,
              full_name: null,
              email: null,
              f_id_12: null,
              phone: null,
          },
          selectedAgentId: null,
      }
    },
    computed:{
        hoodAgentId(){
            return this.office?.hood_agent_id;
        }
    },
    methods:{
      onChangeAgent(agent){
          this.office.hood_agent_id = agent.id;
      },
      async loadOffice() {
          this.data = await OfficeService.loadOfficeById(this.activeOffice);
          await this.syncData();
          // console.log(this.office);
          this.isLoaded = true;
      },

       async syncData() {
           await this.updateOffice(this.data?.office);
           await this.updateCommission(this.data?.commissions);
           await this.updateAgent(this.data?.agent);
           this.hood_users = this.data?.hood_users;

        },

        updateAgent(data) {
            this.agent.id = data.id;
            this.agent.full_name = data.full_name;
            this.agent.first_name = data.first_name;
            this.agent.last_name = data.last_name;
            this.agent.phone = data.phone;
            this.agent.email = data?.user?.email;
            this.agent.f_id_12 = data.f_id_12;
        },

        updateOffice(data) {
            this.office.id = data.id;
            this.office.name = data.name;
            this.office.address = data.address;
            this.office.phone = data.phone;
            this.office.email = data.email;
            this.office.abn = data.abn;
            this.office.agency_name = data.agency_name;
            this.office.agency_type = data.agency_type;
            this.office.agency_id = data.agency_id;
            this.office.rent_roll = data.rent_roll;
            this.office.hood_agent_id = data.hood_agent_id;

        },

        updateCommission(commission) {

          commission.forEach((dt)=> {

              switch (dt.text)
              {
                  case 'gas':
                      this.commission.gas = parseInt(dt.rate);
                      break;
                  case 'water':
                      this.commission.water = parseInt(dt.rate);
                      break;
                  case 'power':
                      this.commission.power = parseInt(dt.rate);
                      break;
                  case 'internet':
                      this.commission.internet = parseInt(dt.rate);
                      break;
                  case 'sponsorship':
                      this.commission.sponsorship = parseInt(dt.rate);
                      break;

              }
          });
        },

        synCommissionbeforeSave()
        {
            // return this.data.commissions.map((dt) => {
            //     if(dt.text === 'gas') {
            //         dt.rate = this.commission.gas;
            //     }

            //     if(dt.text === 'water') {
            //         dt.rate = this.commission.water;
            //     }
            //     if(dt.text === 'power') {
            //         dt.rate = this.commission.power;
            //     }
            //     if(dt.text === 'internet') {
            //         dt.rate = this.commission.internet;
            //     }
            //     if(dt.text === 'sponsorship') {
            //         dt.rate = this.commission.sponsorship;
            //     }
            //     return dt;
            // });

            let commissions = [];
            let utilities = ['gas', 'water', 'power', 'internet', 'sponsorship'];

            utilities.forEach((utility) => {
                let item = this.data.commissions.find((dt) => {
                    return dt.text === utility;
                });
                if(item) {
                    item.rate = this.commission[utility];
                    commissions.push(item);
                } else {
                    commissions.push({
                        text: utility,
                        rate: this.commission[utility],
                    });
                }
            });
            return commissions;
        },

        cancelChange() {
            this.syncData();
        },

       async saveChange() {
           let v = await this.$refs.office_details.validate();
           if(!v) return;
            const officeData = {
                office: this.office,
                commissions: this.synCommissionbeforeSave(),
                agent: this.agent,
            };
          await OfficeService.updateOffice(officeData, this.activeOffice);
           this.updateConfirmFlag = true;
        },
        goToOffice() {
          this.$router.push({name:'real.state.agency.users',params:{id:this.$route.params.id, officeId: this.$route.params.officeId}});
        },

        cancelSuccessfulModal() {
          this.updateConfirmFlag = false;
            this.$router.push({name:'real.state.agency.users',params:{id:this.$route.params.id, officeId: this.$route.params.officeId}});

        }
    },
    mounted() {
      this.activeOffice = this.$route.params.officeId;
        this.loadOffice();
    }
};
</script>

<style scoped>
</style>
