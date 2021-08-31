<template>
    <v-app>
        <v-container style="height: 100%">
            <div style="height: 100%; display: flex; justify-content: center; align-items: center">
                <v-card width="400px">
                    <v-card-title class="primary white--text">Forgot Password</v-card-title>

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
                                        <span>Please check your email to get the reset link</span>
                                        <v-btn flat color="red" @click="snackbar = false">Close</v-btn>
                                    </v-snackbar>
                                </v-fade-transition>
                                <validation-provider
                                        v-slot="{ errors }"
                                        name="email"
                                        rules="required|email"
                                >
                                    <v-text-field
                                            v-model="email"
                                            :error-messages="errors"
                                            label="Email"
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
                try {
                    //this.successMessage = true;
                    const response = await axios.post('/api/forgot-password', {
                        email: this.email
                    });
                    this.snackbar = true;
                    console.log(response);
                } catch (e) {
                    this.errorMessage = true;
                }
            }
        },
    }
</script>

<style scoped>

</style>
