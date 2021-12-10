<template>
    <div class='d-flex fixed-height'>
        <v-spacer></v-spacer>
        <v-select
            class='type-select'
            :items="items"
            solo
            dense
            label="Type"
        ></v-select>
        <v-text-field
            class='date-select'
            solo
            dense
            label="Calender"
            placeholder="Today"
            append-icon="mdi-calendar-range"
            readonly
        ></v-text-field>
        <v-btn class='download-button'>
            Download
            <v-icon right>mdi-download</v-icon>
        </v-btn>
    </div>
</template>

<script>
export default {
    name: "SalesFilter",
    data() {
        return {
            items: ['Energy', 'Water'],
        }
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
    .fixed-height {
        max-height: 30px !important;
    }
</style>
