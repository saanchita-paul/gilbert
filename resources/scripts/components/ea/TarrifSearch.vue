<template>
   <div id="searchTarrif">
      <v-form ref="form" v-model="valid" lazy-validation>
         <v-col
            cols="12"
            sm="12"
            md="12"
            class="pb-0 mr-0"

            >
            <v-text-field
                full-width
                dense
               label="Please confirm your postcode"
               solo placeholder="eg 3000" type="number"  v-model="postcode"  :rules="searchRules" required
               ></v-text-field>
             <v-col cols="12">
                 <v-btn
                     :disabled="!valid"
                     color="success"
                     class="mr-5"
                     @click="validate"
                     small
                 >
                     Submit
                 </v-btn>
                 <v-btn
                     color="blue-grey"
                     class="mr-5 white--text"
                     @click="reset"
                     small
                 >
                     Clear
                 </v-btn>
             </v-col>
         </v-col>

      </v-form>
      <p class="font-weight-bold my-2 pt-1">Tariff types</p>
      <ul class="px-0 my-1">
         <div v-if=" utility === 'Electricity' || utility === 'Electricity & Gas' ">
            <p class="my-1 pt-1">Electricity</p>   
            <div v-if="!electricityLinks.length" class="error-message">
                <v-icon color="orange">mdi-alert-octagon</v-icon>
                There are no plans available for the search criteria you have entered. Please refine your search.
            </div>
            <div v-else>
                <li class="list-links" v-for="item in electricityLinks" :key="item.id">
                <a :href= "item.url">
                    {{ item.name }}
                    <v-icon color="black">mdi-launch</v-icon>
                </a>
                </li>       
            </div>     
         </div>
         <div v-if=" utility === 'Gas' || utility === 'Electricity & Gas' ">
            <p class="my-1 pt-1">Gas</p>
            <div v-if="!gasLinks.length" class="error-message">
                <v-icon color="orange">mdi-alert-octagon</v-icon>
                There are no plans available for the search criteria you have entered. Please refine your search.
            </div>
            <div v-else>
                <li class="list-links" v-for="item in gasLinks" :key="item.id">
                <a :href= "item.url">
                    {{ item.name }}
                    <v-icon color="black">mdi-launch</v-icon>
                </a>
                </li>
            </div>    
         </div>
      </ul>
   </div>
</template>


<script>
import CustomerService from "@src/services/CustomerService";

export default {
    name: "TariffSearch",
    props: ['utility', 'planPostcode'],

    data() {
        return {
            planType: this.$route.params.plan,
            valid: true,
            gasLinks: [],
            postcode: this.planPostcode,
            electricityLinks: [],
            searchRules: [
                v => !!v || 'Please enter a postcode',
                v => (v && v.length == 4) || 'Postcode must contain 4 digits',
            ],
        }
    },

    mounted() {
      this.searchQuery = this.planPostcode
      this.getData()
    },

    watch: {
        planType(newVal) {
            this.planType = newVal;
        },
        planPostcode: val => this.postcode = val

    },
    methods: {
        async validate () {
            if (await this.$refs.form.validate()) {
                this.getData();
            }

        },
        reset () {
            this.$refs.form.reset()
        },
        async getData() {
          if (this.planType && this.$route.params.customerId!= null && this.searchQuery!=null ) {
            const data = await CustomerService.getTarrifDetailsByPostcode(this.postcode, this.planType, this.$route.params.customerId )
            let elecArr = [...data["electricity"]]
            let gasArr = [...data["gas"]]
            this.electricityLinks = this.removeDuplicates(elecArr, item => item.url)
            this.gasLinks = this.removeDuplicates(gasArr, item => item.url)
          }
        },

        removeDuplicates(data, key) {
            return [
                ...new Map(data.map(item => [key(item), item])).values()
            ]
        }
    },
}
</script>

<style scoped>
.list-links{
    background-color: #eaeaea;
    margin: 5px 0;
    padding: 8px;
    color: #333;
}
.error-message {
    background-color: #eaeaea;
    margin: 5px 0;
    padding: 8px;
}
.list-links>a{
    text-decoration: none;
    color: #333;
}
.list-links>a>i::before{
    text-decoration: none;
    font-size: 18px;
    padding-left: 3px
}
.list-links:hover {
    background-color: #ecf7f9;
}
</style>
