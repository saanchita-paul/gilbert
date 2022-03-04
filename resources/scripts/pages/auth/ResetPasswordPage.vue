<template>
    <v-app class="reset-password">
        <v-container style="height: 100%">
            <div style="height: 100%; display: flex; justify-content: center; align-items: center">
                <v-card width="400px" v-if="isValidToken">
                    <v-card-title class="primary white--text">Reset Password</v-card-title>

                    <v-card-text>
                        <validation-observer
                                ref="observer"
                                v-slot=""
                        >
                            <form @submit.prevent="handleSubmit">
                                <v-fade-transition>
                                    <div
                                            v-if="errorMessage"
                                            style="border-radius: 10px"
                                            class="red white--text pa-2 ma-3 text-center app-title-small"
                                    >Error occured.
                                    </div>
                                    <v-snackbar v-model="snackbar" :timeout="5000" top color="success">
                                        <span>Your password has been reset successfully!</span>
                                        <v-btn flat @click="snackbar = false">Close</v-btn>
                                    </v-snackbar>
                                </v-fade-transition>
                                <validation-provider
                                        v-slot="{ errors }"
                                        name="password"
                                        rules="required|password"
                                >
                                    <v-text-field
                                            v-model="password"
                                            :error-messages="errors"
                                            label="Password"
                                            type="password"
                                            required
                                            @keyup.enter="handleSubmit"
                                    ></v-text-field>
                                </validation-provider>
                                <validation-provider
                                        v-slot="{ errors }"
                                        name="confirm_password"
                                        rules="required|confirmed:password"
                                >
                                    <v-text-field
                                            v-model="confirm_password"
                                            type="password"
                                            :error-messages="errors"
                                            label="Confirm Password"
                                            required
                                            @keyup.enter="handleSubmit"
                                    ></v-text-field>
                                </validation-provider>
                            </form>
                        </validation-observer>
                    </v-card-text>
                    <v-card-actions>
                        <div class="d-flex justify-space-between" style="width: 100%">
                            <v-btn
                                    :loading="loginLoading"
                                    rounded
                                    class="primary white--text mt-2"
                                    @click="handleSubmit"
                            >Submit
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
        </v-container>
    </v-app>
</template>

<script>

    import ResetPasswordService from "@scripts/services/crm/ResetPasswordService";
    export default {
        name: 'ResetPasswordPage',
        data() {
            return {
                loginLoading: false,
                password: '',
                confirm_password: '',
                snackbar: false,
                errorMessage: '',
                isValidToken: false,
            }
        },
        methods: {
            async handleSubmit() {
                try{
                    await ResetPasswordService.savePassword({
                        password: this.password,
                        confirm_password: this.confirm_password,
                        token: this.$route.params.token
                    });
                    this.snackbar = true;
                    await this.$router.push({name: 'login'})
                } catch (e){
                    console.log('error', e);
                    this.errorMessage = true;
                }

            },
            async validatedToken() {
               this.isValidToken = await ResetPasswordService.checkIsValidateToken({token: this.$route.params.token});
               if(!this.isValidToken) {
                   await this.$router.push({name: 'login'});
               }
            }
        },
       async mounted() {
           await this.validatedToken();
        }
    }
</script>

<style scoped>
.reset-password{
    padding: 0px;
    height: 100%;
    background-image: url(/assets/images/loginbg_new.png)  !important;
    background-position: center !important;
    background-size: cover !important;
}
</style>
