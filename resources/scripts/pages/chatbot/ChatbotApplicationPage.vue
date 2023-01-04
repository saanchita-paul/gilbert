<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <h2>Chatbot Application</h2>
            </v-col>

            <v-col cols="8">
                <v-card>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <h3>Filters</h3>
                                <ChatbotApplicationFilter
                                    :selected="selectedCaf"
                                    v-model="advanceSearch"
                                    :cafFiles="cafFiles"
                                    :isSearchEmpty="advanceSearch.isSearchEmpty()"
                                    listPage="true"
                                    @updateDate="updateDate"
                                    @updateApplicationTypeFilter = "updateApplicationTypeFilter"
                                    @updateProviderNameFilter = "updateProviderNameFilter"
                                >
                                </ChatbotApplicationFilter>
                            </v-col>
                            <v-col cols="12">
                                <ChatbotCafTable
                                    v-model="selectedCaf"
                                    :cafFiles="chatbotApps"
                                    :totalItem="totalItem"
                                    @updateDataTable="updateDataTable"
                                    @refreshDataTable="refreshDataTable"
                                    @updateServiceType="updateServiceType"
                                    @updateSelectedMovingData="updateSelectedMovingData"
                                    @selectRowCafFile="selectRowCafFile"
                                    listPage="true"
                                >
                                </ChatbotCafTable>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4" v-if="chatbotApps.length">
                <ChatbotApplicationDetails  ></ChatbotApplicationDetails>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import GilbertApplicationCafFileFilter from '@scripts/pages/GilbertApplicationCafFileFilter';
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
import ApplicationCafFileTable from "@scripts/pages/ApplicationCafFileTable";
import GilbertApplicationCafFileTable from "@scripts/pages/GilbertApplicationCafFileTable";
import {CafFileSearchFilterModel} from "@scripts/models/CafFileSearchFilterModel";
import {forEach, isEqual, isNull, omit} from "lodash-es";
import ChatbotApplicationDetails from "@scripts/components/chatbot/ChatbotApplicationDetails";
import ChatbotApplicationFilter from "@scripts/components/chatbot/ChatbotApplicationFilter";
import ChatbotCafTable from "@scripts/components/chatbot/ChatbotCafTable";
import Store from '@scripts/store/index';

export default {
name: "ChatbotApplicationPage",
    components: {
        ChatbotApplicationFilter,
        ApplicationCafFileTable,
        ApplicationCafFileFilter,
        ChatbotApplicationDetails,
        ChatbotCafTable
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
            selectedApp: null
        }
    },

    watch: {
        advanceSearch: {
            handler(value) {
                let params = {...this.$route.query, ...value}
                if (isEqual(this.$route.query, value)) return;
                if(params.is_gilbert && params.is_gilbert === 'chatbot'){
                    delete params.is_gilbert
                }else{
                    params.is_gilbert = 'true'
                }
                this.$router.push({
                    name: "chatbot.application",
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
                    name: "chatbot.application",
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
            const query = {...this.$route.query, app_id: item.id};
            this.$router.replace({ query: {...query} });
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
            this.selectedApp = this.cafFiles[id];
            let index = this.cafFiles.findIndex((dt)=> {
                return dt.id === id;
            });

            if(index !== -1) {
                this.cafFiles[index].selected_service = service_type;
            }
        },

        async fetchCafFiles() {
            let data = await ApplicationCafFileService.getChatbotApplication({...this.sort_search_meta, ...{page: this.page}}, this.advanceSearch);
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            if(data.data.length > 0) {
                const query = this.$route.query;
                await this.$router.replace({query: {...query, app_id: data.data[0].id}});
            }
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

        updateApplicationTypeFilter(applicationType){
            this.advanceSearch.is_gilbert = applicationType
        },
        updateProviderNameFilter(provider_name){
            this.advanceSearch.provider_name = provider_name
        }

    },
    computed: {
        chatbotApps() {

            console.log('chatbot application updated', );
           return Store.getters.applications;
        }
    }

}
</script>

<style scoped>
.v-tab {
    text-transform: capitalize;
    font-weight: bold;
}
</style>
