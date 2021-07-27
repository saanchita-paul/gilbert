<template>
    <div>
       <AgencyDetails></AgencyDetails>
    </div>
</template>

<script>
import OfficeDetails from "@scripts/components/crm/OfficeDetails";
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
export default {
name: "Test",
    components: {AgencyDetails, OfficeDetails}
}
</script>

<style scoped>

</style>
