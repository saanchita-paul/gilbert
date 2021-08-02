<template>
    <v-app id="inspire" v-if="user" style="position: relative" ref="mainSpinner">
        <v-app-bar app class="app-app-bar" dark>
            <v-img
                class="mx-2"
                src="/assets/images/logo/hood_logo_while.png"
                max-height="40"
                max-width="40"
                contain
            ></v-img>

            <v-toolbar-title class="ml-2">
                AgencyName Office
            </v-toolbar-title>

            <v-spacer/>

            <v-toolbar-title class="mx-4">
                <h6>Dimuthu Sathrasinghe</h6>
                <h6>dimuthu.sathrasinghe@brccappital.com.au</h6>
            </v-toolbar-title>
            <v-img
                class="mx-2"
                src="/assets/images/logo/hood_logo_while.png"
                max-height="40"
                max-width="40"
                contain
            ></v-img>
        </v-app-bar>

        <v-main>
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
import ApplicationService from "../services/ApplicationService";
import AuthService from "@scripts/services/AuthService";

export default {
    name: "NewDashboardLayout",
    data() {
        return {
            user: null,
            drawer: null,
            routes: ApplicationService.getMainNavigationRoutes()
        }
    },
    mounted() {
        this.user = AuthService.getAuthUser();
        setInterval(AuthService.authUser, 300000)
    },
     methods: {
        async onLogout() {
            await AuthService.logout();
        },
    },
}
</script>

<style scoped>
.app-app-bar {
    background-color: #542E89 !important;
    max-height: 76px !important;
}

.app-bar-user-name {
    font-size: 14px !important;
    font-Weight: 600 !important;
    line-height: 20px !important;
    letter-spacing: .2px;
    margin-top: 15px;
    color: rgb(223, 224, 235, 1);
}
</style>
