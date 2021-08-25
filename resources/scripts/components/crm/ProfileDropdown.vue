<template>
    <v-card v-bind="attrs" v-on="on">
            <div class="d-flex pa-4">
                <div class="profile-img">
                    <v-avatar size="50" class="mr-3">
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg">
                    </v-avatar>
                </div>
                <div class="pro-info">
                    <h4>Dimuthu Sathrasinghe</h4>
                    <p class="ma-0 grey--text">sathrasinghe.dimuthu@barryplant.com.</p>
                </div>
            </div>

            <div class="my-2">
                <v-divider></v-divider>
            </div>
            <div class="pa-4">
                <v-btn outlined block @click="editProfile">
                    Edit My Profile
                </v-btn>
            </div>
            <v-btn @click="onLogout" color="error" block>
                Sign out
            </v-btn>
    </v-card>
</template>

<script>
import AuthService from "@scripts/services/AuthService";

export default {
  name: "ProfileDropdown",
    methods: {
        onLogout () {
            this.$emit('onLogout');
        },
        editProfile() {
            this.$emit('editProfile');
        }
    },
};
</script>

<style scoped>
</style>
