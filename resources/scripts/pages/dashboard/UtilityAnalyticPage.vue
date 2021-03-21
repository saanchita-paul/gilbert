<template>
    <div class="analytic-bg hood-gradiant">
        <v-container>
            <div class="my-lead-head mb-2">
                <h3 class="section-title">My leads overview</h3>
                <div>
                    <v-btn>filter
                        <v-icon>mdi-filter-outline</v-icon>
                    </v-btn>
                </div>
            </div>
            <div class="widgets">
                <LeadWidget chart-id="totalLead"/>
                <LeadWidget chart-id="totalLead2"/>
                <LeadWidget chart-id="totalLead3"/>
                <LeadWidget chart-id="totalLead4"/>
                <LeadWidget chart-id="totalLead5"/>
            </div>
        </v-container>
    </div>
</template>

<script>
import LeadWidget from "@scripts/components/widgets/LeadWidget";

export default {
    name: "UtilityAnalyticPage",
    components: { LeadWidget }
}
</script>

<style scoped lang="scss">
.my-lead-head {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.widgets {
    display: flex;
    flex-direction: row;
}
</style>
