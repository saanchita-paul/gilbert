<template>
    <v-app>
        <div  class="loginpage">
            <v-container >
                <div class="login-section">

                    <v-card class="hood-card py-5">
                            <div class="login-header">
                                <v-img  src="/assets/images/logo.png"/>
                                <p class="dialogs-title">Welcome!</p>
                                <p class="primary-text">Login and start managing your applications</p>
                            </div>

                            <v-fade-transition>
                                <div
                                    v-if="isLoginFailed"
                                    style="border-radius: 10px"
                                    class="red white--text pa-2 ma-3 text-center app-title-small"
                                >Login failed! Invalid credentials
                                </div>
                            </v-fade-transition>

                            <v-card-text>
                                <validation-observer
                                    ref="observer"
                                    v-slot=""
                                >
                                    <form @submit.prevent="onSubmit">
                                        <validation-provider
                                            v-slot="{ errors }"
                                            name="email"
                                            rules="required|email"
                                        >
                                            <v-text-field
                                                v-model="form.email"
                                                :error-messages="errors"
                                                label="Email"
                                                required
                                                outlined
                                                dense
                                                @keyup.enter="onSubmit"
                                            ></v-text-field>
                                        </validation-provider>
                                        <validation-provider
                                            v-slot="{ errors }"
                                            name="password"
                                            rules="required"
                                        >
                                            <v-text-field
                                                v-model="form.password"
                                                :error-messages="errors"
                                                label="Password"
                                                type="password"
                                                required
                                                outlined
                                                dense
                                                @keyup.enter="onSubmit"
                                            ></v-text-field>
                                        </validation-provider>
                                    </form>
                                </validation-observer>
                            </v-card-text>
                            <div class="card-actions">
                                <div class="actions-link">
                                    <validation-provider
                                        v-slot=""
                                        rules="required"
                                        name="checkbox"
                                    >
                                        <v-checkbox
                                            v-model="form.remember_me"
                                            :error-messages="errors"
                                            value="1"
                                            label="Remember me"
                                            type="checkbox"
                                            required
                                        ></v-checkbox>
                                    </validation-provider>
                                    <router-link class="forgot-text" :to="{name: 'forgot.password'}">Forgot password?</router-link>
                                </div>
                                <div>
                                    <v-btn
                                        :loading="loginLoading"
                                        block
                                        class="primary white--text mt-2 mb-3"
                                        @click="onLogin"
                                    >LOGIN
                                    </v-btn>
                                </div>
                            </div>
                    </v-card>
                </div>
            </v-container>

        </div>
    </v-app>
</template>

<script>

import AuthService from "@scripts/services/AuthService";

export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
                remember_me: false,
            },
            loginLoading: false,
            isLoginFailed: false,
            errors: null,
        }
    },
    mounted() {
        console.log("AUTH", AuthService.isAuthenticated())
    },

    methods: {
        async onLogin() {
            this.isLoginFailed = false;
            this.loginLoading = true;
            if (!(await AuthService.login(this.form))) {
                this.isLoginFailed = true;
            } else {
                //await this.$router.push({name: 'dashboard.utility'})
            }
            this.loginLoading = false;
        },
        async onSubmit() {
            if (await this.$refs.observer.validate()) {
                await this.onLogin()
            }
        }
    },
}
</script>

<style scoped>

</style>
