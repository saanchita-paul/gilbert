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
                                <p>Confirm New User Details</p>
                            </div>

                            <div class="dialogs-area">
                                <p class="title">New user details</p>
                                <v-row>
                                    <v-col cols="4" class="pb-1 pt-0" ><h5 class="text-align-right" >Job Title</h5></v-col><v-col cols="8"  class="pb-1 pt-0">{{role}}</v-col>
                                    <v-col cols="4"   class="pb-1 pt-0" ><h5 class="text-align-right" >First Name</h5></v-col><v-col cols="8"  class="pb-1 pt-0">{{user.first_name}}</v-col>
                                    <v-col cols="4"  class="pb-1 pt-0" ><h5 class="text-align-right" >Last Name</h5></v-col><v-col cols="8"  class="pb-1 pt-0">{{user.last_name}}</v-col>
                                    <v-col cols="4"  class="pb-1 pt-0" ><h5 class="text-align-right" >Phone Number</h5></v-col><v-col cols="8"  class="pb-1 pt-0">{{user.phone}}</v-col>
                                    <v-col cols="4" class="pb-1 pt-0" ><h5 class="text-align-right" >Email</h5></v-col><v-col cols="8"  class="pb-1 pt-0">{{user.email}}</v-col>
                                </v-row>
                            </div>

                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <div class="d-flex justify-space-between">
                                <v-btn @click="backToEdit"
                                >Back To Edit
                                </v-btn>
                                <v-btn
                                    color="primary"
                                    @click="askConfirmation"
                                >
                                    Confirm
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
import UserRoles from "@scripts/data/UserRoles";

export default {
    name: "UserCreationConfirmationModal",
    props:['dialog','user'],
    data() {
        return {
        }
    },
    computed: {
        role() {
            return this.user
                ? UserRoles.AGENCY.find(role => role.value === this.user.job_title)?.text
                : this.user?.job_title;
        }
    },
    methods: {
        askConfirmation()
        {
            // console.log(this.user);
            this.$emit('confirmData');
        },
        backToEdit() {
            this.$emit('backToEdit',this.user);
        }
    }
}
</script>

<style scoped>
.text-align-right {
text-align: right;
}
</style>
