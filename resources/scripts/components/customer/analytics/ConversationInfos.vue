<template>
    <v-card style="height: 100%">
        <div class="header">
            <div class="item">
                <p class="app-title-small">Total conversation</p>
                <p class="app-title primary--text">34,856</p>
            </div>
            <span class="divider">|</span>
            <div class="item">
                <p class="app-title-small">Sent</p>
                <p class="app-title primary--text">22, 988</p>
            </div>
            <span class="divider">|</span>
            <div class="item">
                <p class="app-title-small">Received</p>
                <p class="app-title primary--text">11,868</p>
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
