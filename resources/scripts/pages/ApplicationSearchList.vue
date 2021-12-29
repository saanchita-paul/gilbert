<template>
  <div>
    <ApplicantTable
      :leadSrc="selectedSrc"
      :applications="applications"
      :totalItem="totalItem"
      :currentLead="currentLead"
      @refreshDataTable="refreshDataTable"
      @openLeadSummary="openLeadSummary"
      @updateLeadAndatrics="updateLeadAndatrics"
      :isSearching="isSearching"
    >
    </ApplicantTable>
  </div>
</template>

<script>
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
export default {
  name: "ApplicationSearchList",
  props: ["selectedSrc", "leads", "totalItem", "leadDetails", "applications" , "currentLead", 'isSearching'],
  components: { ApplicantTable },
  methods:{
    openLeadSummary(applicationId) {
      this.$emit("openLeadSummary", applicationId);
    },
    refreshDataTable(data) {
      this.$emit("refreshDataTable", data);
    },
    updateLeadAndatrics(leadId, userId) {
      this.$emit('updateLeadAndatrics',leadId,userId);
    },
  },
  mounted(){
    console.log("applicatons" , this.applications)
  }
};
</script>

<style lang="scss" scoped>
</style>
