<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Agency Details</p>
        </div>

        <div class="dialogs-area">
          <p class="title">What type of agency?</p>
          <v-select outlined dense :items="agencyType"  v-model="agency.type" label="Please select agency type">
          </v-select>
          <v-text-field
            label="Order ID"
            placeholder="Office name"
            outlined
            dense
          ></v-text-field>
        </div>
          <div class="d-flex justify-space-between">
              <v-btn @click="cancel"
              >Cancel
              </v-btn>
              <v-btn @click="saveAgency"
                     color="primary"
              >Save
              </v-btn>
          </div>
      </v-col>

    </v-row>
</template>

<script>
export default {
    name: "AgencyDetails",
    props:[],
    data(){
      return {
          agency:{
              type:null,
          },
        agencyType: [
            'Independent Agency',
             'Franchised Agency'
        ]
      };
    },
    methods: {
        cancel() {
            console.log('agen',this.agency);
            this.$emit('cancelDialog');
        },
        saveAgency() {
            console.log('agen',this.agency);
            this.$emit('saveAgency',this.agency);

        }
    }
};
</script>

<style scoped>
</style>
