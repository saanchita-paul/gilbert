<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant="miniDrawer"
            mini-variant-width="80"
            app
            style="background-color: #444348"
            class="navigation-drawer-menu"
        >
            <v-list-item style="padding-bottom: 8px !important; background-color: white">
                <v-list-item-avatar class="ml-0">
                    <v-img src="/assets/images/logo/hood-small.png"/>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title class="white--text">Hood</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense class="main-nav-items">
                    <span class="mb-6" v-for="r in routes">
                        <v-list-item
                            v-if="r.type ==='group'"
                            exact
                            link
                            @click="onMenuClick(r)"
                            exact-active-class="app-active-nav white--text"
                            class="pa-2 app-nav"
                            :key="r.title"
                        >
                            <v-list-item-icon><v-icon size="35" color="white">{{ r.icon }}</v-icon></v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ r.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item
                            v-else
                            :to="{name: r.route_name}"
                            exact
                            link
                            @click="onMenuClick(r)"
                            exact-active-class="app-active-nav white--text"
                            class="pa-2 app-nav"
                            :key="r.title"
                        >
                        <v-list-item-icon><v-icon size="35" color="white">{{ r.icon }}</v-icon></v-list-item-icon>
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

        <v-main  class="body-bg" style="position: relative">
            <v-slide-x-transition>
                <v-navigation-drawer
                    v-if="selectedMenus && subMenuDrawer"
                    width="170px"
                    style="background-color: white; border-radius: 8px; position: fixed; left: 82px; top:70px"
                >
                    <v-list
                        dense
                        nav
                    >
                        <v-list-item
                            v-for="r in selectedMenus"
                            :key="r.title"
                            :to="{name: r.route_name}"
                            exact
                            @click="onSubmenuClick(r)"
                        >
                            <v-list-item-content>
                                <v-list-item-title>{{ r.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>
            </v-slide-x-transition>

            <v-row>
                <v-col v-if="selectedMenus" md="2" >
                </v-col>
                    <v-col :md="selectedMenus ? '10' : '12'">
                        <router-view></router-view>
                    </v-col>
            </v-row>

        </v-main>
    </v-app>
</template>

<script>
import ApplicationService from "../services/ApplicationService";
import {initializeBroadcasting} from "@scripts/plugins/LaravelEcho";
import AuthService, {getAuthUser} from "@scripts/services/AuthService";


export default {
    name: "DashboardLayout",
    data() {
        return {
            subMenuDrawer: false,
            selectedMenus: null,
            drawer: true,
            miniDrawer: true,
            routes: ApplicationService.getMainNavigationRoutes()
        }
    },
    mounted() {
        setInterval(AuthService.authUser, 300000)
        initializeBroadcasting(getAuthUser().id);
    },
    methods: {
        onMenuClick(menu) {
            if (menu.type === 'route') {
                this.$router.push({name: menu.route_name})
                this.selectedMenus = null;
                this.subMenuDrawer = true;
            } else {
                this.selectedMenus = menu.children;
                this.subMenuDrawer = true;
            }
        },
        onSubmenuClick(menu) {
            this.$router.push({name: menu.route_name})
        }
    }
}
</script>

<style scoped>

</style>
