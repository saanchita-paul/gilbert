<template>
    <v-dialog
        v-model="dialog"
        persistent
        max-width="550px"
    >
        <v-card>
            <section class="pa-4">
                <v-row>
                    <v-col cols="12">
                        <p>
                            <span class='header-title'>From: </span>
                            <span class='header-value'>2021-11-01</span>
                            <span class='header-title ml-2'>To: </span>
                            <span class='header-value'>2021-11-01</span>
                        </p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="4" class="py-0">
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
                                        <v-list-item-title v-text="item.text"></v-list-item-title>
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
                            label="Date range (YYYY-MM-DD)"
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
import dayjs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

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
            dates: this.dateRange.start
                ? [
                    dayjs(this.dateRange.start).format(DATE_FORMAT.DATE_DASH),
                    dayjs(this.dateRange.end).format(DATE_FORMAT.DATE_DASH)
                ] : [dayjs().format(DATE_FORMAT.DATE_DASH)],
            selectedPreset: this.getPresetIndex(this.dateRange),
            today: dayjs().format(DATE_FORMAT.DATE_DASH),
            yesderday: dayjs().subtract(1, "day").format(DATE_FORMAT.DATE_DASH),
        }
    },
    computed: {
      presetItems () {
        return [
            { text: 'Today', value: this.today },
            { text: 'Yesterday', value: this.yesderday },
        ]
      }, 
      dateRangeText () {
        return this.dates.length > 0
          ? this.dates.map(date => dayjs(date).format(DATE_FORMAT.DATE_SHASH)).join(' ~ ')
          : ''
      },
    },
    methods: {
        close() {
            this.$emit('close');
        },
        select() {
            this.$emit('select');
        },
        getPresetIndex(dateRange) {
            if(dayjs(dateRange.start).isSame(dayjs(dateRange.end))) {
                if(dayjs(dateRange.start).isSame(dayjs())) {
                    return 0;
                } else if(dayjs(dateRange.start).isSame(dayjs(this.yesderday))) {
                    return 1;
                }
                console.log('No match');
            }
            return null;
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
