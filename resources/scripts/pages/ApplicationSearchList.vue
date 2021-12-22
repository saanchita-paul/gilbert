<template>
  <div>
    <ApplicantTable
      :leadSrc="selectedSrc"
      :applications="leads"
      :totalItem="totalItem"
      :currentLead="leadDetails"
    >
    </ApplicantTable>
  </div>
</template>

<script>
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
export default {
  name: "ApplicationSearchList",
  props: ["selectedSrc", "leads", "totalItem", "leadDetails"],
  components: { ApplicantTable },
};
</script>

<style lang="scss" scoped>
</style>