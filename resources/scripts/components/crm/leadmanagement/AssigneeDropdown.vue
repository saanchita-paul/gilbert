<template>
    <div v-if="lead">
        <v-menu
            bottom
            origin="center center"
            transition="scale-transition"
            :close-on-content-click="false"
            v-model="menu"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn text
                        v-bind="attrs"
                        v-on="on"
                        >
                       <v-avatar size="30"
                            v-on="on">
                           <v-icon color="grey" large>mdi-account-circle</v-icon>
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
                            v-model="search"
                            @input="changeInput"
                        ></v-text-field>
                    </div>
                    <v-list-item v-for="user in users" :key="user.id"
                                 @mouseover="selectedUser = user.id" @mouseleave="selectedUser = -1"
                                 class="cursor-pointer list-tile" @click="assignUser(user)">
                            <v-avatar size="30">
                                <img v-if="user.profile_img" v-bind:src="user.profile_img">
                                <v-icon v-else large>mdi-account-circle</v-icon>
                            </v-avatar>
                            <small  class="pl-2">{{user.proerty_manager_name}}</small>
                            <v-spacer></v-spacer>
                            <span v-show="selectedUser===user.id"
                                  flat>
                                <small>{{ getAssignText() }}</small>
                                <v-icon small>mdi-menu-right</v-icon>
                            </span>
                    </v-list-item>
                </v-list>
        </v-menu>
    </div>
</template>

<script>
export default {
  name: "AssigneeDropdown",
    props:{
      users:{
          required: true
      },
        lead: {
          required: true
        }
    },
    data() {
      return {
          menu: false,
          search: '',
          selectedUser: false,
      }
    },

    methods: {
        assignUser(user) {
            this.$emit('assignUser', user, this.lead);
        },
        changeInput() {
            this.$emit('updateSearch', this.search);
        },
        getAssignText() {
            return this.lead?.assigned_to ? 'Reassign' : 'Assign';
        }
    },
    mounted() {

    }
};
</script>

<style scoped>
</style>
