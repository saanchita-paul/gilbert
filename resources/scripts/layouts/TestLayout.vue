<template>
    <v-app >
        <v-navigation-drawer
            app
            dark
            mini-variant
            mini-variant-width="56"
            style="background-color: red"
        >
                <v-list
                    dense
                    nav
                >
                    <v-list-item
                        v-for="item in items"
                        :key="item.title"
                        @click="subMenu =!subMenu"
                    >
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>

                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
        </v-navigation-drawer>

        <v-app-bar app class="white">
            <v-toolbar-title class="primary--text">Hood</v-toolbar-title>
            <!--            <v-btn icon color="primary" @click="miniDrawer = !miniDrawer">-->
            <!--                <v-icon>{{ miniDrawer ? 'mdi-menu' : 'mdi-menu-open' }}</v-icon>-->
            <!--            </v-btn>-->
            <!--            <v-app-bar-nav-icon @click="miniDrawer = !miniDrawer"></v-app-bar-nav-icon>-->
            <!--            <v-toolbar-title>Hood</v-toolbar-title>-->
            <v-spacer/>
            <v-badge
                color="red"
                content="6"
                offset-y="20"
                offset-x="20"
                overlap
            >
                <v-btn color="primary" icon>
                    <v-icon>mdi-bell</v-icon>
                </v-btn>
            </v-badge>

            <v-menu
                left
                bottom
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="primary"
                        icon
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon>mdi-account</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item>
                        <v-list-item-title>Account</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-title>Log Out</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>

        </v-app-bar>
        <v-main>
            <v-navigation-drawer
                v-model="subMenu"
                dark
                style="background-color: blue"
            >
                <v-list
                    dense
                    nav
                >
                    <v-list-item
                        v-for="item in items"
                        :key="item.title"
                        @click="subMenu =!subMenu"
                    >

                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
        </v-main>
    </v-app>
</template>

<script>
export default {
    data () {
        return {
            subMenu: false,
            items: [
                { title: 'Home', icon: 'mdi-view-dashboard' },
                { title: 'About', icon: 'mdi-forum' },
            ],
            links: ['Home', 'Contacts', 'Settings'],
            mini: true,
        }
    },
}
</script>

<style scoped>

</style>
