<template>
<v-card max-width="750"
    class="mx-auto">
        <v-toolbar elevation="0">
            <v-list-item-avatar>
                <v-img src="https://cdn.vuetifyjs.com/images/lists/1.jpg"></v-img>
            </v-list-item-avatar>

            <p class="mt-3">User Name 1 <br>
                <small class="text-caption">Demo@email.com</small>
            </p>

        </v-toolbar>
        <v-divider></v-divider>
    <v-container class="fill-height">
        <v-row class="fill-height pb-14" align="end">
            <v-col>
                <div v-for="(item, index) in chats" :key="index"
                    :class="['d-flex flex-row align-center my-2', item.from == 'user' ? 'justify-end': null]">
                    <v-card elevation="0" class="py-1 px-5 light-blue darken-4" v-if="item.from === 'user'">
                        <span  class="white--text  ml-3">{{ item.message }}</span>
                    </v-card>

                    <v-card elevation="0" class="py-1 px-5 grey lighten-3" v-if="item.from === 'support'">
                        <span  class="black--text  mr-3">{{ item.message }}</span>
                    </v-card>

                </div>
            </v-col>
        </v-row>
    </v-container>
    <v-footer>
        <v-container class="ma-0 pa-0">
        <v-row no-gutters>
            <v-col>
            <div class="d-flex flex-row align-center">
                <v-text-field placeholder="Type Something"></v-text-field>
                <v-btn icon class="ml-4"><v-icon>mdi-send</v-icon></v-btn>
            </div>
            </v-col>
        </v-row>
        </v-container>
    </v-footer>
</v-card>
</template>

<script>
  export default {
    data: () => ({
        chats: [
            {
                from: 'user',
                message: 'User Message 1'
            },
            {
                from: 'support',
                message: 'Support Message 1'
            },
            {
                from: 'user',
                message: 'User Message 2'
            },
            {
                from: 'support',
                message: 'Support Message 2'
            },
            {
                from: 'user',
                message: 'User Message 3'
            },
            {
                from: 'support',
                message: 'Support Message 3'
            },
        ],
    }),
  }
</script>
