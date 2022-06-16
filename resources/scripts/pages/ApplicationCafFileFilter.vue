<template>
    <div>
        <v-form ref="form" autocomplete="off">
            <v-row>
                <v-col cols="9">
                    <div class="d-flex">
                        <v-text-field
                            autocomplete="off"
                            v-model="name"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Name"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="address"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Address"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="phone"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Mobile"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <div  class="py-0 mr-2" style="flex-basis: 235px;">
                            <v-text-field
                                class='date-select'
                                dense
                                placeholder="Date"
                                v-model="selectedDate"
                                append-icon="mdi-calendar-range"
                                readonly
                                outlined
                                hide-details
                                @click="showDatePickerModal = true"
                                @click:append="showDatePickerModal = true"
                            ></v-text-field>
                        </div>

                        <div
                            style="display: flex; align-items: center;">
                            <v-btn
                                small
                                tile
                                color="#e0e0e0"
                                @click="clearSearch">
                                <v-icon small left> mdi mdi-close</v-icon>
                                Reset
                            </v-btn>
                        </div>
                    </div>
                </v-col>
                <v-col cols="3">
                    <v-btn class="float-right">
                        Generate CAF File
                    </v-btn>
                </v-col>
            </v-row>

            <DatePickerModal
                v-if="showDatePickerModal"
                :dialog="showDatePickerModal"
                :dateRange="dateRange"
                @select="onSelectDate"
                @close="onCloseModal"
            />
        </v-form>
    </div>
</template>

<script>
import DatePickerModal from "@scripts/modules/sales/components/DatePickerModal";
import {getFormattedDBDate, getToday, getTodayString, getYesterday, isSame} from "@scripts/services/DateRangeService";
import {isNil} from "lodash-es";
export default {
    name: "ApplicationCafFileFilter",
    components: {DatePickerModal},
    props: [],
    data() {
        return {
            name: "",
            address: "",
            phone: "",
            selectedDate: null,
            showDatePickerModal: false,
            dateRange: {
                start: this.$route.query?.start ?
                    this.$route.query?.start : getTodayString(),
                end:  this.$route.query?.end ?
                    this.$route.query?.end : getTodayString()
            },
        };
    },
    methods: {
        onSelectDate(dateRange) {
            this.dateRange = dateRange;
            this.showDatePickerModal = false;
            let queries = JSON.parse(JSON.stringify(this.$route.query));
            queries.start = this.dateRange.start;
            queries.end = this.dateRange.end;
            this.agencyFilter.start = this.dateRange.start;
            this.agencyFilter.end = this.dateRange.end;
        },

        checkDate() {
            if(isNil(this.$route.query?.start) && isNil(this.$route.query?.end)){
                return;
            }

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
        onCloseModal() {
            this.showDatePickerModal = false;
        },
        clearSearch() {
            this.$refs.form.reset();
        },
    },
    watch: {
        dateRange(val) {
            this.checkDate();
        },
    },

};
</script>

<style lang="scss" scoped>
.clearButton {
    background: #e0e0e0;
    color: black;
    border-radius: 1px;

    &:hover {
        cursor: pointer;
    }
}
</style>
