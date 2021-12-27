<template>
    <div>
        <ApplicantTable
            v-if="isLoaded"
            :applications="leads"
            :totalItem="totalItem"
        >
        </ApplicantTable>
    </div>
</template>

<script>
    export default {
        name: "OfficeApplicatoinListTable"
    }
</script>

<style lang="scss" scoped>

</style>