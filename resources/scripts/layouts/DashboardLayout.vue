<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant="miniDrawer"
            mini-variant-width="55"
            app
            class="navigation-drawer-menu"
        >
            <v-list-item class="primary" style="padding-bottom: 8px !important;">
                <v-list-item-avatar class="ml-0">
                        <v-img src="/assets/images/logo/hood-small.png" />
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title class="white--text">Hood</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense class="main-nav-items">
                <span v-for="item in routes" :key="item.title">
                    <v-list-item v-if="item.type === 'route'"
                                 link
                                 exact-active-class="primary white--text"
                                 exact
                                 :to="{name: item.route_name}"
                    >
                        <v-list-item-icon><v-icon>{{ item.icon }}</v-icon></v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <span v-if="item.type === 'group'" class="mb-6">
                        <v-list-item v-if="!miniDrawer" >
                            <v-list-item-content class="mb-0 pb-0">
                                <v-list-item-title class="app-title-small mb-1">{{ item.title }}</v-list-item-title>
                                                                <v-divider></v-divider>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item v-for="r in item.children"
                                     :to="{name: r.route_name}"
                                     link
                                     exact-active-class="primary white--text"
                                     exact
                                     :key="r.title"
                        >
                        <v-list-item-icon><v-icon>{{r.icon}}</v-icon></v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ r.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    </span>
                </span>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app class="primary" dark>
            <v-btn icon color="white" @click="miniDrawer = !miniDrawer">
                <v-icon>{{ miniDrawer ? 'mdi-menu' : 'mdi-menu-open' }}</v-icon>
            </v-btn>
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
                <v-btn icon>
                    <v-icon>mdi-bell</v-icon>
                </v-btn>
            </v-badge>

            <v-menu
                left
                bottom
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
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

        <v-main class="body-bg">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import ApplicationService from "../services/ApplicationService";

export default {
    name: "DashboardLayout",
    data() {
        return {
            drawer: true,
            miniDrawer: false,
            routes: ApplicationService.getMainNavigationRoutes()
        }
    }
}
</script>

<style scoped>

</style>
