<template>
    <div class='d-flex'>
        <v-spacer></v-spacer>
        <v-select
            class='type-select'
            :items="serviceType"
            item-text="title"
            item-value="value"
            v-model="selectedType"
            @change="changeServiceType"
            solo
            dense
            label="Type"
            hide-details
        ></v-select>
        <v-text-field
            class='date-select'
            solo
            dense
            label="Calender"
            placeholder="Today"
            v-model="selectedDate"
            append-icon="mdi-calendar-range"
            readonly
            hide-details
            @click="showDatePickerModal = true"
            @click:append="showDatePickerModal = true"
        ></v-text-field>
        <v-btn class='download-button' @click="downloadSalesReport">
            Download
            <v-icon right>mdi-download</v-icon>
        </v-btn>
        <DatePickerModal
            v-if="showDatePickerModal"
            :dialog="showDatePickerModal"
            :dateRange="dateRange"
            @select="onSelectDate"
            @close="onCloseModal"
        />
    </div>
</template>

<script>
import DatePickerModal from "@scripts/modules/sales/components/DatePickerModal";
import {getToday, getTodayString, getYesterday, isSame, getFormattedDBDate} from '@scripts/services/DateRangeService';
import dayJs from "dayjs";

export default {
    name: "SalesFilter",
    components: {
        DatePickerModal,
    },
    data() {
        return {
            serviceType: [
                {
                    title:  'Energy',
                    value: 'energy',
                },
                {
                    title:  'Water',
                    value: 'water',
                },
            ],
            showDatePickerModal: false,
            dateRange: {
                start: this.$route.query?.start ?
                    this.$route.query?.start : getTodayString(),
                end:  this.$route.query?.end ?
                    this.$route.query?.end : getTodayString()
            },
            selectedDate: null,
            selectedType: null,
        }
    },
    methods: {
        onSelectDate(dateRange) {
            this.dateRange = dateRange;
            this.showDatePickerModal = false;

            let queries = JSON.parse(JSON.stringify(this.$route.query));
            queries.start = this.dateRange.start;
            queries.end = this.dateRange.end;
            this.$router.replace({ query: queries });
        },
        onCloseModal() {
            this.showDatePickerModal = false;
        },
        changeServiceType() {
            this.selectedType === 'energy' ?
                this.$router.push({
                    name: 'sales.energy',
                    query: { start: this.dateRange.start, end: this.dateRange.end }
                }) :
                this.$router.push({
                    name: 'sales.water',
                    query: { start: this.dateRange.start, end: this.dateRange.end }
                });
        },
        updateDateRange() {
            this.$emit('updateDate', this.dateRange)
        },
        checkDate() {
            let today = getToday();
            let yesterday = getYesterday();
            if(isSame(this.dateRange.start, today)) {
                this.selectedDate = 'Today';
            } else if(isSame(this.dateRange.start, yesterday)) {
                this.selectedDate = 'Yesterday';
            } else {
                this.selectedDate = `${getFormattedDBDate(this.dateRange.start)} - ${getFormattedDBDate(this.dateRange.end)}`;
            }
        },
        downloadSalesReport() {
            window.open(
                '/api/sales-dashboard/export/submission-report?type='+this.selectedType+'&start='+this.dateRange.start+'&end='+this.dateRange.end,
                '_blank'
            );
        }
    },
    watch: {
        dateRange() {
            this.checkDate();
            this.updateDateRange();
        }
    },
    mounted() {
        this.checkDate();
        this.updateDateRange();
        this.selectedType = this.$route.name === 'sales.energy' ? 'energy' : 'water'; 
    }
};
</script>

<style scoped>
    .type-select {
        max-width: 180px;
        margin-right: 8px;
    }
    .date-select {
        max-width: 250px;
        margin-right: 8px;
    }
    .download-button {
        background-color: white !important;
        max-width: 180px;
        margin-right: 8px;
    }
</style>
