<template>
    <v-container fluid>

        <v-tabs>
            <v-tab href="#chatbotApplication">
                <v-icon left>mdi-facebook-messenger</v-icon>
                Chatbot Applications
            </v-tab>

            <!--  Chatbot Application start-->
            <v-tab-item value="chatbotApplication">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h3>Filters</h3>
                                <ApplicationCafFileFilter :selected="selectedCaf"></ApplicationCafFileFilter>
                            </v-col>
                            <v-col cols="12">
                                <ApplicationCafFileTable v-model="selectedCaf" :cafFiles="cafFiles"></ApplicationCafFileTable>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <!--  Chatbot Application end-->

            <!--  Gilbert Application start-->
            <v-tab href="#gilbertApplication" disabled>
                <v-icon left>mdi-message-text</v-icon>
                Gilbert Applications
            </v-tab>
            <v-tab-item value="gilbertApplication">
                Gilbert Application Details
            </v-tab-item>
            <!--  Gilbert Application end-->
        </v-tabs>
    </v-container>
</template>

<script>
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
import ApplicationCafFileTable from "@scripts/pages/ApplicationCafFileTable";
import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";

export default {
    name: "ApplicationCafFilePage",
    components: {
        ApplicationCafFileTable,
        ApplicationCafFileFilter,
    },

    data() {
        return {
            selectedCaf: [],
            tab: null,
            cafFiles: [],
        }
    },

    async mounted() {
        await this.getCafFiles();
    },

    methods: {
        async getCafFiles() {
            this.cafFiles = await ApplicationCafFileService.getApplicationCafFileData();
            console.log('Inside page', this.cafFiles);
        },
    },
}
</script>

<style scoped>
.v-tab{
    text-transform: capitalize;
    font-weight: bold;
}
</style>

