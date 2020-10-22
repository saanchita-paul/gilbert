<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant="miniDrawer"
            mini-variant-width="80"
            app
            style="background-color: rgba(92, 34, 154, .6)"
            class="navigation-drawer-menu"
        >
            <v-list-item  style="padding-bottom: 8px !important; background-color:rgba(92, 34, 154, .6)">
                <v-list-item-avatar class="ml-0">
                        <v-img src="/assets/images/logo/hood-small.png" />
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title class="white--text">Hood</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense class="main-nav-items">
                    <span class="mb-6" v-for="r in routes">
                        <v-list-item
                                     :to="{name: r.route_name}"
                                     link
                                     exact-active-class="app-active-nav white--text"
                                     exact
                                     class="pa-2 app-nav"
                                     :key="r.title"
                        >
                        <v-list-item-icon><v-icon size="35" color="white">{{r.icon}}</v-icon></v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ r.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    </span>
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

        <v-main class="body-bg">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import ApplicationService from "../services/ApplicationService";
import {initializeBroadcasting} from "@scripts/plugins/LaravelEcho";
import {getAuthUser} from "@scripts/services/AuthService";


export default {
    name: "DashboardLayout",
    data() {
        return {
            drawer: true,
            miniDrawer: true,
            routes: ApplicationService.getMainNavigationRoutes()
        }
    },
    mounted() {
        initializeBroadcasting(getAuthUser().id);
    }
}
</script>

<style scoped>

</style>
