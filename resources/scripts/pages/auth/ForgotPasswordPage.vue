<template>
    <v-app>
        <div class="loginpage">
            <v-container style="height: 100%" fluid>
            <div style="height: 100%; display: flex; justify-content: center; align-items: center">
                <v-card width="400px">
                    <v-card-title class="primary white--text">Forgot Password</v-card-title>

                    <v-card-text>
                        <validation-observer v-if="!snackbar"
                                ref="observer"
                                v-slot=""
                        >
                            <form @submit.prevent="handleSubmit">
<!--                                <v-fade-transition>-->
<!--                                    <div-->
<!--                                            v-if="errorMessage"-->
<!--                                            style="border-radius: 10px"-->
<!--                                            class="red white&#45;&#45;text pa-2 ma-3 text-center app-title-small"-->
<!--                                    >Error occured.-->
<!--                                    </div>-->
<!--                                    <v-snackbar v-model="snackbar" :timeout="5000" top color="success">-->
<!--                                        <span>Please check your email to get the reset link</span>-->
<!--                                        <v-btn flat color="red" @click="snackbar = false">Close</v-btn>-->
<!--                                    </v-snackbar>-->
<!--                                </v-fade-transition>-->
                                <validation-provider
                                        v-slot="{ errors }"
                                        name="email"
                                        rules="required|email|email-exist"
                                >
                                    <v-text-field
                                            class="mt-7"
                                            v-model="email"
                                            :error-messages="errors"
                                            label="Email"
                                            outlined
                                            required
                                            dense
                                            @keyup.enter="handleSubmit"
                                    ></v-text-field>
                                </validation-provider>
                            </form>
                        </validation-observer>
                        <p v-if="snackbar" class="mt-5 send-success-email">
                            We ‘ve sent an email to {{email}} to reset your password.
                        </p>


                    </v-card-text>
                    <v-card-actions>
                        <div class="d-flex justify-space-around" style="width: 100%">
                            <v-row class="pa-2">
                                <v-col :cols="snackbar?'12':'6'" >
                                    <v-btn
                                        :color="snackbar?'primary white--text':'#E0E0E0 #7E8A8F--text'"
                                        class=" mt-2"
                                        block
                                        @click="backToLogin"
                                    >Back to login
                                    </v-btn>
                                </v-col>
                                <v-col cols="6" v-if="!snackbar">
                                    <v-btn
                                        :loading="loginLoading"
                                        block
                                        color="primary white--text"
                                        class=" mt-2"
                                        @click="handleSubmit"
                                    >Submit
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
        </v-container>
        </div>
    </v-app>
</template>

<script>

    import axios from 'axios';

    export default {
        name: 'ForgotPasswordPage',
        data() {
            return {
                email: '',
                loginLoading: false,
                //isSubmitFailed: false,
                errors: null,
                snackbar: false,
                errorMessage: ''
            }
        },

        methods: {
            async handleSubmit() {
                let v = await this.$refs.observer.validate();
                if (!v) {
                   return;
                }


                try {
                    this.loginLoading = true;
                    //this.successMessage = true;
                    const response = await axios.post('/api/forgot-password', {
                        email: this.email,
                    });
                    this.snackbar = true;
                } catch (e) {
                    this.loginLoading = false;
                    this.errorMessage = true;
                }
            },
            backToLogin() {
                this.$router.push({name:'login'});
            }
        },
    }
</script>

<style scoped>
.v-messages__message{
    text-align: end !important;
}
.send-success-email{


    font-family: Roboto;
    font-size: 20px;
    line-height: 30px;
    text-align: center;
    color: #000000;
}

</style>
