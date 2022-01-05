<template>
<div>
  <div class="d-flex header justify-space-between align-center mb-6">
    <div class="font-weight-bold ml-2">Advanced Search</div>
    <div class="mr-2" @click="cancel"><v-icon color="white"> mdi-close </v-icon> Close </div>
  </div>

  

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">APP ID</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-text-field outlined dense full-width hide-details="auto" label="App Id" placeholder="App Id"></v-text-field>
    </div>
  </div>

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">Tenant Name</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-text-field outlined dense full-width hide-details="auto" label="Tenant Name"></v-text-field>
    </div>
  </div>

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">Address</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-text-field outlined dense full-width hide-details="auto" label="Address"></v-text-field>
    </div>
  </div>

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">Email</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-text-field outlined dense full-width hide-details="auto" label="Email"></v-text-field>
    </div>
  </div>

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">Moving Date</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-text-field outlined dense full-width hide-details="auto" label="Moving Date"></v-text-field>
    </div>
  </div>

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">Source</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-select 
          outlined 
          dense
          full-width 
          hide-details="auto"
          v-model="source"
          :items="sources"
          label="Source">
      </v-select>
    </div>
  </div>

  <div class="d-flex justify-space-between align-center py-2">
    <div class="font-weight-bold ml-6" style="flex-basis: 15%;">Status</div>
    <div style="flex-basis: 65%;" class="mr-6">
      <v-select 
          outlined 
          dense
          full-width 
          hide-details="auto"
          v-model="status"
          :items="statuses"
          label="Status">
      </v-select>
    </div>
  </div>

  <div class="d-flex justify-center pb-2">
    <div style="flex-basis: 25%;">
      <v-btn small block color="#E0E0E0" class="black--text"> <v-icon> mdi-close </v-icon> Clear Filter</v-btn>
    </div>
  </div>

  <div class="d-flex justify-center pb-4">
    <div style="flex-basis: 90%;">
      <v-btn block color="#542E89" class="white--text">Search</v-btn>
    </div>
  </div>


</div>
</template>

<script>
export default {
    name: "AgencyAdvanceSearchDetails",
    props:[],
    data(){
      return {
          agency:{
              type:'',
              title: '',
          },
        agencyType: [
            {
                id: 0,
                title:  'Independent Agency',
            },
            {
                id: 1,
                title:  'Franchised Agency',
            },
        ],
        sources:['Hood', 'Foxie'],
        source: '',
        statuses:['Active', 'Processing'],
        status: '',
      };
    },
    methods: {
        cancel() {
            this.$emit('cancelDialog');
        },
       async saveAgency() {

            let v = await this.$refs.create_agency.validate();
            if (v) {
                this.$emit('saveAgency',this.agency);
            }
            return v;



        }
    }
};
</script>

<style lang="scss" scoped>
  .header{
    background: #542E89;
    color: white;
    height: 50px;
    width: 500px;
  }
</style>
