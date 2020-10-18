<template>
    <v-card class="white--text card-bg-color">
        <v-row class="pa-3">
            <v-col cols="8" class="pb-0">
                <p class="text-h4" v-text="value"></p>
            </v-col>
            <v-col cols="4" class="pb-0">
                <v-icon class="white--text" size="40px">{{icon}}</v-icon>
            </v-col>
            <v-col cols="12" class="py-0">
                <p class="text-h6 title" v-text="title"></p>
            </v-col>
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
