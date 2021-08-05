<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search></Search>
            </v-col>
        </v-row>
        <v-card class="pa-4">
            <v-row>
                <v-col cols="12" class="crm-table">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th class="text-left">
                                    Name
                                </th>
                                <th class="text-left">
                                    Moving date
                                </th>
                                <th class="text-left">
                                    Service Type
                                </th>
                                <th class="text-left">
                                    Assignee
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="item in applications"
                                :key="item.id"
                                @click="goToApplicationDetials(item.id)"
                            >
                                <td>{{ item.applicant_name }}</td>
                                <td>{{ item.moving_date }}</td>
                                <td>
                                    <v-icon :disabled="isServiceAllowed(item.service_types, 'power')" color="yellow">mdi-flash</v-icon>
                                    <v-icon :disabled="isServiceAllowed(item.service_types, 'gas')" color="red">mdi-fire</v-icon>
                                    <v-icon :disabled="isServiceAllowed(item.service_types, 'internet')" color="green">mdi-wifi</v-icon>
                                    <v-icon :disabled="isServiceAllowed(item.service_types, 'water')" color="blue" >mdi-water</v-icon>

                                </td>
                                <td>
                                    <AssigneeDropdown :lead="item" :users="users" @reassigning="reassigning"></AssigneeDropdown>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
            <ReassignModal  :dialog="reassignFlag" :user="selectedUser" :lead="selectedLead" @cancelModal="cancelModal"> </ReassignModal>
        </v-card>
    </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import AssigneeDropdown from "@scripts/components/crm/leadmanagement/AssigneeDropdown";
import {omit} from "lodash-es";
import CrmUserService from "@scripts/services/crm/CrmUserService";
import AssignedtoPopUp from "@scripts/components/crm/leadmanagement/AssignedtoPopUp";
import ReassignModal from "@scripts/components/crm/modals/ReassignModal";
export default {
  name: "ApplicantTable",
  components: {
      ReassignModal,
        Search,
        AssigneeDropdown,
        AssignedtoPopUp
    },

    props: {
        applications: {
          required: true
      }
    },

    data() {
        return {
            users: null,
            reassignFlag: false,
            selectedLead: null,
            selectedUser: null,
        }
    },

    methods: {

        async loadUserList() {
           this.users = CrmUserService.loadAllUser();
        },

        isServiceAllowed(services, type) {
            return !services.includes(type);
        },

        goToApplicationDetials(id) {
            let query =omit({...this.$route.query}, 'lead');
            this.$router.push({ query: {lead: id,...query} });
        },

        selectFirstRow() {
            if(this.applications.length > 0)
            {
                this.$router.push({ query: {lead: this.applications[0].id, ...query} });
            }
        },

        reassigning(user, lead)
        {
            this.reassignFlag = true;
            this.selectedUser = user;
            this.selectedLead = lead;
        },
        cancelModal()
        {
            this.reassignFlag = false;
        }
    },
    mounted() {
      this.selectFirstRow();
      this.loadUserList();
    }
};
</script>

<style scoped>
</style>
