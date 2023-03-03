<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{ note.title }}</p>
        <p>{{ assigned_at }}</p>
        <p>by [{{ note.user_role }}]</p>
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
        assigned_at() {
            return new DayJs(this.note.text).format(DATE_FORMAT.NOTE_TIME);
        }
    }
}
</script>
