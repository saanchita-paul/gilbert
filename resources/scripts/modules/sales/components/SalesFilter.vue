<template>
    <div class='d-flex'>
        <v-spacer></v-spacer>
        <v-select
            class='type-select'
            :items="items"
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
            append-icon="mdi-calendar-range"
            readonly
            hide-details
            @click="showDatePickerModal = true"
        ></v-text-field>
        <v-btn class='download-button'>
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

export default {
    name: "SalesFilter",
    components: {
        DatePickerModal,
    },
    data() {
        return {
            items: ['Energy', 'Water'],
            showDatePickerModal: true,
            dateRange: {
                start: '2021-12-09',
                end: '2021-12-09',
            },
        }
    },
    methods: {
        onSelectDate(dateRange) {
            this.showDatePickerModal = false;
        },
        onCloseModal() {
            this.showDatePickerModal = false;
        },
    },
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
