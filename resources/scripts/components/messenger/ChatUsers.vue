<template>
    <v-card
        max-width="450"
        class="mx-auto messenger-support"
    >
        <v-toolbar elevation="0">
            <v-list-item-avatar class="ml-0">
                <v-img src="https://cdn.vuetifyjs.com/images/lists/5.jpg"></v-img>
            </v-list-item-avatar>

            <p class="mt-3  app-title">Hood Admin</p>

        </v-toolbar>

        <v-divider></v-divider>

        <v-list dense class="px-4">
            <span  v-for="(user, index) in users"
                   :key="user.id"
            >
                <v-list-item link>
                    <v-list-item-avatar>
                        <v-img :src="getImage()"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>{{user.name}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider v-if="index +1 < users.length"></v-divider>
            </span>

        </v-list>
    </v-card>
</template>

<script>
import { random} from 'lodash-es'
import axios from 'axios';

export default {
    data() {
        return {
            users: [],
        }
    },
    async mounted() {
        this.users = (await axios.get('https://jsonplaceholder.typicode.com/users?_limit=8')).data
    },
    methods: {
        getImage() {
            return `https://cdn.vuetifyjs.com/images/lists/${random(1,5)}.jpg`
        }
    }
}
</script>
