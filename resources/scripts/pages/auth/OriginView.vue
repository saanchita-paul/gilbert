<template>
    <v-app>
        <div fluid>
            <v-container fluid>
               <v-card>
                   <p> Test </p>
               </v-card>

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
