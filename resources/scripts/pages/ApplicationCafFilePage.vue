<template>
    <v-container fluid>

        <v-tabs v-model="activeTab">
            <!--  Chatbot Application start-->
<!--            <v-tab href="#chatbotApplication">-->
<!--                <v-icon left>mdi-facebook-messenger</v-icon>-->
<!--                Chatbot Applications-->
<!--            </v-tab>-->
<!--            <v-tab-item value="chatbotApplication">-->
<!--                <v-card>-->
<!--                    <v-card-text>-->
<!--                        <v-row>-->
<!--                            <v-col cols="12">-->
<!--                                <h3>Filters</h3>-->
<!--                                <ApplicationCafFileFilter :selected="selectedCaf"-->
<!--                                                          v-model="advanceSearch"-->
<!--                                                          :cafFiles="cafFiles"-->
<!--                                                          :isSearchEmpty="advanceSearch.isSearchEmpty()"-->
<!--                                                          @updateDate="updateDate"></ApplicationCafFileFilter>-->
<!--                            </v-col>-->
<!--                            <v-col cols="12">-->
<!--                                <ApplicationCafFileTable-->
<!--                                    v-model="selectedCaf"-->
<!--                                    :cafFiles="cafFiles"-->
<!--                                    :totalItem="totalItem"-->
<!--                                    @updateDataTable="updateDataTable"-->
<!--                                    @refreshDataTable="refreshDataTable"-->
<!--                                    @updateServiceType="updateServiceType"-->
<!--                                    @updateSelectedMovingData="updateSelectedMovingData"-->
<!--                                    @selectRowCafFile="selectRowCafFile"-->
<!--                                >-->

<!--                                </ApplicationCafFileTable>-->
<!--                            </v-col>-->
<!--                        </v-row>-->
<!--                    </v-card-text>-->
<!--                </v-card>-->
<!--            </v-tab-item>-->
            <!--  Chatbot Application end-->


            <!--  Chatbot Application start-->
            <v-tab href="#chatbotApplication1">
                <v-icon left>mdi-facebook-messenger</v-icon>
                Chatbot Applications
            </v-tab>
            <v-tab-item value="chatbotApplication1">
              <ChatbotApplicationPage></ChatbotApplicationPage>
            </v-tab-item>
            <!--  Chatbot Application end-->


            <!--  Gilbert Application start-->
            <v-tab href="#gilbertApplication">
                <v-icon left>mdi-message-text</v-icon>
                Gilbert Applications
            </v-tab>
            <v-tab-item value="gilbertApplication">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h3>Filters</h3>
                                <GilbertApplicationCafFileFilter
                                    :selectedCafFile="selectedCafFile"
                                    v-model="advanceSearchModel"
                                    :gilbertApplications="gilbertApplications"
                                    :isSearchEmpty="advanceSearchModel.isSearchEmpty()"
                                    @updatePageOnFilterChange="updatePageOnFilterChange"
                                    @updateDates="updateDates">
                                </GilbertApplicationCafFileFilter>
                            </v-col>
                            <v-col cols="12">
                                <GilbertApplicationCafFileTable
                                    v-model="selectedCafFile"
                                    :gilbertApplications="gilbertApplications"
                                    :totalItems="totalItems"
                                    :pages="pages"
                                    @reloadDataTable="reloadDataTable"
                                    @selectRowCafFiles="selectRowCafFiles"
                                >
                                </GilbertApplicationCafFileTable>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <!--  Gilbert Application end-->
        </v-tabs>

    </v-container>
</template>

<script>
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import GilbertApplicationCafFileFilter from '@scripts/pages/GilbertApplicationCafFileFilter';
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
import ApplicationCafFileTable from "@scripts/pages/ApplicationCafFileTable";
import GilbertApplicationCafFileTable from "@scripts/pages/GilbertApplicationCafFileTable";
import {CafFileSearchFilterModel} from "@scripts/models/CafFileSearchFilterModel";
import ChatbotApplicationPage from "@scripts/pages/chatbot/ChatbotApplicationPage";
import {forEach, isEqual, isNull, omit} from "lodash-es";

