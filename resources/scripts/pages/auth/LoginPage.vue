<template>
    <v-app>
        <v-container style="height: 100%"  >
            <div style="height: 100%; display: flex; justify-content: center; align-items: center">
                <v-card width="400px">
                    <v-card-title class="primary white--text">LOGIN</v-card-title>
                    <v-card-text>
                        <validation-observer
                            ref="observer"
                            v-slot=""
                        >
                            <validation-provider
                                v-slot="{ errors }"
                                name="Name"
                                rules="required|max:10"
                            >
                                <v-text-field
                                    v-model="name"
                                    :counter="10"
                                    :error-messages="errors"
                                    label="Name"
                                    required
                                ></v-text-field>
                            </validation-provider>
                            <validation-provider
                                v-slot="{ errors }"
                                name="email"
                                rules="required|email"
                            >
                                <v-text-field
                                    v-model="email"
                                    :error-messages="errors"
                                    label="E-mail"
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
                                    v-model="checkbox"
                                    :error-messages="errors"
                                    value="1"
                                    label="Remember me?"
                                    type="checkbox"
                                    required
                                ></v-checkbox>
                            </validation-provider>
                            <v-btn  rounded class="primary white--text mt-2"  @click="onLogin">
                                LOGIN
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
        </v-container>
    </v-app>
</template>

<script>

export default {
    data: () => ({
        name: '',
        email: '',
        select: null,
        errors: null,
        checkbox: null,
    }),

    methods: {
        onLogin() {
            this.$router.push({name: 'customerAnalytics'})
        },
    },
}
</script>

<style scoped>

</style>
