<template>
    <div>
        <v-form ref="form" autocomplete="off">
            <v-row>
                <v-col cols="10">
                    <div class="d-flex">
                        <v-text-field
                            autocomplete="off"
                            v-model="$attrs.value.tenant_name"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Name"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="$attrs.value.address"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Address"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="$attrs.value.business_name"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Business Name"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <v-text-field
                            v-model="$attrs.value.abn"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="ABN"
                            style="background-color: white"
                            class="mr-2"
                        />
                        <div  class="py-0 mr-2">
                            <v-text-field
                                solo
                                dense
                                label="Calender"
                                placeholder="Today"
                                v-model="selectedDate"
                                append-icon="mdi-calendar-range"
                                readonly
                                hide-details
                                style="background-color: white;"
                                @click="showDatePickerModal = true"
                                @click:append="showDatePickerModal = true"
                            ></v-text-field>
                        </div>

                        <div
                            style="display: flex; align-items: center">
                            <v-btn
                                v-show="!isSearchEmpty"
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
                <v-col cols="2">
                    <v-btn class="float-right" :disabled="isDisabledCafBtn" @click="generateGilbertAppCafFIle">
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
import {isEmpty, isNil} from "lodash-es";
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
export default {
    name: "GilbertApplicationCafFileFilter",
    components: {DatePickerModal},
    props: ["selectedCafFile", "isSearchEmpty", 'gilbertApplications'],
    data() {
        return {
            name: "",
            address: "",
            business_name: "",
            abn: "",
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
    computed: {
        isDisabledCafBtn() {
            return this.selectedCafFile?.length < 1;
        }
    },
    watch: {
        dateRange(val) {
            this.checkDate();
            this.updateDateRange();
        },
    },
    mounted() {
        this.checkDate();
        this.updateDateRange();
    },
    methods: {
        onSelectDate(dateRange) {
            this.dateRange = dateRange;
            this.showDatePickerModal = false;
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
        onCloseModal() {
            this.showDatePickerModal = false;
        },
        clearSearch() {
            this.$refs.form.reset();
        },
        updateDateRange() {
            this.$emit('updateDates', this.dateRange)
        },

        generateGilbertAppCafFIle() {
            let leadIds = this.selectedCafFile.map(item => item.id);
            let selectedIds = leadIds.join();
            let response = ApplicationCafFileService.generateGilbertCafFIle(selectedIds);
            console.log('Response from Generate caf file :', response);
        }

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
