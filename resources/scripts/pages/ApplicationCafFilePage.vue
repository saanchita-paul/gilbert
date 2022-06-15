<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <v-card class="hood-card">
                    <h3 class="page-title">Chatbot Applications & CAF Files</h3>
                </v-card>
            </v-col>
        </v-row>


        <v-row>
            <v-col>
                <h5>Filters</h5>
                <ApplicationCafFileFilter></ApplicationCafFileFilter>

                <ApplicationCafFileTable></ApplicationCafFileTable>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import ApplicationCafFileTable from '@scripts/pages/ApplicationCafFileTable';
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";

export default {
    name: "ApplicationCafFilePage",
    components: {
        ApplicationCafFileFilter,
        ApplicationCafFileTable
    },

    data() {
        return {
        }
    },

    methods: {
        async getCafFiles () {
            let data = await ApplicationCafFileService.getApplicationCafFileData();
            console.log(data);

            data.forEach(dt=> {
                console.log(dt);
            })
        },
    },

    mounted() {
        this.getCafFiles();
    },
}
</script>

<style scoped>
</style>

