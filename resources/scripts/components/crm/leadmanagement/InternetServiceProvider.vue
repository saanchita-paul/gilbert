<template>
    <v-card
        class="mx-2"
        style="cursor: pointer;"
        @click="$emit('onSelectProvider')"
        :class="{'not-selected': provider.name !== selectedProvider}"
    >
        <img class="my-3 img" v-bind:src="provider.logo" width="150px" />
    </v-card>
</template>

<script>
export default {
    name: "InternetServiceProvider",
    props: {
        'provider': {
            require: true,
        },
        selectedProvider: {
            default: 1
        }
    }
}
</script>

<style scoped>
.not-selected {
    opacity: .4;
}
.img {
    margin-top: 0 !important;
    margin-bottom: 0 !important
}
</style>
