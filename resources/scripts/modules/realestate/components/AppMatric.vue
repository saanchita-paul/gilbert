<template>
    <v-row class="matrics text-center">
        <p class="matrics-title mb-1">{{data.value}}</p>
        <p class="matrics-subtitle">{{data.title}}</p>
    </v-row>
</template>

<script>
export default {
name: "AppMatric",
    props: ["data"]
}
</script>

<style scoped>
    .matrics{
        display: flex;
        flex-direction: column;
    }
    .matrics-title {
        font-size: 2em;
        color: #542E89;
        font-weight: 700;
    }
    .matrics-subtitle{
        font-size:  0.875em;
        font-weight: 700;
        color: #263238;
    }
</style>
