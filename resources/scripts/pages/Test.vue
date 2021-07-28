<template>
  <div>
    <OfficeProfile></OfficeProfile>
  </div>
</template>

<script>
import OfficeDetails from "@scripts/components/crm/OfficeDetails";
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import AllocatorDetails from "@scripts/components/crm/AllocatorDetails";
import CommissionProfile from "@scripts/components/crm/CommissionProfile";
import Created from "@scripts/components/crm/Created";
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import OfficeProfile from "@scripts/components/crm/OfficeProfile";
export default {
  name: "Test",
  components: {
    AgencyDetails,
    OfficeDetails,
    AllocatorDetails,
    CommissionProfile,
    Created,
    LeadMetrics,
    OfficeProfile
  },
};
</script>

<style scoped>
</style>
