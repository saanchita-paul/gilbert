<template>
    <v-card>
        <div class="py-5 px-3">
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
                    <div class="count-value"><h2>60</h2></div>
                    <div class="count-label"><p>Application created</p></div>
                </div>
                <div class="lead-sources">
                    <p class="lead-source-value"><span>123</span> Ignite</p>
                    <p class="lead-source-value"><span>123</span> Our Property</p>
                    <p class="lead-source-value"><span>123</span> PropertyMe</p>
                    <p class="lead-source-value"><span>123</span> Foxie</p>
                    <p class="lead-source-value"><span>123</span> Hood</p>
                    <p class="lead-source-value"><span>123</span> Hood.ai</p>
                </div>
            </div>
            <v-divider vertical class="divider"></v-divider>

            <!--            Application unassigned-->
            <div class="unassigned lead-count mx-2">
                <div class="count-value"><h2>30</h2></div>
                <div class="count-label"><p>Unassigned</p></div>
            </div>
            <v-divider vertical class="divider"></v-divider>

            <!--            Application assigned-->
            <div class="assigned lead-count">
                <div class="count-value"><h2>20</h2></div>
                <div class="count-label"><p>Assigned</p></div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application submitted-->
            <div class="submitted with-source mx-2">
                <div class="lead-count">
                    <div class="count-value"><h2>20,008</h2></div>
                    <div class="count-label"><p>Submitted to retailer</p></div>
                </div>
                <div class="lead-sources">
                    <p class="lead-source-value"><span>123</span> Ignite</p>
                    <p class="lead-source-value"><span>123</span> Our Property</p>
                    <p class="lead-source-value"><span>123</span> PropertyMe</p>
                    <p class="lead-source-value"><span>123</span> Foxie</p>
                    <p class="lead-source-value"><span>123</span> Hood</p>
                    <p class="lead-source-value"><span>123</span> Hood.ai</p>
                </div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application conversion-rate-->
            <div class="conversion-rate with-source mx-2">
                <div class="lead-count">
                    <div class="count-value"><h2>47.6%</h2></div>
                    <div class="count-label"><p>Overall Conversion Rate</p></div>
                </div>
                <div class="lead-sources">
                    <p class="lead-source-value"><span>123</span> Ignite</p>
                    <p class="lead-source-value"><span>123</span> Our Property</p>
                    <p class="lead-source-value"><span>123</span> PropertyMe</p>
                    <p class="lead-source-value"><span>123</span> Foxie</p>
                    <p class="lead-source-value"><span>123</span> Hood</p>
                    <p class="lead-source-value"><span>123</span> Hood.ai</p>
                </div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application rea??-->
            <div class="rea lead-count mx-2">
                <div class="count-value"><h2>1</h2></div>
                <div class="count-label"><p>title </p></div>
            </div>
            <v-divider vertical class="divider"></v-divider>


            <!--            Application closed-->
            <div class="closed lead-count mx-2">
                <div class="count-value"><h2>10</h2></div>
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
</style>
