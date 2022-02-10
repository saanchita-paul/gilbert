<template>
    <v-card class="card-style">
        <div class="">
            <h3 class="page-title-text">Here's a summary of all created applications/tenants</h3>
            <p class="my-2">
                As of <span class="font-weight-medium">{{ selectedDate }}</span>.
            </p>
            <p class="text-light">You can change these parameters using the filters on the top right corner.</p>
        </div>

        <div class="metrics pa-4">

            <!--            Application Created-->
            <div class="created with-source mx-2">
                <div class="lead-count">
                    <div class="count-value"><h2>{{ summaryData.source_all.total }}</h2></div>
                    <div class="count-label"><p>Application created</p></div>
                </div>
                <div class="lead-sources">
                    <p class="lead-source-value"><span>{{ summaryData.source_all.ignite }}</span> Ignite</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_all.our_property }}</span> Our Property</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_all.property_me }}</span> PropertyMe</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_all.foxie }}</span> Foxie</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_all.hood }}</span> Hood</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_all.hood_ai }}</span> Hood.ai</p>
                </div>
            </div>
            <v-divider vertical class="divider"></v-divider>

            <!--            Application unassigned-->
            <div class="unassigned lead-count mx-2">
                <div class="count-value"><h2>{{ summaryData.source_unassigned.total }}</h2></div>
                <div class="count-label"><p>Unassigned</p></div>
            </div>
            <v-divider vertical class="divider"></v-divider>

            <!--            Application assigned-->
            <div class="assigned lead-count">
                <div class="count-value"><h2>{{ summaryData.source_assigned.total }}</h2></div>
                <div class="count-label"><p>Assigned</p></div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application submitted-->
            <div class="submitted with-source mx-2">
                <div class="lead-count">
                    <div class="count-value"><h2>{{ summaryData.source_submitted.total }}</h2></div>
                    <div class="count-label"><p>Submitted to retailer</p></div>
                </div>
                <div class="lead-sources">
                    <p class="lead-source-value"><span>{{ summaryData.source_submitted.ignite }}</span> Ignite</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_submitted.our_property }}</span> Our Property</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_submitted.property_me }}</span> PropertyMe</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_submitted.foxie }}</span> Foxie</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_submitted.hood }}</span> Hood</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_submitted.hood_ai }}</span> Hood.ai</p>
                </div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application conversion-rate-->
            <div class="conversion-rate with-source mx-2">
                <div class="lead-count">
                    <div class="count-value"><h2>{{ summaryData.source_conversation_rate.total }}%</h2></div>
                    <div class="count-label"><p>Overall Conversion Rate</p></div>
                </div>
                <div class="lead-sources">
                    <p class="lead-source-value"><span>{{ summaryData.source_conversation_rate.ignite }}%</span> Ignite</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_conversation_rate.our_property }}%</span> Our Property</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_conversation_rate.property_me }}%</span> PropertyMe</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_conversation_rate.foxie }}%</span> Foxie</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_conversation_rate.hood }}%</span> Hood</p>
                    <p class="lead-source-value"><span>{{ summaryData.source_conversation_rate.hood_ai }}%</span> Hood.ai</p>
                </div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application rea??-->
            <div class="rea lead-count mx-2" style="opacity: 0.2;">
                <div class="count-value"><h2>{{ summaryData.source_consent_pending.total }}</h2></div>
                <div class="count-label"><p>Consent Pending </p></div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application closed-->
            <div class="closed lead-count mx-2">
                <div class="count-value"><h2>{{ summaryData.source_closed.total }}</h2></div>
                <div class="count-label"><p>Closed</p></div>
            </div>
        </div>
    </v-card>
</template>

<script>
import {getFormattedDBDate, getToday, getYesterday, isSame} from "@scripts/services/DateRangeService";

export default {
    name: "ApplicationSummary",
    props: ["summaryData", "dateRange"],
    computed: {
        selectedDate() {
            let today = getToday();
            let yesterday = getYesterday();
            if (isSame(this.dateRange.start, today)) {
                return 'Today';
            } else if (isSame(this.dateRange.start, yesterday)) {
                return 'Yesterday';
            } else {
                return `${getFormattedDBDate(this.dateRange.start)} - ${getFormattedDBDate(this.dateRange.end)}`;
            }
        }
    },
}
</script>

<style lang="scss" scoped>
.page-title-text {
    //styleName: Heading 4;
    font-family: Roboto;
    font-size: 32px;
    font-style: normal;
    font-weight: 700;
    line-height: 42px;
    letter-spacing: 0em;
    text-align: left;

}

.text-light {
    font-size: 12px;
}

.lead-count {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.lead-sources {
    margin: 0px 8px 0px 8px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    p span {
        font-weight: bold;
        //font-size: .7em;
    }
}

.lead-source-value {
    margin: 0px;
    font-size: .7em;
}

.with-source {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
}

.count-label {
    font-size: .7em;
    color: #263238;
}
.count-value  {
    color: #5C229A;
    font-size: 1.6em;
}
.metrics {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-items: center;
}

.divider {
    border-color: #41464b !important;
}

.card-style{
    padding: 24px;
}
</style>
