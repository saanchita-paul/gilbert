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
        <AuthorizedPersonProfileForm @closeModal="closeModal"
                                     @saveAuthroizedPerson = "saveAuthroizedPerson"
                                     v-if="dialog"
                                     dialog="dialog"
                                     :authorized_person_data="authorized_person_data"
        >

        </AuthorizedPersonProfileForm>
    </div>

</template>

<script>
import AuthorizedPersonProfileForm from "@scripts/components/crm/leadmanagement/AuthorizedPersonProfileForm";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
export default {
name: "AuthorizedPersonForm",
    components: {AuthorizedPersonProfileForm},
    props:['authorizedPersonName','leadId'],
    data(){
        return {
            dialog: false,
            authorized_person_data: null,
        };
    },

    computed: {
        name() {
            return this.authorizedPersonName;
        }
    },

    methods: {
        async openProfileForm() {
            await this.loadAuthoriedPerson();
            this.dialog = true;
        },

        closeModal()
        {
            this.dialog = false;
        },

        async loadAuthoriedPerson()
        {
            this.authorized_person_data = await LeadApplicationService.loadAuthorizedPerson(this.leadId);
        },
         async saveAuthroizedPerson(data)
         {
             console.log(data);
             await LeadApplicationService.saveAuthorizedPerson(data);
         }

    },

    async mounted() {
        await this.loadAuthoriedPerson()
    }
}
</script>

<style scoped>

</style>
