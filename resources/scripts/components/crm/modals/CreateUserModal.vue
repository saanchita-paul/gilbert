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
                            <div class="dialogs-title">
                                <p>Setup a new User</p>
                            </div>

                            <div class="dialogs-area">
                                <p class="title">New user details</p>
                                <v-text-field label="Job Title" v-model="user.job_title" placeholder="Property Manager / Admin / Director, etc....
" outlined dense></v-text-field>
                                <v-text-field
                                    v-model="user.first_name"
                                    label="Firstname*"
                                    placeholder="Firstname"
                                    outlined
                                    dense
                                ></v-text-field>
                                <v-text-field
                                    v-model="user.last_name"
                                    label="Lastname*"
                                    placeholder="Lastname"
                                    outlined
                                    dense
                                ></v-text-field>
                                <v-text-field   v-model="user.phone_number" label="Phone Number" placeholder="Phone Number" outlined dense></v-text-field>
                                <v-text-field    v-model="user.email" label="Email Address" placeholder="firstname.lastname@barryplantcamberwell.com.au" outlined dense></v-text-field>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <div class="d-flex justify-space-between">
                                <v-btn @click="cancelUser"
                                >Cancel
                                </v-btn>
                                <v-btn
                                       color="primary"
                                       @click="askConfirmation"
                                >
                                    Next
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
    name: "CreateUserModal",
    props:['dialog'],
    data() {
        return {
            user: {

            }
        }
    },
    methods: {
        askConfirmation()
        {
            this.$emit('goToNext',this.user);
        },
        cancelUser() {
         this.$emit('cancelUserDialog');
        }
    }
}
</script>

<style scoped>

</style>
