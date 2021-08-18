<template>
    <div class="d-flex justify-start">
        <single-lead-type title="My Applications" type="my_applications" :count="myAppCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Assigned to me"> </single-lead-type>
        <single-lead-type title="Unassigned" type="unassigned" :count="unassignCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Waiting on queue"> </single-lead-type>
        <single-lead-type title="Assigned" type="assigned" :count="assignCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Being attended"> </single-lead-type>
        <single-lead-type title="Escalated" type="escalated" :count="escalatedCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Needs attention"> </single-lead-type>
        <single-lead-type title="Submitted" type="submitted" :count="submittedCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="For connection"> </single-lead-type>

    </div>
</template>

<script>
import SingleLeadType from "@scripts/components/crm/leadmanagement/SingleLeadType";
import {omit} from "lodash-es";
export default {
  name: "ApplicationsMetrics",
    components: {SingleLeadType},
    props:{
        leads: {
            required: true,
        },
        activeLeadType: {
            required: true,
        }
    },
    data() {
      return {
          myAppCount: 0,
          unassignCount: 0,
          assignCount: 0,
          escalatedCount: 0,
          submittedCount: 0,
          activeLead: 'My Applications'
      }
    },

    methods: {
      updateCount() {
         let totalLeads = 0;
          this.leads.forEach(lead=>{
              switch (lead.title)
              {
                  case 'My Applications':
                      totalLeads += lead.lead_count;
                      this.myAppCount = lead.lead_count;
                      break;
                  case 'Unassigned':
                      totalLeads += lead.lead_count;
                      this.unassignCount = lead.lead_count;
                      break;
                  case 'Assigned':
                      totalLeads += lead.lead_count;
                      this.assignCount = lead.lead_count;
                      break;
                  case 'Escalated':
                      totalLeads += lead.lead_count;
                      this.escalatedCount = lead.lead_count;
                      break;
                  case 'Submitted':
                      totalLeads += lead.lead_count;
                      this.submittedCount = lead.lead_count;
                      break;
                  default:
                      break
              }
          });
          this.$emit('updateTotal', totalLeads);
      },
        changeLeadType(type) {
            this.activeLead = type;
            let query =omit({...this.$route.query}, 'type');
            this.$router.push({query:{type:type, ...query}});
        }
    },

    mounted() {
      this.updateCount();

    }

};
</script>

<style scoped>
</style>
