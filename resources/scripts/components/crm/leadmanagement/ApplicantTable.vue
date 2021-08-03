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
                            >
                                <td>{{ item.application_name }}</td>
                                <td>{{ item.moving_date }}</td>
                                <td>
                                    <v-icon color="yellow">mdi-flash</v-icon>
                                    <v-icon color="red">mdi-fire</v-icon>
                                </td>
                                <td>
                                    <AssigneeDropdown></AssigneeDropdown>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import AssigneeDropdown from "@scripts/components/crm/leadmanagement/AssigneeDropdown";
export default {
  name: "ApplicantTable",
  components: {
        Search,
        AssigneeDropdown
    },
    data(){
        return {
            applications : [
                {
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    service_types: 30,
                    assignee: 'Not Confirmed Connection',
                },
                {
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    service_types: 30,
                    assignee: 'Not Confirmed Connection',
                },
                {
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    service_types: 30,
                    assignee: 'Not Confirmed Connection',
                },
                
            ]

        }
    },
};
</script>

<style scoped>
</style>
