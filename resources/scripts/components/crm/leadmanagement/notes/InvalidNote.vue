<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{ note.title }}</p>
        <pre>{{note.text}}</pre>
    </v-card>
</template>

<script>
export default {
name: "Note",
    props: ['note']
}
</script>

<style scoped>

</style>
