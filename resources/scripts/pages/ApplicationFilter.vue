<template>
    <div class="d-flex">
        <v-text-field v-model="search" full-width outlined dense hide-details="auto" placeholder="Firstname" style="background-color: white" class="my-1 "/>
        <v-text-field v-model="search" full-width outlined dense hide-details="auto" placeholder="Firstname" style="background-color: white" class="my-1 mx-1"/>
        <v-text-field v-model="search" full-width outlined dense hide-details="auto" placeholder="Firstname" style="background-color: white" class="my-1 mx-1"/>
        <v-text-field v-model="search" full-width outlined dense hide-details="auto" placeholder="Firstname" style="background-color: white" class="my-1 mx-1"/>
        <v-text-field v-model="search" full-width outlined dense hide-details="auto" placeholder="Firstname" style="background-color: white" class="my-1 "/>
    </div>
</template>

<script>
    export default {
        name: "ApplicationFilter",
        data(){
            return {
                search: ""
            }
        },
        watch:{
            search(){
                console.log("search printing");
                this.$router.push({name: 'application.list', query: {...this.$route.query, ...{test: this.search}}})
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>