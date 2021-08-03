<template>
    <div>
        <v-menu
            bottom
            origin="center center"
            transition="scale-transition"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn text
                        v-bind="attrs"
                        v-on="on"
                        >
                       <v-avatar size="30"
                            v-on="on">
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg">
                        </v-avatar>
                        <v-icon x-small>mdi-menu-down</v-icon>
                    </v-btn>
                    
                    
                </template>

                <v-list>
                    <div class="assigneesearch">
                        <v-text-field
                            label="Search"
                            outlined
                            dense
                            prepend-inner-icon="mdi-magnify"
                            hide-details="auto"
                        ></v-text-field>
                    </div>
                    <v-list-item>
                        <v-avatar size="30">
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg">
                        </v-avatar>
                        <small  class="pl-2">John Doe</small>
                    </v-list-item>
                    <v-list-item>
                            <v-avatar size="30">
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg">
                        </v-avatar>
                        <small class="pl-2">John Doe</small>
                    </v-list-item>
                    <v-list-item>
                            <v-avatar size="30">
                                <img src="https://cdn.vuetifyjs.com/images/john.jpg">
                            </v-avatar>
                        <small class="pl-2">John Doe</small>
                    </v-list-item>
                </v-list>
    </v-menu>
    </div>
</template>

<script>
export default {
  name: "AssigneeDropdown",

};
</script>

<style scoped>
</style>
