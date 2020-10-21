<template>
    <v-menu
        ref="menu"
        v-model="menu2"
        :close-on-content-click="false"
        :nudge-right="40"
        transition="scale-transition"
        offset-y
        min-width="290px"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-text-field
                v-model="dateRangeText"
                label="Select date range"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
            ></v-text-field>
        </template>
        <v-date-picker
            v-model="dates"
            range
        >
            <v-spacer></v-spacer>
            <v-btn
                text
                color="primary"
                @click="menu2 = false"
            >
                Cancel
            </v-btn>
            <v-btn
                text
                color="primary"
                @click="onDateUpdate"
            >
                OK
            </v-btn>
        </v-date-picker>
    </v-menu>
</template>
<script>
import DateRange from "@scripts/models/DateRange";

export default {
    name: "DateRangePicker",
    props: ['value'],
    data() {
        return {
            dates: [this.value.start, this.value.end],
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
                this.dates = [value.start, value.end]
            },
            deep: true
        }
    },
    methods: {
        onDateUpdate() {
            this.$refs.menu.save(this.dates);
            this.$emit('input', new DateRange({
                start: this.dates[0],
                end: this.dates[1],
            }))
        }
    }
}
</script>

<style scoped>

</style>
