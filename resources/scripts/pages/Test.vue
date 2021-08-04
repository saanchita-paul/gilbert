<template>
  <div>
    <ConfirmSubmission></ConfirmSubmission>
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
import LeadApplications from "@scripts/components/crm/leadmanagement/LeadApplications";
import AssignedtoPopUp from "@scripts/components/crm/leadmanagement/AssignedtoPopUp";
import ApplicationDetailScreen from "@scripts/components/crm/leadmanagement/ApplicationDetailScreen";
import ConfirmSubmission from "@scripts/components/crm/leadmanagement/ConfirmSubmission";
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
    LeadApplications,
    AssignedtoPopUp,
    ApplicationDetailScreen,
    ConfirmSubmission
  },
};
</script>

<style scoped>
</style>
