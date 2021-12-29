<template>
    <div class="d-flex justify-space-between">
        <div class="d-flex my-2">
            <div style="flex-basis: 40%;">
                <Search @updateSearch="updateSearch"></Search>
            </div>

            <div class="mx-2 buttonLarge">
                <v-btn @click="changeComponent('ApplicatoinListTable')" :class="getButtonClass('ApplicatoinListTable')"> Performance Operation </v-btn>
            </div>

            <div class="mx-2 buttonLarge">
                <v-btn @click="changeComponent('AgentListTable')" :class="getButtonClass('AgentListTable')"> Backend of agency </v-btn>
            </div>
        </div>
        <div  class="d-flex my-2" v-if="dynamicComponent === 'AgentListTable'">
            <v-btn v-if="selected.length > 0" class="mr-4">
                <v-icon color="primary">mdi-send</v-icon> Invite Selected
            </v-btn>
            <v-btn class="mr-4" @click="setEditMode">
                {{ editMode ? 'Cancel Edit' : 'Edit Staff' }}
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
    props: ['editMode', 'selected', 'dynamicComponent'],
    methods: {
        updateSearch() {

        },

        changeComponent(name){
            this.$emit('changeComponent', name);
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
    }
}
</script>

<style scoped>
.buttonActive{
    background: #DDE2FF;
    color:  #542E89;
}
</style>
