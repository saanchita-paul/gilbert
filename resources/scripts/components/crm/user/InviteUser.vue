<template>
    <v-app>
        <v-container>
            <v-card class="pa-4">
                <v-row>
                    <v-col cols="12 pb-0">
                    </v-col>

                    <v-col cols="10">
                        <v-text-field
                            label="Password *"
                            outlined
                            dense
                            v-model="user.password"
                            placeholder="password"
                            :type="'password'"

                        ></v-text-field>
                    </v-col>

                    <v-col cols="10">
                        <v-text-field
                            label="Email Address (Optional)"
                            outlined
                            v-model="user.password_confirm"
                            dense
                            :type="'password'"
                            placeholder="confirm password"
                        ></v-text-field>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col cols="1">
                        <v-btn @click="setPassword">Set Password</v-btn>
                    </v-col>
                </v-row>
            </v-card>
        </v-container>
        </v-app>
</template>

<script>
export default {
name: "InviteUser",
    data() {
    return {
        user: {
            password:'',
            password_confirm: '',
        }
    }
    },
    methods:{
        setPassword() {

        }
    }
}
</script>

<style scoped>

</style>
