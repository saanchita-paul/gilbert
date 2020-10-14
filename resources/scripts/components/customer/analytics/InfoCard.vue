<template>
    <v-card
        :color="color || '#952175'"
        dark
    >
        <div class="box d-flex justify-space-between align-content-center">
            <div class="d-flex flex-column justify-space-around" style="height: 100%">
                <v-card-title
                    style="font-size: 32pt !important;"
                    class="headline"
                    v-text="value"
                ></v-card-title>
                <v-card-subtitle v-text="title"></v-card-subtitle>
            </div>

            <v-avatar
                class="ma-3"
                tile
            >
                <v-icon size="60px">{{icon}}</v-icon>
            </v-avatar>
        </div>
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
