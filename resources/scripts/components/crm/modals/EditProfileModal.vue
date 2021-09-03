<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"

        >
    <EditingProfile @cancleModal="cancleModal" @saveUserSuccess="saveUserSuccess" :user="user"></EditingProfile>
        </v-dialog>
    </v-row>
</template>

<script>
import EditingProfile from "@scripts/components/crm/EditingProfile";
export default {
name: "EditProfileModal",
    components: {EditingProfile},
    props:['dialog','user'],
    methods: {
        cancleModal() {
            this.$emit('cancleModal');
        },
        saveUserSuccess() {
            this.$emit('saveUserSuccess');
        }
    },
    mounted() {
    }

}
</script>

<style scoped>

</style>
