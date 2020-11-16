<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant="miniDrawer"
            mini-variant-width="70"
            app
            class="navigation-drawer-menu app-nav-bg"
        >
            <v-list-item class="app-logo-area">
                <v-list-item-avatar class="ml-0">
                    <v-img src="/assets/images/logo/hood-small.png"/>
                </v-list-item-avatar>

                <v-list-item-content>
<!--                    <v-list-item-title class="white&#45;&#45;text">Hood</v-list-item-title>-->
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense class=" pt-0 main-nav-items">
                    <span class="mb-2" v-for="r in routes">
                         <v-list-item
                            v-if="r.type ==='group'"
                            link
                            @click="onGroupMenuClicked(r)"
                            exact-active-class=""
                            :class="{
                                'py-1': true,
                                'app-nav': true,
                                'app-active-nav white--text': selectedRouteTitle === r.title
                            }"
                            :key="r.title"
                        >
                            <v-list-item-icon>
                                <v-icon size="26" :color="selectedRouteTitle === r.title ? 'white' : 'grey'">{{ r.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title>{{ r.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item
                            v-else
                            link
                            @click="onMenuClicked(r)"
                            :class="{
                                'pa-1': true,
                                'app-nav': true,
                                'app-active-nav white--text': selectedRouteTitle === r.title
                            }"
                            :key="r.title"
                        >
                        <v-list-item-icon><v-icon size="26" :color="selectedRouteTitle === r.title ? 'white' : 'grey'">{{ r.icon }}</v-icon></v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ r.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    </span>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar flat app class="white">
<!--            <v-toolbar-title class="primary&#45;&#45;text">Hood</v-toolbar-title>-->
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

        <v-main  class="body-bg" :style="{position: 'relative', 'padding-left': subMenuDrawer ? '234px !important' : '64px !important' }">
            <v-expand-x-transition>
                <v-navigation-drawer
                    v-if="selectedMenus && subMenuDrawer"
                    width="170px"
                    style="background-color: white; border-radius: 8px; position: fixed; left: 61px; top:66px"
                >
                    <v-list
                        dense
                        nav
                    >
                        <div class="d-flex justify-end">
                            <div><v-icon @click="subMenuDrawer = false" small>mdi-arrow-left</v-icon></div>
                        </div>
                        <div>
                            <v-list-item class="mt-0 pt-0">
                                <v-list-item-title class="sub-menu-title mt-0 pt-0">
                                    {{selectedMenus ? selectedMenus.title.toUpperCase() : ''}}
                                </v-list-item-title>
                            </v-list-item>
                        </div>
                        <v-list-item
                            v-for="r in selectedMenus.children"
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
            </v-expand-x-transition>

            <router-view></router-view>

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
            //todo: update default route based on current route
            selectedMenus: ApplicationService.getDefaultRoute(),
            drawer: true,
            miniDrawer: true,
            routes: ApplicationService.getMainNavigationRoutes()
        }
    },
    computed: {
        selectedRouteTitle() {
            return this.selectedMenus?.title
        }
    },
    mounted() {
        setInterval(AuthService.authUser, 300000)
        initializeBroadcasting(getAuthUser().id);
    },
    methods: {
        onGroupMenuClicked(menu) {
            if(menu.route_name === this.selectedMenus?.route_name) {
                this.subMenuDrawer = !this.subMenuDrawer;
            } else {
                this.$router.push({name: menu.route_name})
                this.subMenuDrawer = true;
                this.selectedMenus = menu;
            }
        },
        onMenuClicked(menu) {
            this.subMenuDrawer = false;
            if (menu.route_name !== this.selectedMenus?.route_name) {
                this.$router.push({name: menu.route_name})
                this.selectedMenus = menu;
            }
        },
        onSubmenuClick(menu) {
            this.$router.push({name: menu.route_name})
        }
    }
}
</script>

<style scoped>
.app-nav-bg {
    background-color: #444348
}
.app-logo-area {
    padding-bottom: 8px !important; background-color: white
}
.sub-menu-title {
    color: darkgrey;font-size: 10px !important;
}
</style>
