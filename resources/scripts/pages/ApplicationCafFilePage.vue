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
                                <ApplicationCafFileFilter :selected="selectedCaf" v-model="advanceSearch" :isSearchEmpty="advanceSearch.isSearchEmpty()" @updateDate="updateDate"></ApplicationCafFileFilter>
                            </v-col>
                            <v-col cols="12">
                                <ApplicationCafFileTable
                                    v-model="selectedCaf"
                                    :cafFiles="cafFiles"
                                    :totalItem="totalItem"
                                    @refreshDataTable="refreshDataTable"
                                    @updateServiceType="updateServiceType"
                                >

                                </ApplicationCafFileTable>
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
import {CafFileSearchFilterModel} from "@scripts/models/CafFileSearchFilterModel";
import {isEqual, omit} from "lodash-es";

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

            sort_search_meta: null,
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            options: {},
            advanceSearch: new CafFileSearchFilterModel(),
            dateRange: null,
        }
    },

    watch: {
        advanceSearch: {
            handler(value) {
                let params = {...this.$route.query, ...value}
                if (isEqual(this.$route.query, value)) return;
                this.$router.push({
                    name: "caf.files",
                    query: params,
                });
                this.resetPage();
                this.fetchCafFiles();
            },
            deep: true
        }
    },

    mounted() {
        this.advanceSearch = new CafFileSearchFilterModel({...this.$route.query});
        this.fetchCafFiles();
    },


    methods: {

        updateServiceType(service_type, id) {
            let index = this.cafFiles.findIndex((dt)=> {
                return dt.id = id;
            });
            if(index !== -1) {
                this.cafFiles[index].selected_service = service_type;
            }
        },

        async fetchCafFiles() {
            let data = await ApplicationCafFileService.getApplicationCafFileData({...this.sort_search_meta, ...{page: this.page}}, this.advanceSearch);
            this.cafFiles = data.data;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
        },

        resetPage() {
            this.page = 1;
        },
        refreshDataTable(meta) {
            this.page = meta.page
            this.sort_search_meta = omit({...meta}, 'page');
            this.fetchCafFiles();
        },

        updateDate(dateRange) {
            this.dateRange = dateRange;
            this.fetchCafFiles();
        },
    },
}
</script>

<style scoped>
.v-tab {
    text-transform: capitalize;
    font-weight: bold;
}
</style>

