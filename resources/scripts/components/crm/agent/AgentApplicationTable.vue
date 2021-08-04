<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search></Search>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn color="primary" @click="addNewApplication"
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
                                    Preference
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
                                @click="openApplicationSummary(item.id)"
                            >
                                <td>{{ item.first_name + ' ' + item.last_name }}</td>
                                <td>{{ item.moving_date }}</td>
                                <td>{{ item.phone }}</td>
                                <td>
                                    <v-icon :disabled="isServiceAllowed(item.service_interests, 'power')" color="yellow">mdi-flash</v-icon>
                                    <v-icon :disabled="isServiceAllowed(item.service_interests, 'gas')" color="red">mdi-fire</v-icon>
                                    <v-icon :disabled="isServiceAllowed(item.service_interests, 'internet')" color="green">mdi-wifi</v-icon>
                                    <v-icon :disabled="isServiceAllowed(item.service_interests, 'water')" color="blue" >mdi-water</v-icon>
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
    name: "AgentApplicationTable",
    props: ["applications"],
    components: {
        Search
    },
    methods: {
        addNewApplication() {
            this.$router.push({name: 'agent.create.application'});
        },
        openApplicationSummary(id) {
            this.$emit("openApplicationSummary", id);
        },
        isServiceAllowed(services, type) {
           return !services.includes(type);
        }
    },
}
</script>

<style scoped>

</style>
