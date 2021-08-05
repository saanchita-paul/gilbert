<template>
    <div class="d-flex justify-start">
        <single-lead-type title="My Applications" :count="myAppCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Assigned to me"> </single-lead-type>
        <single-lead-type title="Unassigned" :count="unassignCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Waiting on queue"> </single-lead-type>
        <single-lead-type title="Assigned" :count="assignCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Being attended"> </single-lead-type>
        <single-lead-type title="Escalated" :count="escalatedCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Needs attention"> </single-lead-type>
        <single-lead-type title="Submitted" :count="submittedCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="For connection"> </single-lead-type>

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
        changeLeadType(title) {
            this.activeLead = title;
            let query =omit({...this.$route.query}, 'type');
            this.$router.push({query:{type:title, ...query}});
        }
    },

    mounted() {
      this.updateCount();

    }

};
</script>

<style scoped>
</style>
