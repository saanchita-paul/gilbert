<template>
    <v-card class="white black--text" style="position: relative">
        <v-row class="pa-3">
            <v-col cols="8" class="pb-0">
                <p style="color: #757575" class="text-h4" v-text="value"></p>
            </v-col>
            <v-col cols="8" class="pb-0">
                <p style="color: #757575" class="app-title" v-text="title"></p>
            </v-col>
            <v-icon style=" opacity: .4; position: absolute; bottom: 0px; right: 0px" class="" size="100">{{icon}}</v-icon>
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
</style>
