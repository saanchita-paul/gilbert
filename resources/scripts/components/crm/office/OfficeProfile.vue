<template>
    <v-container>
        <v-card class="pa-4" v-if="isLoaded">
            <v-row>
                <v-col cols="12 pb-0">
                    <v-btn @click="goToOffice"><v-icon left dark>mdi-arrow-left</v-icon>Back to Sunbury Office Metrics</v-btn>
                    <h3 class="page-title my-5 pt-5">{{office.name}} Office Profile</h3>
                    <p class="sub-title mb-0">Branch/Office Details</p>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Office Branch Location*"
                        outlined
                        dense
                        v-model="office.name"

                        placeholder="Sunbury"
                    ></v-text-field>
                    <v-text-field
                        label="Office Address*"
                        v-model="office.address"
                        outlined
                        dense
                        placeholder="398 Bourke Road, Camberwell 3124 VIC"
                    ></v-text-field>
                    <v-text-field
                        label="Phone Number (Optional)"
                        outlined
                        dense
                        v-model="office.phone"
                        placeholder="+61"
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Email Address (Optional)"
                        outlined
                        v-model="office.email"
                        dense
                        placeholder="example@domain.com"
                    ></v-text-field>
                    <v-text-field
                        label="ABN (Optional)"
                        outlined
                        dense
                        v-model="office.abn"
                        placeholder="00000000000"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" class="py-0">
                    <p class="sub-title mb-0">Branch Allocator Details</p>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Office Branch Location*"
                        v-model="agent.full_name"
                        outlined
                        dense
                        placeholder="Branch Location"
                    ></v-text-field>
                    <v-text-field
                        label="User ID*"
                        v-model="agent.email"
                        outlined
                        dense
                        placeholder="firstname.lastname@barryplantcamberwell.com.au"
                    ></v-text-field>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Email Address*"
                        v-model="agent.email"
                        outlined
                        dense
                        placeholder="example@domain.com"
                    ></v-text-field>

                    <v-text-field
                        label="Phone Number"
                        v-model="agent.phone"
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
                                    v-model="commission.power"
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
                                    v-model="commission.gas"
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
                                    v-model="commission.internet"
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
                                    v-model="commission.water"
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
            <v-row>
                <v-col cols="12">
                    <v-btn @click="cancelChange">Cancel</v-btn>
                    <v-btn @click="saveChange">Save</v-btn>
                </v-col>
            </v-row>
        </v-card>
        <CreateSuccessfulModal v-if="updateConfirmFlag" :is-update="updateConfirmFlag" :dialog="updateConfirmFlag" :title="office.name" @cancel="cancelSuccessfulModal">
        </CreateSuccessfulModal>
    </v-container>


</template>

<script>
import OfficeService from "@scripts/services/crm/OfficeService";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
export default {
  name: "OfficeProfile",
    components: {
        CreateSuccessfulModal,
    },
    data() {
      return {
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
          },
          commission: {
              gas:null,
              water:null,
              power:null,
              internet:null
          },
          commissionObject: null,
          agent: {
              first_name: null,
              last_name: null,
              full_name: null,
              email: null,
              user_id: null,
              phone: null,
          }
      }
    },
    methods:{
      async loadOffice() {
          this.data = await OfficeService.loadOfficeById(this.activeOffice);
          this.syncData();
          this.isLoaded = true;
      },

        syncData() {
            this.updateOffice(this.data?.office);
            this.updateCommission(this.data?.commissions);
            this.updateAgent(this.data?.agent);
        },

        updateAgent(data) {
            this.agent.id = data.id;
            this.agent.full_name = data.full_name;
            this.agent.phone = data.phone;
            this.agent.email = data.email;
        },

        updateOffice(data) {
            this.office.id = data.id;
            this.office.name = data.name;
            this.office.address = data.address;
            this.office.phone = data.phone;
            this.office.email = data.email;
            this.office.abn = data.abn;

        },

        updateCommission(commission) {

          commission.forEach((dt)=> {

              switch (dt.text)
              {
                  case 'gas':
                      this.commission.gas = dt.rate;
                      break;
                  case 'water':
                      this.commission.water = dt.rate;
                      break;
                  case 'power':
                      this.commission.power = dt.rate;
                      break;
                  case 'internet':
                      this.commission.internet = dt.rate;
                      break;

              }
          });
        },

        synCommissionbeforeSave()
        {
          return   this.data.commissions.map((dt)=> {
                if(dt.text === 'gas') {
                    dt.rate = this.commission.gas;
                }

                if(dt.text === 'water') {
                    dt.rate = this.commission.water;
                }
                if(dt.text === 'power') {
                    dt.rate = this.commission.power;
                }
                if(dt.text === 'internet') {
                    dt.rate = this.commission.internet;
                }
                return dt;
            }
            );
        },

        cancelChange() {
            this.syncData();
        },

       async saveChange() {
            let fullName = this.agent.full_name.split(' ');
            this.agent.first_name = fullName[0];
            this.agent.last_name = fullName.slice(1).join(' ', );
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
