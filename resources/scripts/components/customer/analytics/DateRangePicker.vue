<template>
    <div class="date-range-container">
        <p class="app-title-small my-0 mr-2">Date range</p>
        <v-menu
            v-model="menu1"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    small
                    outlined
                    text
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-icon>mdi-calendar</v-icon>{{date_start}}
                </v-btn>
            </template>
            <v-date-picker
                v-model="date_start"
                @input="menu1 = false"
            ></v-date-picker>
        </v-menu>
        <p class="my-0 mx-1">to</p>
        <v-menu
            v-model="menu2"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    small
                    outlined
                    text
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-icon>mdi-calendar</v-icon>{{date_end}}
                </v-btn>
            </template>
            <v-date-picker
                v-model="date_end"
                @input="menu2 = false"
            ></v-date-picker>
        </v-menu>
    </div>
</template>
<script>
import DateRange from "@scripts/models/DateRange";

export default {
    name: "DateRangePicker",
    props: ['value'],
    data() {
        return {
            date_start: this.value.start,
            date_end: this.value.end,
            menu1: false,
            menu2: false
        }
    },
    computed: {
        dateRangeText() {
            return this.dates.join(' ~ ')
        },
    },
    watch: {
        value: {
            handler(value) {
                this.date_start = value.start;
                this.date_end = value.end;
            },
            deep: true
        },
        date_start: function() {
          this.onDateUpdate();
        },
        date_end: function() {
          this.onDateUpdate();
        }
    },
    methods: {
        onDateUpdate() {
            // this.$refs.menu.save(this.dates);
            this.$emit('input', new DateRange({
                start: this.date_start,
                end: this.date_end,
            }))
        }
    }
}
</script>

<style scoped>
.date-range-container {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
}
</style>
