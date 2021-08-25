<template>
    <v-app>
        <v-container >
                <v-row  class="d-flex justify-center">
                    <v-col cols="5">
                        <v-card v-if="isLoaded" class="pa-4 hood-card">
                            <div class="login-header">
                                <v-img  src="/assets/images/logo.png"/>
                                <p class="dialogs-title">Hello  jones</p>
                                <p class="primary-text">Confirm NEW Password</p>
                            </div>
                            <v-text-field
                            label="Password *"
                            outlined
                            dense
                            v-model="user.password"
                            placeholder="password"
                            :type="'password'"

                        ></v-text-field>


                        <v-text-field
                            label="Email Address (Optional)"
                            outlined
                            v-model="user.password_confirm"
                            dense
                            :type="'password'"
                            placeholder="confirm password"
                        ></v-text-field>
                         <v-btn block color="primary" @click="setPassword">Set Password</v-btn>
                        </v-card>
                      <v-card v-else class="pa-4 hood-card">
                        <p>Your token is expired or invalidated</p>
                        <v-btn @click="goToLogin" color="primary">ok</v-btn>
                      </v-card>
                    </v-col>
                </v-row>
        </v-container>
        </v-app>
</template>

<script>
import UserInvitationService from "@scripts/services/crm/UserInvitationService";

export default {
name: "InviteUser",
    data() {
    return {
        user: {
            password:'',
            password_confirm: '',
        },
      isLoaded: false,
    }
    },
    methods:{
        setPassword() {
        },
      async validateToken() {
        const token = this.$route.query.token;
        const isValid = await UserInvitationService.validateToken(token);
        if(isValid?.success)
        {
          this.isLoaded =true;
        }
      },

      goToLogin() {
        this.$router.push({name:'login'});
      }
    },

  mounted() {
    this.validateToken();

  }
}
</script>

<style scoped>

</style>
