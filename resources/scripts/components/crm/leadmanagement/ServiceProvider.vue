<template>
    <v-card class="mx-2" style="cursor: pointer" @click="$emit('onSelectProvider')">
        <img class="my-3" v-bind:src="provider.logo" width="150px">
    </v-card>
</template>

<script>
export default {
name: "ServiceProvider",
    props: {
        'provider': {
            require: true,
        }
    }
}
</script>

<style scoped>

</style>
