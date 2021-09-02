<template>
    <ValidationObserver ref="create_agency">
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
                                <ValidationProvider name="Job Title" rules="required"  v-slot="{ errors }">
                                    <v-select label="Job Title" v-model="user.job_title" :items="roles" item-value="value" item-text="text"
                                                  placeholder="Property Manager / Admin / Director, etc...."
                                              :error-messages=" errors[0]"
                                              outlined dense>

                                    </v-select>
                                </ValidationProvider>
                                <ValidationProvider name="First Name" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        v-model="user.first_name"
                                        label="Firstname*"
                                        placeholder="Firstname"
                                        name="First Name"
                                        outlined
                                        dense
                                        :error-messages=" errors[0]"
                                    ></v-text-field>
                                </ValidationProvider>
                                <ValidationProvider name="Last Name" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        v-model="user.last_name"
                                        label="Lastname*"
                                        placeholder="Lastname"
                                        outlined
                                        dense
                                        :error-messages=" errors[0]"
                                    ></v-text-field>
                                </ValidationProvider>
                                <ValidationProvider name="Phone" rules="cv-phone|length:10"  v-slot="{ errors }">
                                    <v-text-field   v-model="user.phone" label="Phone Number" :error-messages=" errors[0]" placeholder="Phone Number" outlined dense></v-text-field>
                                </ValidationProvider>
                                <ValidationProvider name="Email" rules="required|email|unique-user-email"  v-slot="{ errors }">
                                    <v-text-field  :error-messages=" errors[0]"   v-model="user.email" label="Email Address" placeholder="firstname.lastname@barryplantcamberwell.com.au" outlined dense></v-text-field>
                                </ValidationProvider>
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
    </ValidationObserver>
</template>

<script>
import UserRoles from "@scripts/data/UserRoles";

export default {
    name: "CreateUserModal",
    props:['dialog'],
    data() {
        return {
            user: {}
        }
    },
    computed: {
        roles() {
            return UserRoles.AGENCY
        }
    },
    methods: {
        async askConfirmation()
        {
            let v = await this.$refs.create_agency.validate();
            if (v) {
                this.$emit('goToNext',this.user);
            }
        },
        cancelUser() {
         this.$emit('cancelUserDialog');
        }
    },
}
</script>

<style scoped>

</style>
