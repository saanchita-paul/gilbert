
<template>
    <div>
        <v-form ref="form" autocomplete="off">
            <v-row>
                <v-col cols="12">
                    <v-row class="my-1">
                        <v-col cols="3" >
                            <v-text-field
                                autocomplete="off"
                                v-model="$attrs.value.name"
                                outlined
                                dense
                                hide-details="auto"
                                placeholder="Name"
                                style="background-color: white"
                                class="mr-2"
                            />
                        </v-col>
                        <v-col cols="3" >
                            <v-text-field
                                v-model="$attrs.value.address"
                                outlined
                                dense
                                hide-details="auto"
                                placeholder="Address"
                                style="background-color: white"
                                class="mr-2"
                            />
                        </v-col>
                        <v-col cols="3" >
                            <v-text-field
                                v-model="$attrs.value.business_name"
                                outlined
                                dense
                                hide-details="auto"
                                placeholder="Business Name"
                                style="background-color: white"
                                class="mr-2"
                            />
                        </v-col>
                        <v-col cols="3" >
                            <v-text-field
                                v-model="$attrs.value.abn"
                                outlined
                                dense
                                hide-details="auto"
                                placeholder="ABN"
                                style="background-color: white"
                                class="mr-2"
                            />
                        </v-col>
                        <v-col cols="3" >
                            <v-select
                                placeholder="Provider"
                                v-model="$attrs.value.provider_name"
                                item-text="text"
                                item-value="value"
                                :items="provider"
                                outlined
                                dense
                                hide-details="auto"
                                class="mr-2"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="3" >
                            <v-select
                                placeholder="Application Type"
                                v-model="$attrs.value.app_type"
                                item-text="text"
                                item-value="value"
                                :items="applicationType"
                                outlined
                                dense
                                hide-details="auto"
                                class="mr-2"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="3">
                            <v-select
                                placeholder="Power Status"
                                v-model="$attrs.value.power_status"
                                item-text="text"
                                item-value="value"
                                :items="powerStatus"
                                outlined
                                dense
                                hide-details="auto"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="3">
                            <v-select
                                placeholder="Gas Status"
                                v-model="$attrs.value.gas_status"
                                item-text="text"
                                item-value="value"
                                :items="gasStatus"
                                outlined
                                dense
                                hide-details="auto"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="3" >
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
                        </v-col>
                        <v-col cols="3" >
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
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="12">
                    <v-btn class="float-right" :disabled="isDisabledCafBtn" @click="generateCafFIle">
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
export default {
    name: "ChatbotApplicationFilter",
    components: {DatePickerModal},
    props: ["selected", "isSearchEmpty", 'cafFiles'],
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
            provider : [
                {
                    text: "EA",
                    value: "ea"
                },
                {
                    text: "Powershop",
                    value: "powershop"
                },
                {
                    text: "Origin",
                    value: "origin"
                },
            ],
            applicationType : [
                {
                    text: "Chatbot",
                    value: "chatbot"
                },
                {
                    text: "Twiddle",
                    value: "twiddle"
                }
            ],
            powerStatus : [
                {
                    id: 13,
                    type: "service",
                    display_text: "Accepted",
                    display_text_alias: "Connected",
                    status_value: 5,
                    text: "Accepted",
                    value: 5
                },
                {
                    id: 15,
                    type: "service",
                    display_text: "Not Submitted",
                    display_text_alias: "In progress",
                    status_value: 7,
                    text: "Not Submitted",
                    value: 7
                },
                {
                    id: 17,
                    type: "service",
                    display_text: "Rejected",
                    display_text_alias: "Rejected",
                    status_value: 9,
                    text: "Rejected",
                    value: 9
                },
                {
                    id: 19,
                    type: "service",
                    display_text: "Manual Processing",
                    display_text_alias: "Manual Processing",
                    status_value: 11,
                    text: "Manual Processing",
                    value: 11
                },
                {
                    id: 20,
                    type: "service",
                    display_text: "In Progress",
                    display_text_alias: "In Progress",
                    status_value: 12,
                    text: "In Progress",
                    value: 12
                }
            ],
            gasStatus : [
                {
                    id: 13,
                    type: "service",
                    display_text: "Accepted",
                    display_text_alias: "Connected",
                    status_value: 5,
                    text: "Accepted",
                    value: 5
                },
                {
                    id: 15,
                    type: "service",
                    display_text: "Not Submitted",
                    display_text_alias: "In progress",
                    status_value: 7,
                    text: "Not Submitted",
                    value: 7
                },
                {
                    id: 17,
                    type: "service",
                    display_text: "Rejected",
                    display_text_alias: "Rejected",
                    status_value: 9,
                    text: "Rejected",
                    value: 9
                },
                {
                    id: 19,
                    type: "service",
                    display_text: "Manual Processing",
                    display_text_alias: "Manual Processing",
                    status_value: 11,
                    text: "Manual Processing",
                    value: 11
                },
                {
                    id: 20,
                    type: "service",
                    display_text: "In Progress",
                    display_text_alias: "In Progress",
                    status_value: 12,
                    text: "In Progress",
                    value: 12
                }
            ],
        };
    },
    computed: {
        isDisabledCafBtn() {
            return this.selected?.length < 1;
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
            this.$emit('updateDate', this.dateRange)
        },

        generateCafFIle()
        {
            let selectedId = this.selected.map(dt => dt.id);

            let selectedLeads = this.cafFiles.filter(cf => selectedId.includes(cf.id));
            let selectedRow = this.selected.map(dt => {
                return dt.id + '-' + 'both'
            });
            let query = selectedRow.join('_');
            console.log('selected rows', query);
            const url = `${process.env.MIX_BOT_ROOT_URL}/api/download-caf-file?leads=`+ query;
            window.open(
                url,
                '_blank'
            );

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

