<template>
    <v-row no-gutters>
        <v-col cols="12">
          <v-chip
            v-for="(item, index) in filterItems"
            :key="index"
            class="mx-2" color="#DDE2FF"
          >
            {{ item }}
          </v-chip>
        </v-col>
    </v-row>
</template>

<script>
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
    methods: {
      setFilterItems(){
          this.filterItems = [];
          if(this.searchFilterModel?.app_id && this.searchFilterModel?.app_id !== '') {
              this.filterItems.push('App ID: ' + this.searchFilterModel?.app_id);
          }
          if(this.searchFilterModel?.tenant_name && this.searchFilterModel?.tenant_name !== '') {
              this.filterItems.push('Tenant Name: ' + this.searchFilterModel?.tenant_name);
          }
          if(this.searchFilterModel?.address && this.searchFilterModel?.address !== '') {
              this.filterItems.push('Address: ' + this.searchFilterModel?.address);
          }
          if(this.searchFilterModel?.email && this.searchFilterModel?.email !== '') {
              this.filterItems.push('Email: ' + this.searchFilterModel?.email);
          }
          if(this.searchFilterModel?.agent_id && this.searchFilterModel?.agent_id !== '') {
              this.filterItems.push('Agent Name: ' + this.searchFilterModel?.agent_id);
          }
          if(this.searchFilterModel?.moving_date && this.searchFilterModel?.moving_date !== '') {
              this.filterItems.push('Moving Date: ' + this.searchFilterModel?.moving_date);
          }
          if(this.searchFilterModel?.source && this.searchFilterModel?.source !== '') {
              this.filterItems.push('Source: ' + this.searchFilterModel?.source);
          }
          if(this.searchFilterModel?.status && this.searchFilterModel?.status !== '') {
              this.filterItems.push('Status: ' + this.searchFilterModel?.status);
          }
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
