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
                <v-btn outlined block>
                    Edit My Profile
                </v-btn>
            </div>
            <v-btn color="error" block>
                Sign out
            </v-btn>
    </v-card>
</template>

<script>
export default {
  name: "ProfileDropdown",
};
</script>

<style scoped>
</style>
