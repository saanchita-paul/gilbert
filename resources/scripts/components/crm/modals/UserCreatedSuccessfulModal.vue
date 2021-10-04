<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <v-row>
                        <v-col class="section-dialogs" cols="12">
                            <div class="dialogs-area">
                                <p class="title">{{this.user.first_name +' '+ this.user.last_name }} has been added</p>
                                <p>An email has been sent to this user. Please inform user to verify the email</p>
                            </div>

                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <div class="d-flex justify-center">
                                <v-btn @click="save"  color="primary"
                                >Done
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
name: "UserCreatedSuccessfulModal",
    props:['dialog','user'],
    methods: {
    save() {
        this.$emit('done');
    }
    }
}
</script>

<style scoped>

</style>
