<template>
    <v-card style="height: 100%">
        <div class="header">
            <div class="item">
                <p class="app-title-small">Total User</p>
                <p class="app-title primary--text">2,145</p>
            </div>
            <span class="divider">|</span>
            <div class="item">
                <p class="app-title-small">New User</p>
                <p class="app-title primary--text">97</p>
            </div>
            <span class="divider">|</span>
            <div class="item">
                <p class="app-title-small">Active User</p>
                <p class="app-title primary--text">1,157</p>
            </div>
            <span class="divider">|</span>
            <div class="item">
                <p class="app-title-small">Engaged User</p>
                <p class="app-title primary--text">853</p>
            </div>
        </div>
    </v-card>
</template>

<script>
export default {
name: "CustomerInfos"
}
</script>

<style scoped lang="scss">
.divider {
    font-size: 50px;
}
.header {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: space-around;
    align-items: center;
    p {
        margin-bottom: 0px !important;
    }
}
.item {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>
