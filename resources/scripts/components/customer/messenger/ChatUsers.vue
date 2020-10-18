<template>
    <v-card
        max-width="450"
        class="mx-auto messenger-support"
    >
        <v-toolbar elevation="0">
            <v-list-item-avatar class="ml-0">
                <v-img src="https://cdn.vuetifyjs.com/images/lists/5.jpg"></v-img>
            </v-list-item-avatar>

            <p class="mt-3">Support 1<br>
                <small class="text-caption">Demo@email.com</small>
            </p>

        </v-toolbar>

        <v-divider></v-divider>

        <v-list dense>
            <span  v-for="i in 7"
                   :key="i"
            >
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img :src="getImage()"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title>John Doe</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider v-if="i < 7"></v-divider>
            </span>

        </v-list>
    </v-card>
</template>

<script>
import { random} from 'lodash-es'

export default {
    methods: {
        getImage() {
            return `https://cdn.vuetifyjs.com/images/lists/${random(1,5)}.jpg`
        }
    }
}
</script>
