<template>
            <v-col cols="12" >
                 <v-card class="pa-4">
                     <h2 v-if="user.proerty_manager_name" class="page-title my-5 primary--text">
                         {{lead.first_name}} {{lead.last_name}} has been {{ assignedText }} to {{user.proerty_manager_name}}.
                     </h2>
                     <h2 v-else class="page-title my-5 primary--text">
                         {{lead.first_name}} {{lead.last_name}} has been {{ assignedText }} to {{user.first_name + ' ' + user.last_name}}.
                     </h2>
                    <p>It will now show on the his/her “My Leads”</p>
                    <v-btn color="primary" class="mx-auto" @click="cancelModal">Done</v-btn>
                 </v-card>
            </v-col>
</template>

<script>
export default {
  name: "AssignedtoPopUp",
    props:['lead', 'user', 'assignedText'],
    methods: {
        cancelModal()
        {
            this.$emit('cancelModal');
        }
    },
    mounted() {
      console.log(this.user);
    }
};
</script>

<style scoped>
</style>
