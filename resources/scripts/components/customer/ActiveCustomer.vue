<template>
    <v-card class="my-3">
        <v-card-title>RECENT ACTIVE CUSTOMERS</v-card-title>
        <v-card-text>
            <div style="display: flex; justify-content: center; flex-wrap: wrap">
                <div v-for="i in 15"
                     :key="i" style="display: flex; flex-direction: column; align-items: center"
                     class="ma-2"
                >
                    <v-avatar
                        color="primary"
                        class="white--text"
                        size="56"
                    >SS</v-avatar>
                    <p class="mt-1 mb-0">Stefan</p>
                    <h6 class="my-0">10 minutes ago</h6>
                </div>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: "ActiveCustomer"
}
</script>

<style scoped>

</style>
