<template>
  <div>
    <ApplicationDetails></ApplicationDetails>
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
import AddNewApplication from "@scripts/components/crm/application/AddNewApplication";
import ApplicationDetails from "@scripts/components/crm/application/ApplicationDetails";
export default {
  name: "Test",
  components: {
    AgencyDetails,
    OfficeDetails,
    AllocatorDetails,
    CommissionProfile,
    Created,
    LeadMetrics,
    OfficeProfile,
    AddNewApplication,
    ApplicationDetails
  },
};
</script>

<style scoped>
</style>
