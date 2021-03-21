<template>
    <div id="app">
        <v-app id="inspire">
            <v-app id="inspire">
                <v-navigation-drawer
                    v-model="drawer"
                    app
                    class="app-nav-bg"
                    dark
                >
                    <v-list-item class="app-logo-area">
                        <v-list-item-avatar class="ml-0">
                            <v-img src="/assets/images/logo/hood-small.png"/>
                        </v-list-item-avatar>

                        <v-list-item-content>
        <!--                    <v-list-item-title class="white&#45;&#45;text">Hood</v-list-item-title>-->
                        </v-list-item-content>
                    </v-list-item>
                    <v-list dense>
                        <v-list-group
                            :value="false"
                            prepend-icon="mdi-chart-pie"
                        >
                            <template v-slot:activator>
                            <v-list-item-title>Dashboard</v-list-item-title>
                            </template>

                            <v-list-item link>
                                <v-list-item-action>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>Utility</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            
                            <v-list-item link>
                                <v-list-item-action>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>Chatbot</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group>
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-account-group</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Customers</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-account-tie</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Helpdesk</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>
        
                <v-app-bar app class="app-nav-bg" dark>
                     <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
                    <v-toolbar-title>
                        <h4>Dashboard</h4>
                        <h6>Customer / List</h6>
                    </v-toolbar-title>
                    <v-spacer/>
                    <v-icon>mdi-magnify</v-icon>
                    <v-divider vertical></v-divider>
                    <h6>Jones Ferdinand</h6>
                    <v-icon>mdi-login</v-icon>
                </v-app-bar>
                
                <v-main>
                    <router-view></router-view>
                </v-main>
            </v-app>
        </v-app>
    </div>
</template>

<script>

export default {
    name: "NewDashboardLayout",
    data() {
        return {
            drawer: null
        }
    }
}
</script>

<style scoped>
.app-nav-bg {
    background-color: rgb(38,50,56,1) !important;
}

</style>
