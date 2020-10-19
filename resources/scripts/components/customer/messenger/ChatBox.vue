<template>
<v-card max-width="750"
        style="background-color: #FAFAFA"
    class="mx-auto">
        <v-toolbar elevation="0">
            <v-list-item-avatar>
                <v-img src="https://cdn.vuetifyjs.com/images/lists/1.jpg"></v-img>
            </v-list-item-avatar>
            <p class="mt-3 app-title">Mr Green</p>
        </v-toolbar>
        <v-divider></v-divider>
    <v-container class="fill-height">
        <v-row class="fill-height pb-2" align="end">
            <v-col>
                <div v-for="(item, index) in chats" :key="index"
                    :class="['d-flex flex-row align-center my-2', item.from === 'user' ? 'justify-end': null]">
                    <v-card elevation="1" class="pa-2 mr-2 white" v-if="item.from === 'user'">
                        <span  class=" mgs-text">{{ item.message }}</span>
                    </v-card>

                    <v-card elevation="1" class="pa-2 ml-2  white" v-if="item.from === 'support'">
                        <span  class=" mgs-text">{{ item.message }}</span>
                    </v-card>

                </div>
            </v-col>
        </v-row>
    </v-container>
    <v-footer style="background-color: #FAFAFA !important;">
        <v-container class="ma-0 pa-0">
        <v-row>
            <v-col>
            <div class="d-flex flex-row align-center">
                <v-text-field dense hide-details outlined placeholder="Type your message"></v-text-field>
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
                message: 'Hi'
            },
            {
                from: 'support',
                message: 'Hey there'
            },
            {
                from: 'user',
                message: 'I need to Reminder'
            },
            {
                from: 'support',
                message: 'Sure!'
            },
            {
                from: 'support',
                message: 'What do you want to remind?'
            },
            {
                from: 'user',
                message: 'Nothing!'
            },
            {
                from: 'user',
                message: 'Bye'
            },
        ],
    }),
  }
</script>

<style scoped>
.mgs-text {
    font-size: 15px;
    font-weight: 600;
}
</style>
