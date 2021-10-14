<template>
    <div class="crm-text-field" >
        <div class="field-label">
            <span>Authorised Person</span>
        </div>
        <div class="text-field" >
                <v-text-field
                    indentification
                    @click="openProfileForm"
                    outlined
                    v-model="authrity_full_name"
                    dense
                    hide-details="auto"
                    placeholder="Add authorised person"
                ></v-text-field>
        </div>
        <AuthorizedPersonProfileForm @closeModal="closeModal"
                                     @saveAuthroizedPerson = "saveAuthroizedPerson"
                                     v-if="dialog"
                                     dialog="dialog"
                                     :leadId="this.leadId"
                                     :authorized_person_data="authorized_person_data"
        >

        </AuthorizedPersonProfileForm>
    </div>

</template>

<script>
import AuthorizedPersonProfileForm from "@scripts/components/crm/leadmanagement/AuthorizedPersonProfileForm";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import {isNull} from "lodash-es";
export default {
name: "AuthorizedPersonForm",
    components: {AuthorizedPersonProfileForm},
    props:['authorizedPersonName','leadId'],
    data(){
        return {
            dialog: false,
            authorized_person_data: null,
            authrity_full_name : null,
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
            if(this.authorized_person_data?.first_name && this.authorized_person_data?.last_name ) {
                this.authrity_full_name = this.authorized_person_data.first_name +' '+ this.authorized_person_data.last_name;
            }
        },
         async saveAuthroizedPerson(data)
         {
             this.authorized_person_data = await LeadApplicationService.saveAuthorizedPerson(data);
             if(!isNull(this.authorized_person_data)) {
                 this.authrity_full_name = this.authorized_person_data.first_name +' '+ this.authorized_person_data.last_name;
             }
             this.dialog = false;
         }

    },

    async mounted() {
        await this.loadAuthoriedPerson()
    }
}
</script>

<style scoped>

</style>