export default {
    name: "ApplicationCafFilePage",
    components: {
        ApplicationCafFileTable,
        ApplicationCafFileFilter,
        GilbertApplicationCafFileTable,
        GilbertApplicationCafFileFilter,
        ChatbotApplicationPage
    },

    data() {
        return {

            selectedMovingData: [],
            selectedCaf: [],
            selectedCafFile: [],
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
            gilbertApplications: [],
            sorts_search_meta: null,
            pages: 1,
            pageCounts: 0,
            itemsPerPages: 10,
            totalItems: null,
            option: {},
            advanceSearchModel: new CafFileSearchFilterModel(),
        }
    },

    computed: {
        activeTab: {
            set(tab) {
                this.$router.replace({ query: { ...this.$route.query, tab }})
            },
            get() {
                return this.$route.query.tab;
            }
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
        },
        advanceSearchModel: {
            handler(value) {
                let params = {...this.$route.query, ...value}
                if (isEqual(this.$route.query, value)) return;
                this.$router.push({
                    name: "caf.files",
                    query: params,
                });
                this.resetPage();
                this.fetchGilbertApplications();
            },
            deep: true
        },
    },

    async mounted() {
        // await this.fetchGilbertApplications();
    },


    methods: {
        updateDataTable(data)
        {

            let index = this.cafFiles.findIndex((dt)=> {
                return dt.id === data.id;
            });


            if(index !== -1) {


                this.cafFiles[index].full_name = data.full_name;
                this.cafFiles[index].business_name = data.business_name;
                this.cafFiles[index].abn = data.abn;
                this.cafFiles[index].first_name = data.first_name;
                this.cafFiles[index].last_name = data.last_name;
                this.cafFiles[index].middle_name = data.middle_name;
                this.cafFiles[index].nmi = data.nmi;
                this.cafFiles[index].mirn = data.mirn;
                this.cafFiles[index].title = data.title;

                if(!isNull(data.connection_date)) {
                    this.cafFiles[index].connection_date = data.connection_date;
                }
                if(!isNull(data.plan)) {
                    this.cafFiles[index].plan = data.plan;
                }



            }
        },

        selectRowCafFile(item)
        {
            let index = this.selectedCaf.findIndex(dt => dt.id === item.id);
            if(index === -1) {
                this.selectedCaf.push(item);
            } else {
                this.selectedCaf.splice(index, 1);
            }


        },

        selectRowCafFiles(item) {
            let index = this.selectedCafFile.findIndex(dt => dt.id === item.id);
            if(index === -1) {
                this.selectedCafFile.push(item);
            } else {
                this.selectedCafFile.splice(index, 1);
            }
        },

        updateSelectedMovingData(id, service_type)
        {
            let index = this.selectedMovingData.findIndex(dt => dt.id === id);
            if(index !== -1) {
                this.selectedMovingData.splice(index, 1)
            } else {
                this.selectedMovingData({id:id, service_type: service_type});
            }
        },

        updateServiceType(service_type, id) {
            let index = this.cafFiles.findIndex((dt)=> {
                return dt.id === id;
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

        async fetchGilbertApplications() {
            let data = await ApplicationCafFileService.getGilbertApplicationData({...this.sorts_search_meta, ...{page: this.pages}}, this.advanceSearchModel);
            this.gilbertApplications = data.data;
            this.pages = data.pagination.current_page;
            this.itemsPerPages = data.pagination.per_page;
            this.totalItems = data.pagination.total;
        },

        resetPage() {
            this.page = 1;
        },
        refreshDataTable(meta) {
            this.page = meta.page
            this.sort_search_meta = omit({...meta}, 'page');
            this.fetchCafFiles();
        },

        reloadDataTable(meta) {
            this.pages = meta.page;
            this.sorts_search_meta = omit({...meta}, 'page');
            this.fetchGilbertApplications();
        },

        updateDate(dateRange) {
            if (dateRange) {
                this.advanceSearch.start_date = dateRange.start
                this.advanceSearch.end_date = dateRange.end
            }

        },

        updateDates(dateRange) {
            if (dateRange) {
                this.advanceSearchModel.start_date = dateRange.start
                this.advanceSearchModel.end_date = dateRange.end
            }

        },
        updatePageOnFilterChange() {
            this.pages = 1;
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

