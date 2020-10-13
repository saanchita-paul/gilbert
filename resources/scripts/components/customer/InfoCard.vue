<template>
    <v-card
        :color="color || '#952175'"
        dark
        class="box"
    >
        <div class="d-flex justify-space-between align-content-center">
            <div>
                <v-card-title
                    class="headline"
                    v-text="value"
                ></v-card-title>

                <v-card-subtitle v-text="title"></v-card-subtitle>

            </div>

            <v-avatar
                class="ma-3"
                tile
            >
                <v-icon x-large>{{icon}}</v-icon>
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
    height: 120px;
}
</style>
