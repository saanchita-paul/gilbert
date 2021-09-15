<template>
    <v-app id="inspire" v-if="user" style="position: relative" ref="mainSpinner">
        <v-navigation-drawer
            v-model="drawer"
            app
            class="app-nav-bar"
            dark
            :width="252"
        >
            <v-list class="nav-user-card hood-gradiant">
                <v-list-item-avatar class="center-element nav-logo" size="59">
                    <v-img src="/assets/images/logo/hood_logo_while.png"/>
                </v-list-item-avatar>
                <v-list-item-content class="text-center">
                    <v-list-item-title class="nav-user-name">
                        {{user.name}}
                    </v-list-item-title>
                    <v-list-item-subtitle class="nav-user-email">
                        {{user.email}}
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list>

            <v-list class="navigation-menu" dense>
                <v-img v-if="hasProfilePhoto"
                    class="center-element rounded-circle nav-avatar"
                    src="/assets/images/user_logo.png"
                    max-width="96px"
                ></v-img>
                <div v-if="!hasProfilePhoto" class="center-element rounded-circle nav-avatar avater-fullname-container" style="">
                    <span class="full-name-text" >{{profile_display_name}}</span>
                </div>

                <span v-for="route in routes" :key="route.title">
                        <v-list-group
                            color="white"
                            v-model="route.model"
                            active-class="nav-active"
                            v-if="route.type === 'group'"
                        >
                            <template v-slot:activator>
                                <v-list-item-icon>
                                    <v-img v-bind:src="route.icon"/>
                                </v-list-item-icon>
                                <v-list-item-title class="nav-main-item"> {{ route.title }} </v-list-item-title>
                            </template>
                            <span class="mb-2" v-for="subRoute in route.children" :key="subRoute.title">
                                <v-list-item
                                    exact-active-class="nav-active"
                                    link
                                    :to="{name:subRoute.route_name}"
                                    exact
                                >
                                    <v-list-item-action>
                                    </v-list-item-action>

                                    <v-list-item-content>
                                        <v-list-item-title class="nav-sub-item"> {{ subRoute.title }} </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </span>
                        </v-list-group>
                        <v-list-item link active-class="nav-active" v-if="route.type === 'route'" :to="{name:route.route_name}">
                           <v-list-item-icon>
                               <v-img v-bind:src="route.icon"/>
                           </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title class="nav-main-item"> {{ route.title }} </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </span>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app class="app-app-bar" dark>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>
                <h4 v-if="user.permissions.includes('can_manage_application')">
                    {{ getHeaderText() }}
                </h4>
                <h4 v-else-if="$route.meta.header">{{ $route.meta.header }}</h4>
                <v-breadcrumbs v-if="breadcrumbs" :items="breadcrumbs" divider=">">
                    <template v-slot:item="{ item }">
                        <v-breadcrumbs-item
                            :to="{name: item.to}"
                            exact
                        >
                            {{ item.text }}
                        </v-breadcrumbs-item>
                    </template>
                </v-breadcrumbs>
            </v-toolbar-title>
            <v-spacer/>
            <v-img  src="/assets/images/icons/Search.svg" max-width="24px"/>
            <div class="vertical-divider"></div>

            <p class="app-bar-user-name">{{user.name}}</p>
<!--            <v-icon medium class="app-bar-icon app-bar-login-button"> mdi-login</v-icon>-->
            <v-img style="cursor: pointer" alt="LOGOUT"  class="mx-9" @click="onLogout" src="/assets/images/icons/Logout.svg" max-width="24px"/>
        </v-app-bar>

        <v-main>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import ApplicationService from "../services/ApplicationService";
import AuthService from "@scripts/services/AuthService";
import UserRoles from "@scripts/data/UserRoles";

export default {
    name: "NewDashboardLayout",
    data() {
        return {
            user: null,
            drawer: null,
            profile_display_name: null,
            hasProfilePhoto: false,
            all_routes: ApplicationService.getMainNavigationRoutes()
        }
    },
   async mounted() {
        this.user = await AuthService.getAuthUser();
        this.getNameText();
        setInterval(AuthService.authUser, 300000)
    },
     methods: {
        async onLogout() {
            await AuthService.logout();
        },
         getHeaderText() {
            let user = this.user;
            let userRole = [...UserRoles.AGENCY, ...UserRoles.HOOD].find((r) => { return r.value === user.roles[0] });
            return "Hello "+ user.profile.first_name + ' ' +  user.profile.last_name + ' (' + userRole.text + ')';

         },

         getNameText()
         {
             this.hasProfilePhoto = Boolean(this.user?.profile?.profile_photo);
             this.profile_display_name = this.user?.profile?.first_name.charAt(0) + this.user?.profile?.last_name.charAt(0);
         }
    },
    computed: {
        routes() {
            return this.all_routes.filter(route => {
                if(this.user.id){
                    return this.user.permissions.includes(route.permissions)
                }
            })
        },
        breadcrumbs() {
            return AuthService.getBreadcrumbs();
        },

    }
}
</script>

<style scoped>
.app-nav-bar {
    background-color: rgb(37, 40, 48, 1) !important;
}

.app-app-bar {
    background-color: rgb(38, 50, 56, 1) !important;
    max-height: 76px !important;
}

.nav-user-card {
    min-height: 235px !important;
}

.center-element {
    display: block !important;
    margin: 0px auto !important;
}

.nav-logo {
    margin-top: 25px !important;
    margin-bottom: 15px !important;
}

.nav-avatar {
    border: 10px solid rgb(37, 40, 48, 1);
    margin-top: -50px !important;
    margin-bottom: 20px !important;
}

.nav-user-name {
    font-size: 20px !important;
    font-Weight: 500 !important;
    line-height: 28px;
    letter-spacing: 0.15px;
}

.nav-user-email {
    font-size: 12px !important;
    font-Weight: 400 !important;
    line-height: 14.76px;
    letter-spacing: 0.15px;
}

.nav-main-item {
    font-size: 16px !important;
    font-Weight: 500 !important;
    line-height: 28px !important;
    letter-spacing: 1.5px;
}

.nav-sub-item {
    font-size: 16px !important;
    font-Weight: 400 !important;
    line-height: 24px !important;
    letter-spacing: 1px;
}

.nav-active {
   background-color: #542E89 !important;
   color: white !important;
}
/*.navigation-menu .v-list-item--active {*/
/*    background-color:rgb(84,46,137,1) !important;*/
/*    color: white !important;*/
/*}*/
.vertical-divider {
    border-left: 2px solid rgb(223, 224, 235, 1);
    height: 30px;
    margin-right: 25px;
    margin-left: 25px;
}

.app-bar-icon {
    opacity: .7;
}

.app-bar-user-name {
    font-size: 14px !important;
    font-Weight: 600 !important;
    line-height: 20px !important;
    letter-spacing: .2px;
    margin-top: 15px;
    color: rgb(223, 224, 235, 1);
}

.app-bar-login-button {
    margin-right: 30px !important;
    margin-left: 60px !important;
}

.nav-group .v-list-group-active {
    background-color: #542E89 !important;
    color: white !important;
}


.avater-fullname-container{
    text-align:center;
    background: #5C229A;
    background: linear-gradient(to right bottom, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%) !important;
    width: 96px; height: 96px
}

.full-name-text{
    position: relative;
    top: 25%;
    font-size: 28px;
    font-weight: bold;
}
</style>
