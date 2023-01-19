<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{ note.title }}</p>
        <p>{{ note.text }}</p>
        <p>by [{{ note.user_role }}]</p>
    </v-card>
</template>

<script>
export default {
    name: "AssignedNote",
    props: {
        note: {
            type: Object,
            required: true
        }
    }
}
</script>
