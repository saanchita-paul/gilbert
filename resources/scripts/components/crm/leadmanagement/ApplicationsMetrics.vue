<template>
    <div class="d-flex justify-start">
        <single-lead-type title="My Applications" type="my_applications" :count="myAppCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Assigned to me"> </single-lead-type>
        <single-lead-type title="Unassigned" type="unassigned" :count="unassignCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Waiting on queue"> </single-lead-type>
        <single-lead-type title="Assigned" type="assigned" :count="assignCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Being attended"> </single-lead-type>
        <single-lead-type title="Escalated" type="escalated" :count="escalatedCount" :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Needs attention"> </single-lead-type>
        <single-lead-type title="Submitted" type="submitted" :count="submittedCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="For connection"> </single-lead-type>
        <single-lead-type title="Closed" type="closed" :count="closedCount"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="have been closed"> </single-lead-type>
        <single-lead-type title="Duplicates" type="duplicates" :count="closedCount" :showDuplicate="showDuplicate"  :active="activeLeadType" @changeLeadType="changeLeadType" subtext="Similar Leads"> </single-lead-type>

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
        },
        showDuplicate: {
            required: false,
        }
    },
    data() {
      return {
          myAppCount: 0,
          unassignCount: 0,
          assignCount: 0,
          escalatedCount: 0,
          submittedCount: 0,
          closedCount: 0,
          activeLead: 'My Applications'
      }
    },

    methods: {
      updateCount() {
         let totalLeads = 0;
          this.leads.forEach(lead=>{
              switch (lead.type)
              {
                  case 'my_application':
                      this.myAppCount = lead.count;
                      break;
                  case 'unassigned':
                      totalLeads += lead.count;
                      this.unassignCount = lead.count;
                      break;
                  case 'assigned':
                      totalLeads += lead.count;
                      this.assignCount = lead.count;
                      break;
                  case 'escalated':
                      totalLeads += lead.count;
                      this.escalatedCount = lead.count;
                      break;
                  case 'submitted':
                      totalLeads += lead.count;
                      this.submittedCount = lead.count;
                      break;
                  case 'closed':
                      totalLeads += lead.count;
                      this.closedCount = lead.count;
                      break;
                  default:
                      break
              }
          });
          this.$emit('updateTotal', totalLeads);
      },
        changeLeadType(type) {
            this.activeLead = type;
            this.$emit('resetPage');
            let query =omit({...this.$route.query}, ['type', 'duplicates']);

            if(type === 'duplicates') {
                this.$router.push({query:{duplicates:true, ...query}});
            } else {
                this.$router.push({query:{type:type, ...query}});
            }

        },
    },

    mounted() {
      this.updateCount();

    }

};
</script>

<style scoped>
</style>
