<template>
    <img class="my-3" v-bind:src="provider.logo" width="150px">
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
