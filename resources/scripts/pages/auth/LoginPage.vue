<template>
    <v-app>
        <v-container style="height: 100%"  >
            <div style="height: 100%; display: flex; justify-content: center; align-items: center">
                <v-card width="400px">
                    <v-card-title class="primary white--text">LOGIN</v-card-title>
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
                                ></v-text-field>
                            </validation-provider>
                        </validation-observer>
                    </v-card-text>
                    <v-card-actions>
                        <div class="d-flex justify-space-between" style="width: 100%">
                            <validation-provider
                                v-slot=""
                                rules="required"
                                name="checkbox"
                            >
                                <v-checkbox
                                    v-model="form.remember_me"
                                    :error-messages="errors"
                                    value="1"
                                    label="Remember me?"
                                    type="checkbox"
                                    required
                                ></v-checkbox>
                            </validation-provider>
                            <v-btn
                                :loading="loginLoading"
                                rounded
                                class="primary white--text mt-2"
                                @click="onLogin"
                            >LOGIN
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
        </v-container>
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
                await this.$router.push({name: 'dashboard'})
            }
            this.loginLoading = false;
        },
    },
}
</script>

<style scoped>

</style>
