<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <AssignedtoPopUp :lead="lead" :user="user" :assignedText="assignedText" @cancelModal="cancelModal"></AssignedtoPopUp>
        </v-dialog>
    </v-row>
</template>

<script>
import AssignedtoPopUp from "@scripts/components/crm/leadmanagement/AssignedtoPopUp";
export default {
name: "ReassignModal",
    components: {AssignedtoPopUp},
    props:['dialog', 'user', 'lead', 'assignedText'],
    methods: {
        cancelModal() {
            this.$emit('cancelModal');
        }
    },
    mounted() {
        // console.log(this.dialog);
        // console.log(this.user);
        // console.log(this.user);
    }
}
</script>

<style scoped>

</style>
