<template>
    <div id="app">
        <v-app id="inspire">
            <v-app id="inspire">
                <v-navigation-drawer
                    app
                >
                    <v-list dense>
                        <v-list-group
                            :value="false"
                            prepend-icon="mdi-home"
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
                                <v-icon>mdi-home</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Customers</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item link>
                            <v-list-item-action>
                                <v-icon>mdi-email</v-icon>
                            </v-list-item-action>
                            <v-list-item-content>
                                <v-list-item-title>Helpdesk</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>
        
                <v-app-bar app color="indigo" dark>
                    <v-toolbar-title>
                        <h4>Dashboard</h4>
                        <h6>Customer / List</h6>
                    </v-toolbar-title>
                    <v-spacer/>
                    <v-icon>mdi-home</v-icon>
                    <v-divider vertical></v-divider>
                    <h6>Jones Ferdinand</h6>
                    <v-icon>mdi-home</v-icon>
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
            
        }
    }
}
</script>

<style scoped>

</style>
