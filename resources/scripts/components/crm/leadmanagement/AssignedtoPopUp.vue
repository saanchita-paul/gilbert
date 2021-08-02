<template>
        <v-row class="justify-center text-center">
            <v-col cols="4" >
                 <v-card class="pa-4">
                     <h2 class="page-title my-5 primary--text">[Firstname_Lastname] has been assigned to [Service Rep Name].</h2>
                    <p>It will now show on the his/her “My Leads”</p>
                    <v-btn color="primary" class="mx-auto">Done</v-btn>
                 </v-card>
            </v-col>
        </v-row>
</template>

<script>
export default {
  name: "AssignedtoPopUp",
};
</script>

<style scoped>
</style>
