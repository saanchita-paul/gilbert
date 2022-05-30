<template>
    <v-card v-bind="attrs" v-on="on">
            <div class="d-flex pa-4">
                <div class="profile-img">
                    <v-avatar size="50" :color="avaterColor" class="mr-3">
                            <img v-if="profileImageFlag" src="https://cdn.vuetifyjs.com/images/john.jpg">
                        <span  v-else class="white--text">{{profile_display_name}}</span>
                    </v-avatar>
                </div>
                <div class="pro-info">
                    <h4 class="profile-user-name">{{ user.profile.first_name }} {{ user.profile.last_name }}</h4>
                    <p class="ma-0 grey--text user-email">{{ user.email}}</p>
                </div>
            </div>

            <div class="my-2">
                <v-divider></v-divider>
            </div> 
            <div class="button-style" block @click="editProfile">
                 <v-icon >mdi-account-edit</v-icon> Edit My Profile
            </div>
            <div class="button-style" block>
                <v-icon >mdi-dock-top</v-icon><a target=”_blank” href="https://www.hoodrewards.ai/">View MyRewards Points</a>
            </div>
            <div class="button-style" block>
                <v-icon >mdi-cart</v-icon> <a target=”_blank” href="https://www.google.com/">Shop Using MyRewards Points</a>
            </div>
            <!-- <div class="pa-4">
                <v-btn outlined block @click="editProfile">
                    Edit My Profile
                </v-btn>
            </div> -->
            <div class="button-style" block @click="onLogout" style="background-color:#ea4b64; color: white" >
                Sign out
            </div>
    </v-card>
</template>

<script>
import AuthService from "@scripts/services/AuthService";

export default {
  name: "ProfileDropdown",
    props:['user','profile_display_name','profileImageFlag'],
    computed: {
        avaterColor()
        {
            return this.profileImageFlag?'transperent':'primary';
        }
    },
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

.profile-user-name {
    /*font-family: Roboto;*/
    /*font-size: 16px;*/
    /*font-style: normal;*/
    /*font-weight: 700;*/
    /*line-height: 21px;*/
    /*letter-spacing: 0em;*/
    /*background: #542E89;*/
    /*text-align: left;*/
}
.user-email {
    /*font-family: Roboto;*/
    /*font-size: 12px;*/
    /*font-style: normal;*/
    /*font-weight: 400;*/
    /*line-height: 16px;*/
    /*letter-spacing: 0em;*/
    /*background: #7E8A8F;*/
    /*text-align: left;*/
}

.button-style {
    padding: 10px ;
    cursor: pointer;
    font-family: Arial, Helvetica, sans-serif;
    background-color: white;
}
.button-style:active {
  background-color: whitesmoke;
}
a:hover, a:visited, a:link, a:active {
    color: inherit;
    padding-left: 4px;
    text-decoration: none
}

</style>
