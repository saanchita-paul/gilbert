<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{ note.title }}</p>
        <p>{{ note.created_at }}</p>
        <p v-if="by">{{ by }}</p>
    </v-card>
</template>

<script>
import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default {
    name: "AssignedNote",
    props: {
        note: {
            type: Object,
            required: true
        }
    },
    computed: {
        by() {
            if(this.note.additional_data?.name) {
                return this.note.additional_data?.role_formatted ?
                    `by [${this.note.additional_data?.name}] [${this.note.additional_data?.role_formatted}]`
                    : `by [${this.note.additional_data?.name}]`
            }
            return null
        }
    }
}
</script>
