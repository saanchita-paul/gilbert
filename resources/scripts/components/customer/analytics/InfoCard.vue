<template>
    <v-card class="white black--text" style="position: relative">
        <v-row class="px-4 py-3">
            <v-col cols="8" class="pb-0">
                <p  class="info-value pa-0 ma-0 primary--text" v-text="value"></p>
            </v-col>
            <v-col cols="8" class="pb-0">
                <p class="app-title-small primary--text" v-text="title"></p>
            </v-col>
            <v-icon  class="card-icon" color="primary" size="100">{{icon}}</v-icon>
        </v-row>
        <!-- <div class="box d-flex justify-space-between align-content-center">
            <div class="d-flex flex-column justify-space-around" style="height: 100%">
                <v-card-title
                    style="font-size: 32pt !important;"
                    class="headline"
                    v-text="value"
                ></v-card-title>
                <v-card-subtitle class="white--text" v-text="title"></v-card-subtitle>
            </div>
            <v-avatar
                class="mt-3 white--text"
                tile
            >
                <v-icon size="60px">{{icon}}</v-icon>
            </v-avatar>
        </div> -->
    </v-card>
</template>

<script>
export default {
    name: "InfoCard",
    props: ['title', 'value', 'icon', 'color']
}
</script>

<style scoped>
.box {
    height: 150px;
}
.info-value {
    color: #757575;
    font-size: 40px !important;
    font-weight: 900;
}
.card-icon {
    opacity: .2;
    position: absolute;
    bottom: 0px;
    right: 5px
}
</style>
