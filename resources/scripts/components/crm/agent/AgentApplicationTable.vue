<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search></Search>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn color="primary" @click="addAgency"
                ><v-icon left>add
                </v-icon> Add New Application
                </v-btn>
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
                                    Mobile
                                </th>
                                <th class="text-left">
                                    Service Type
                                </th>
                                <th class="text-left">
                                    Status
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
                                <td>{{ item.phone }}</td>
                                <td>
                                    <v-icon color="yellow">mdi-flash</v-icon>
                                    <v-icon color="red">mdi-fire</v-icon>
                                    <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                                    <v-icon color="grey lighten-1">mdi-water</v-icon>
                                </td>
                                <td>{{ item.status }}</td>
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

export default {
name: "AgencyApplicationTable",
    components: {
        Search
    },
    data(){
        return {
            applications : [
                {
                    id: 1,
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    phone: '0410758782',
                    service_types: 30,
                    status: 'Not Confirmed Connection',
                },
                {
                    id: 2,
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    phone: '0410758782',
                    service_types: 30,
                    status: 'Not Confirmed Connection',
                },
                {
                    id: 3,
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    phone: '0410758782',
                    service_types: 30,
                    status: 'Not Confirmed Connection',
                },
                {
                    id: 4,
                    application_name: 'Applicant Name goes here',
                    moving_date: '06/08/2021',
                    phone: '0410758782',
                    service_types: 30,
                    status: 'Not Confirmed Connection',
                },
            ]

        }
    },
    methods: {

    },
    mounted() {

    }
}
</script>

<style scoped>

</style>
