<template>
    <v-card
    elevation="0"
    max-width="450"
    class="elevation-0 mx-auto messenger-support"
    >
        <v-toolbar elevation="0">
            <v-list-item-avatar>
                <v-img src="https://cdn.vuetifyjs.com/images/lists/5.jpg"></v-img>
            </v-list-item-avatar>

            <p class="mt-3">Support 1<br>
                <small class="text-caption">Demo@email.com</small>

            </p>

        </v-toolbar>

        <v-divider></v-divider>

        <v-list three-line>
            <template v-for="(item, index) in items">
            <v-subheader
                v-if="item.header"
                :key="item.header"
                v-text="item.header"
            ></v-subheader>

            <v-divider
                v-else-if="item.divider"
                :key="index"
                :inset="item.inset"
            ></v-divider>

            <v-list-item
                v-else
                :key="item.title"
            >
                <v-list-item-avatar>
                <v-img :src="item.avatar"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                <v-list-item-title v-html="item.title"></v-list-item-title>
                <v-list-item-subtitle v-html="item.subtitle"></v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            </template>
        </v-list>
    </v-card>
</template>

<script>
  export default {
    data: () => ({
      items: [
        { header: 'Today' },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
          title: 'User Name 1',
          subtitle: "Lorem ipsum dummy text used in web designs....",
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
          title: 'User Name 2',
          subtitle: "Lorem ipsum dummy text used in web designs....",
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
          title: 'User Name 3',
          subtitle: "Lorem ipsum dummy text used in web designs....",
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
          title: 'User Name 4',
          subtitle: "Lorem ipsum dummy text used in web designs....",
        },
        { divider: true, inset: true },
        {
          avatar: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
          title: 'User Name 5',
          subtitle: "Lorem ipsum dummy text used in web designs....",
        },
      ],
    }),
  }
</script>