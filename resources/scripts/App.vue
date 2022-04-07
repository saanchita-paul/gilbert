<template>
    <router-view></router-view>
</template>

<script>
export default {
    name: "App.vue",

    mounted() {
        this.$eventBus.$on("address_updated", address => {
            // console.log("EventBus: ", address)
        });
    },
//     beforeDestroy(){
//     EventBus.$off('content-type-saving', this.listener)
//   }
    
}
</script>

<style scoped>
</style>
