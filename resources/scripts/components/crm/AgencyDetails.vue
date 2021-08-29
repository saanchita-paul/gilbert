<template>
    <ValidationObserver ref="create_agency">
        <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Agency Details</p>
        </div>

        <div class="dialogs-area">
          <p class="title">What type of agency?</p>
            <ValidationProvider name="agencyType" rules="required"  v-slot="{ errors }">
                <v-select outlined dense :items="agencyType" item-text="title" item-value="id" v-model="agency.type" :error-messages=" errors[0]" label="Please select agency type"> </v-select>
              </ValidationProvider>
            <ValidationProvider name="Title" rules="required"  v-slot="{ errors }">
              <v-text-field v-model="agency.title"
                label="Company Name"
                placeholder="Barry Plant"
                outlined
                dense
                :error-messages=" errors[0]"
              ></v-text-field>
            </ValidationProvider>
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
    </ValidationObserver>
</template>

<script>
export default {
    name: "AgencyDetails",
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
        ]
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

<style scoped>
</style>
