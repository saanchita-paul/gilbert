<template>
    <div class="crm-text-field" >
        <div class="field-label">
            <span>Authorised Person</span>
        </div>
        <div class="text-field" >
            <ValidationProvider name="Authorised Person" rules="required"  v-slot="{ errors }">
                <v-text-field
                    indentification
                    :error-messages=" errors[0]"
                    @click="openProfileForm"
                    outlined
                    dense
                    hide-details="auto"
                ></v-text-field>
            </ValidationProvider>
        </div>
        <AuthorizedPersonProfileForm @closeModal="closeModal" v-if="dialog" dialog="dialog">

        </AuthorizedPersonProfileForm>
    </div>

</template>

<script>
import AuthorizedPersonProfileForm from "@scripts/components/crm/leadmanagement/AuthorizedPersonProfileForm";
export default {
name: "AuthorizedPersonForm",
    components: {AuthorizedPersonProfileForm},
    props:['authorizedPersonName'],
    data(){
        return {
            dialog: false
        };
    },

    computed: {
        name() {
            return this.authorizedPersonName;
        }
    },

    methods: {
        openProfileForm() {
            this.dialog = true;
        },
        synData() {

        },
        closeModal()
        {
            this.dialog = false;
        }
    },

    mounted() {
        this.synData();
    }
}
</script>

<style scoped>

</style>
