<template>
    <div>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="cafFiles"
            :server-items-length="totalItem"
            :options.sync="options"
            :single-expand=true
            :expanded.sync="expanded"
            :item-class="isSelectedClass"
            item-key="id"
            :show-expand="!listPage"
            class="row-pointer"
            @click:row="onRowSelect"
        >
            <template v-slot:item.data-table-select="{ item, isSelected, select }">
                <v-simple-checkbox
                    :ripple="false"
                    :disabled="getStatus(item)"
                    v-model="item.is_selected"
                    @input="onchangeRow(item)"
                ></v-simple-checkbox>
            </template>

            <template v-slot:item.services="{ item}">
                <div class="d-flex pa-2" style="gap : 8px">
                    <div class="d-flex justify-center align-center">
                        <v-img v-if="item.service_provider === 'ea'"   src="/assets/images/logo/providers/ea_small.png" />
                        <v-img v-if="item.service_provider === 'origin'"  src="/assets/images/logo/providers/origin_small.png" />
                        <v-img v-if="item.service_provider === 'powershop'"  src="/assets/images/logo/providers/powershop_small.png" />
                    </div>
                    <div>
                        <div class="d-flex  ">
                            <v-icon :disabled="isServiceAllowed(item.services, 'electricity')" color="yellow">mdi-flash</v-icon>
                            <span class="service-status">{{getServiceStatus(item.services, 'electricity')}}</span>
                        </div>
                        <div class="d-flex align-center">
                            <v-icon :disabled="isServiceAllowed(item.services, 'gas')" color="red">mdi-fire</v-icon>
                            <span class="service-status"> {{getServiceStatus(item.services, 'gas')}}</span>
                        </div>
                    </div>
                </div>
            </template>

        </v-data-table>
    </div>
</template>

<script>
import ApplicationCafFileDetails from "@scripts/pages/ApplicationCafFileDetails";
import Pagination from "@scripts/models/crm/Pagination";
import dayJs from "dayjs";
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
import {isEqual} from "lodash-es";

export default {
    name: "ChatbotCafTable",
    components: {ApplicationCafFileDetails},
    props: ["value", "cafFiles", "totalItem", "listPage"],
    data() {
        return {
            selected: [],
            expanded: [],
            selectedRowId: 0,
            disabledCount: 0,
            headers: [
                {text: 'App ID', align: 'start', sortable: true, value: 'id', class: 'black--text', shouldShow: true },
                {text: 'Tenant Name', align: 'start', sortable: true, value: 'full_name', class: 'black--text', shouldShow: true},
                {text: 'Address', align: 'start', sortable: true, value: 'to_address', class: 'black--text', shouldShow: true},
                {text: 'Connection Date', align: 'start', sortable: true, value: 'connection_date', class: 'black--text', shouldShow: true},
                {text: 'Created Date', align: 'start', sortable: true, value: 'created_date', class: 'black--text', shouldShow: true},
                {text: 'Services', align: 'start', sortable: true, value: 'services', class: 'black--text', shouldShow: true},
                {text: 'Status', align: 'start', sortable: true, value: 'is_caf_file_generated', class: 'black--text', shouldShow: true},
                {text: '', value: 'data-table-select', sortable: false}
            ],
            cafFileSearch: '',
            options: {
                itemsPerPage: 10
            },
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
        }
    },

    watch: {
        selected(val) {
            this.$emit('input', val)
        },
        options: {
            handler () {
                this.loadCafFileList();
            },
            deep: true,
        },
    },

    methods: {


        updateTableData(data)
        {
            this.$emit('updateDataTable', data);
        },

        onchangeRow(item)
        {
            this.$emit('selectRowCafFile', item);
        },

        updateSelectedService(item)
        {
            let moving_id = item.id;
            let services = item.services;
            let selectedServiceType = '';
            services.forEach(svc => {
                if(svc.is_active) {
                    selectedServiceType = svc.service_type;
                }
            });

            this.updateSelectedMovingData(moving_id, selectedServiceType);
        },

        updateServiceType(data, id) {
            this.$emit('updateServiceType', data, id);
        },
        onRowSelect(item) {
            let params = { ...this.$route.query, app_id: item.id}
            this.$router.replace({ query: {...params} });
        },
        isSelectedClass(item) {
            if (item.id === this.selectedRowId) {
                return 'selectedRowForAgentTable';
            }
        },

        created_date(date) {
            return dayJs(date,'YYYY-MM-DD').format('DD/MM/YYYY');
        },

        loadCafFileList() {
            const meta = {
                page: this.options.page,
                per_page: this.options.itemsPerPage === -1 ? this.totalItem : this.options.itemsPerPage,
            }
            this.$emit('refreshDataTable', meta);
        },


        isDisabled(item) {

            console.log('updated item', item);
            return false;
        },

        updateSelectedMovingData(moving_id, selectedServiceType) {

            this.$emit('updateSelectedMovingData', moving_id, selectedServiceType);
        },

        getStatus(item) {
            return !item.is_possible_caf_file;
        },

        isServiceAllowed(services, type) {
            const pp =  services.find((dt) => dt.service_type === type);
            return !pp;

        },
        getServiceStatus(services, type) {
            let selectedService =  services.find((dt) => dt.service_type === type);
            if(selectedService) {
                return selectedService.status;
            }
            return '--';
        },
    },
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    /*cursor: pointer;*/
}
.row-pointer >>> tbody tr td:nth-child(3){
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 203px !important;
}
.service-status{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 82px !important;
}
</style>
