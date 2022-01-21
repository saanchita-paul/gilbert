<template>
    <div class="d-flex justify-space-between">
        <div class="d-flex mt-5 mb-1">
            <div style="flex-basis: 40%;">
                <Search @updateSearch="updateSearch"></Search>
            </div>

            <div class="mx-2 buttonLarge">
                <v-btn
                    :text="dynamicComponent === 'AgentListTable'"
                    @click="changeComponent('lead')"
                    :class="getButtonClass('ApplicatoinListTable')"
                >
                    Performance Operation
                </v-btn>
            </div>

            <div class="buttonLarge">
                <v-btn
                    :text="dynamicComponent === 'ApplicatoinListTable'"
                    @click="changeComponent('user')"
                    :class="getButtonClass('AgentListTable')"
                >
                    Backend of agency
                </v-btn>
            </div>
        </div>
        <div  class="d-flex  mt-5 mb-1" v-if="dynamicComponent === 'AgentListTable'">
            <v-btn
                v-if="selected.length > 0"
                class="mr-4"
                @click="sendInvitationToSelected"
                :loading="isSendingInvitation"
            >
                <v-icon color="primary">mdi-send</v-icon> Send Invite's
            </v-btn>
            <v-btn class="mr-4" @click="setEditMode">
                {{ editMode ? 'Close Edit Mode' : 'Edit Staff Mode' }}
            </v-btn>
            <v-btn color="primary" @click="addNewUser">
                <v-icon left>add</v-icon> Add New Staff
            </v-btn>
        </div>
    </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
export default {
name: "CrmOfficeListHeader",
    components: {Search},
    props: ['editMode', 'selected', 'dynamicComponent', 'isSendingInvitation'],
    methods: {
        updateSearch(text) {
            this.$emit('updateSearch', text);
        },

        changeComponent(name){
            if(this.$route.query.type !== name){
                this.$router.push({name: 'real.state.agency.users',
                    params: {id : this.$route.params.id, office_id : this.$route.params.officeId},
                    query: { type: name}
                })
            }
        },

        getButtonClass(name){
            return this.dynamicComponent === name ? 'buttonActive' : 'buttonInactive';
        },

        setEditMode(){
            this.$emit('changeEditMode');
        },

        addNewUser() {
            this.$emit('addNewUser');
        },

        sendInvitationToSelected() {
            this.$emit('sendInvitationToSelected');
        }
    }
}
</script>

<style scoped>
    .buttonActive{
        background: #DDE2FF !important;
        color:  #542E89 !important;
        cursor: default !important;
    }

    .buttonInactive{
        /* background: #C0C3C4 !important; */
        color: #263238 !important;
    }
</style>
