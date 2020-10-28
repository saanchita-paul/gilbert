<template>
    <v-card class="white black--text" style="position: relative">
        <v-row class="px-5 py-1">
            <v-col cols="8" class="pb-0">
                <p  class="info-value pa-0 ma-0 teal--text" v-text="user_value"></p>
                <p  class="app-title-small pa-0 ma-0 mt-n2" v-text="user_title"></p>
            </v-col>
            <v-col cols="8" class="pb-0">
                <p class="info-value pa-0 ma-0 teal--text" v-text="customer_value"></p>
                <p class="app-title-small pa-0 ma-0 mt-n2" v-text="customer_title"></p>
            </v-col>
            <v-icon  class="card-action-icon" size="20">{{actionIcon}}</v-icon>
            <v-icon  class="card-icon" color="primary" size="60">{{icon}}</v-icon>
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
    props: ['user_title', 'user_value', 'customer_title', 'customer_value', 'icon', 'color', 'actionIcon']
}
</script>

<style scoped>
.box {
    height: 150px;
}
</style>
