<template>
    <v-row v-if="filterItems.length>0" no-gutters>
        <v-col cols="12">
          <v-chip
            v-for="(item, index) in filterItems"
            :key="index"
            class="mx-1 mb-1" color="#DDE2FF"
          >
            {{ item }}
          </v-chip>
          <v-chip
            class="mx-1 close"
            color="#D0D0D0"
            @click="removeFilters"
          >
            Clear filters
            <v-icon small>mdi-close</v-icon>
          </v-chip>
        </v-col>
    </v-row>
</template>

<script>
import { sources } from '@scripts/data/LeadSourceMap';
import { statusesForAgent } from '@scripts/data/ConnectionStatusMapper';
import { formatDate } from "@scripts/services/others/DateService"
import dayJs from "dayjs";
export default {
    props: ["searchFilterModel"],
    data() {
      return {
        filterItems: []
      }
    },
    mounted() {
      this.setFilterItems();
    },
    computed:{
      source(){
        return sources.find(n=>n.value==this.searchFilterModel?.source)?.text ?? "";
      },
      status(){
        return statusesForAgent.find(n=>n.value==this.searchFilterModel?.active_lead_type)?.text ?? "";
      },
      moving_date(){
        let formattedDate = formatDate(this.searchFilterModel?.moving_date);
        if(formattedDate){
          return formatDate(this.searchFilterModel?.moving_date);
        } else if(dayJs(this.searchFilterModel?.moving_date, 'DD/MM/YYYY').isValid()){
          return this.searchFilterModel?.moving_date;
        } else {
          return "";
        }
      }
    },
    methods: {
      setFilterItems(){
          this.filterItems = [];
          if(this.searchFilterModel?.app_id && this.searchFilterModel?.app_id !== '') {
              this.filterItems.push('App ID: ' + this.searchFilterModel?.app_id);
          }
          if(this.searchFilterModel?.tenant_name && this.searchFilterModel?.tenant_name !== '') {
              this.filterItems.push('Tenant: ' + this.searchFilterModel?.tenant_name);
          }
          if(this.searchFilterModel?.address && this.searchFilterModel?.address !== '') {
              this.filterItems.push('Address: ' + this.searchFilterModel?.address);
          }
          if(this.searchFilterModel?.tenant_email && this.searchFilterModel?.tenant_email !== '') {
              this.filterItems.push('Email: ' + this.searchFilterModel?.tenant_email);
          }
          if(this.searchFilterModel?.agent_id && this.searchFilterModel?.agent_id !== '') {
              this.filterItems.push('Agent: ' + this.searchFilterModel?.agent_name);
          }
          if(this.searchFilterModel?.moving_date && this.searchFilterModel?.moving_date !== '') {
              this.filterItems.push('Move Date: ' + this.moving_date);
          }
          if(this.searchFilterModel?.source && this.searchFilterModel?.source !== '') {
              this.filterItems.push('Source: ' + this.source); 
          }
          if(this.searchFilterModel?.active_lead_type && this.searchFilterModel?.active_lead_type !== '') {
              this.filterItems.push('Status: ' + this.status); 
          }
          if(this.searchFilterModel?.phone && this.searchFilterModel?.phone !== '') {
              this.filterItems.push('Phone: ' + this.searchFilterModel?.phone);
          }
      },
      removeFilters(){
          this.$emit('removeFilters');
      }
    },
  watch: {
    searchFilterModel: {
      handler () {
        this.setFilterItems();
      },
      deep: true,
    },
  }
}
</script>

<style scoped>
.close {
  cursor: pointer;
}
</style>
