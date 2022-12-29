
<template>
    <div>
        <v-form ref="form" autocomplete="off">
            <v-row>
                <v-col cols="9">
                    <div class="d-flex">
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
                        <v-select
                            placeholder="Provider"
                            v-model="filterApplication.provider"
                            @change="filterProvider"
                            item-text="text"
                            item-value="value"
                            :items="provider"
                            outlined
                            dense
                            hide-details="auto"
                            class="mr-2"
                        >
                        </v-select>
                        <v-select
                            placeholder="Application Type"
                            v-model="filterApplication.applicationType"
                            @change="filterApplicationType"
                            item-text="text"
                            item-value="value"
                            :items="applicationType"
                            outlined
                            dense
                            hide-details="auto"
                            class="mr-2"
                        >
                        </v-select>
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
                <v-col cols="3">
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
            filterApplication:{
                provider : "",
                applicationType : ""
            }
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
            let selectedRow = selectedLeads.map(dt => {
                return dt.id + '-' + dt.selected_service
            });
            let query = selectedRow.join('_');
            console.log('selected rows', query);
            const url = `${process.env.MIX_BOT_ROOT_URL}/api/download-caf-file?leads=`+ query;
            window.open(
                url,
                '_blank'
            );

        },
        filterApplicationType(){
            this.$emit("updateApplicationTypeFilter",this.filterApplication.applicationType)
        },
        filterProvider(){
            this.$emit("updateProviderNameFilter",this.filterApplication.provider)
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

