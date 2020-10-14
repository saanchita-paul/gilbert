<template>
<v-card max-width="750"
    class="mx-auto">
        <v-toolbar>
            <v-list-item-avatar>
                <v-img src="https://cdn.vuetifyjs.com/images/lists/1.jpg"></v-img>
            </v-list-item-avatar>

            <v-toolbar-title>Inbox</v-toolbar-title>
        </v-toolbar>
    <v-container class="fill-height">
        <v-row class="fill-height pb-14" align="end">
            <v-col>
                <div v-for="(item, index) in chats" :key="index" 
                    :class="['d-flex flex-row align-center my-2', item.from == 'user' ? 'justify-end': null]">
                    <span v-if="item.from === 'user'" class="blue--text mr-3">{{ item.message }}</span>
                    <span v-if="item.from === 'support'" class="blue--text ml-3">{{ item.message }}</span>
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
                message: 'Uer Message 2'
            },
            {
                from: 'support',
                message: 'Support Message 2'
            },
            {
                from: 'user',
                message: 'Uer Message 3'
            },
            {
                from: 'support',
                message: 'Support Message 3'
            },
        ],
    }),
  }
</script>