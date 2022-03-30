<template>
    <v-dialog
        v-model="dialog"
        persistent
        max-width="800px"
    >
        <v-card>
            <v-toolbar color="primary" dark>
                <v-toolbar-title>Report Download</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn depressed right color="primary" @click="dialog = false">
                    Close
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>
            <section class="pa-4">
                <v-row>
                    <v-col cols="7">
                        <p>Select date</p>
                        <v-row>
                            <v-col cols="4">
                                <v-card
                                    class="mx-auto mt-3"
                                    max-width="300"
                                    elevation="0"
                                >
                                    <v-list>
                                        <v-subheader >Presets</v-subheader>
                                        <v-list-item-group
                                            v-model="selectedPreset"
                                        >
                                            <v-list-item
                                                v-for="(item, i) in presetItems"
                                                :key="i"
                                            >
                                            <v-list-item-content>
                                                <v-list-item-title v-text="item"></v-list-item-title>
                                            </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </v-list>
                                </v-card>
                            </v-col>
                            <v-col cols="8" class="calender-view py-0">
                                <v-text-field
                                    class="date-range-field"
                                    v-model="dateRangeText"
                                    label="Date range (YYYY/MM/DD)"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                ></v-text-field>
                                <v-date-picker
                                    class="date-range-picker"
                                    v-model="dates"
                                    range
                                    no-title
                                    elevation="1"
                                ></v-date-picker>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="5">
                        <p>Select the report type</p>
                    </v-col>
                </v-row>
            </section>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="blue darken-1"
                    text
                    @click="close"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="blue darken-1"
                    text
                    @click="select"
                >
                    Apply
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { 
    getTodayString, getYesterdayString, getToday, isBefore, isAfter,
    getYesterday, getFormattedDateString, getFormattedDBDate, isSame
} from '@scripts/services/DateRangeService';

export default {
    name: "DatePickerModal",
    props:{
        dialog: {
            require: true,
        },
        dateRange: {
            require: true,
        }
    },
    data() {
        return {
            presetItems: ['Today', 'Yesterday'],
            dates: this.getDates(this.dateRange),
            selectedPreset: this.getPresetIndex(this.dateRange),
        }
    },
    computed: {
        dateRangeText() {
            if(this.dates.length > 1) {
                return isBefore(this.dates[0], this.dates[1]) ?
                    this.getFormattedDateRange(this.dates[0], this.dates[1])
                    : this.getFormattedDateRange(this.dates[1], this.dates[0]);
            } else {
                return this.getFormattedDateRange(this.dates[0], this.dates[0])
            }
        },
        getFromDate() {
            if(this.dates.length > 1) {
                return isBefore(this.dates[0], this.dates[1]) ?
                    getFormattedDBDate(this.dates[0])
                    : getFormattedDBDate(this.dates[1]);
            } else {
                return this.dates[0] ?
                    getFormattedDBDate(this.dates[0])
                    : getTodayString();
            }
        },
        getToDate() {
            if(this.dates.length > 1) {
                return isAfter(this.dates[0], this.dates[1]) ?
                    getFormattedDBDate(this.dates[1])
                    : getFormattedDBDate(this.dates[0]);
            } else {
                return this.dates[0] ?
                    getFormattedDBDate(this.dates[0])
                    : getTodayString();
            }
        }
    },
    methods: {
        getDates(dateRange) {
            return dateRange.start ?
                [getFormattedDateString(dateRange.start), getFormattedDateString(dateRange.end)]
                : [getTodayString()];
        },
        getPresetIndex(dateRange) {
            let today = getTodayString();
            let yesterday = getYesterdayString()

            if(isSame(dateRange.start, dateRange.end)) {
                if(isSame(dateRange.start, today)) {
                    return 0;
                } else if(isSame(dateRange.start, yesterday)) {
                    return 1;
                }
            }
            return null;
        },
        getMatchedPreset(date) {
            let today = getToday();
            let yesterday = getYesterday();
            if(isSame(date, today)) {
                this.selectedPreset = 0;
            } else if(isSame(date, yesterday)) {
                this.selectedPreset = 1;
            } else {
                this.selectedPreset = null;
            }
        },
        close() {
            this.$emit('close');
        },
        select() {
            let selectedDate = {
                start: getTodayString(),
                end: getTodayString()
            }
            if(this.dates.length > 1) {
                isBefore(this.dates[0], this.dates[1]) ?
                (selectedDate.start = this.dates[0], selectedDate.end = this.dates[1])
                : (selectedDate.start = this.dates[1], selectedDate.end = this.dates[0])
            } else {
                selectedDate.start = this.dates[0], selectedDate.end = this.dates[0]
            }
            this.$emit('select', selectedDate);
        },
        getFormattedDateRange(date1, date2) {
            return getFormattedDBDate(date1) + ' - ' + getFormattedDBDate(date2)
        }
    },
    watch: {
        dates(dates) {
            if(dates.length > 1) {
                if(isSame(dates[0], dates[1])) {
                    this.getMatchedPreset(dates[0]);
                } else {
                    this.selectedPreset = null;
                }
            } else if(dates.length === 1) {
                this.getMatchedPreset(dates[0]);
            } else {
                this.selectedPreset = null;
            }
        },
        selectedPreset(selectedPreset) {
            if(selectedPreset === 0) {
                this.dates = [getTodayString()];
            } else if(selectedPreset === 1) {
                this.dates = [getYesterdayString()];
            }
        },
    },
}
</script>

<style scoped>
 .header-title {
    margin-right: 4px !important;
    font-size: 14px;
 }
 .header-value {
    background-color: #ebe4e4;
    padding: 8px;
    border-radius: 20px;
    font-size: 14px;
 }
 .calender-view {
    text-align: center;
 }
 .date-range-field {
     margin-left: 40px !important;
     width: 80%;
 }
</style>
