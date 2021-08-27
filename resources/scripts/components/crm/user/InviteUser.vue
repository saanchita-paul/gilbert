<template>
    <v-app>

        <v-container >
                <v-row  class="d-flex justify-center">
                    <v-col cols="5">
                        <v-card v-if="isLoaded == 1" class="pa-4 hood-card">
                          <ValidationObserver ref="create_agency">
                            <div class="login-header">
                                <v-img  src="/assets/images/logo.png"/>
                                <p class="dialogs-title">Hello  {{user.first_name}}</p>
                                <p class="primary-text">Confirm NEW Password</p>
                            </div>
                          <ValidationProvider name="password" rules="required"  v-slot="{ errors }">
                            <v-text-field
                            label="Password *"
                            outlined
                            dense
                            v-model="new_user.password"
                            placeholder="password"
                            :type="'password'"
                            :error-messages=" errors[0]"

                        ></v-text-field>
                          </ValidationProvider>
                            <ValidationProvider name="Confirm Password" rules="required|confirmed:password"  v-slot="{ errors }">
                        <v-text-field
                            label="Confirm Password"
                            outlined
                            v-model="new_user.confirm_password"
                            dense
                            :type="'password'"
                            :error-messages=" errors[0]"
                            placeholder="confirm password"
                        ></v-text-field>
                            </ValidationProvider>
                         <v-btn block color="primary" @click="setPassword">Set Password</v-btn>
                          </ValidationObserver>
                        </v-card>

                      <v-card  v-if="isLoaded == 2" class="pa-4 hood-card">
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
import AuthService from "@scripts/services/AuthService";

export default {
name: "InviteUser",
    data() {
    return {
        new_user: {
          password:'',
          confirm_password: '',
            id: '',
        },
      user: {
        first_name: '',
        last_name: '',
          email: null,
      },
      isLoaded: false,
    }
    },
    methods:{
       async setPassword() {

         let v = await this.$refs.create_agency.validate();
         if(!v) return;
         try {
             const isValidUser = await UserInvitationService.savePassword({...this.new_user, ...{token: this.$route.query.token}}, this.new_user.id);
             await AuthService.login({email: this.user.email, password: this.new_user.password})
         } catch (err) {
             console.log(err)
         }

        },
      async validateToken() {
        const token = this.$route.query.token;
        const isValidUser = await UserInvitationService.validateToken(token);
        if(isValidUser?.success)
        {
          this.user.first_name = isValidUser.data.user.profile.first_name;
          this.user.email = isValidUser.data.user.email;
          this.new_user.id = isValidUser.data.id;
          this.isLoaded = 1;
        } else
        {
          this.isLoaded = 2;
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
