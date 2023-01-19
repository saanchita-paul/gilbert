<template>
    <div>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="cafFiles"
            :server-items-length="totalItem"
            :options.sync="options"
            :single-select=false
            :expanded.sync="expanded"
            :item-class="selectedRowToHighlight"
            item-key="id"
            show-select
            class="row-pointer"
            @click:row="onRowSelect"
        >
            <template v-slot:item.full_name="{item}">
                <div class="d-flex align-center"  style="font-size: 12px!important;">
                    <span > {{ truncateLongText(item.full_name) }}</span>
                </div>
            </template>

            <template v-slot:item.to_address="{item}">
                <div class="d-flex align-center"  style="font-size: 12px!important;">
                    <span > {{ truncateLongText (item.to_address) }}</span>
                </div>
            </template>


            <template v-slot:item.caf_generation_status="{item}">
                <div class="d-flex align-center"  style="font-size: 12px!important;">
                    <span class="service-status"> {{item.caf_generation_status}}</span>
                </div>
            </template>

            <template v-slot:item.services="{ item}">
                <div class="d-flex pa-2" style="gap : 8px;">
                    <div class="d-flex justify-center align-center">
                        <v-img v-if="item.service_provider === 'ea'"   src="/assets/images/logo/providers/ea_small.png" />
                        <v-img v-if="item.service_provider === 'origin'"  src="/assets/images/logo/providers/origin_small.png" />
                        <v-img v-if="item.service_provider === 'powershop'"  src="/assets/images/logo/providers/powershop_small.png" />
                    </div>
                    <div>
                        <div class="d-flex  ">
                            <v-icon :disabled="isServiceAllowed(item.services, 'electricity')" color="yellow">mdi-flash</v-icon>
                            <span :class="isSelectedClass(item)">{{getServiceStatus(item.services, 'electricity')}}</span>
                        </div>
                        <div class="d-flex align-center">
                            <v-icon :disabled="isServiceAllowed(item.services, 'gas')" color="red">mdi-fire</v-icon>
                            <span :class="isSelectedClass(item)"> {{getServiceStatus(item.services, 'gas')}}</span>
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
import {isEqual, isNull} from "lodash-es";

export default {
    name: "ChatbotCafTable",
    components: {ApplicationCafFileDetails},
    props: ["value", "cafFiles", "totalItem"],
    data() {
        return {
            selected: [],
            expanded: [],
            selectedRowId: 0,
            disabledCount: 0,
            headers: [
                {text: 'App ID', align: 'start', sortable: false, value: 'id', class: 'black--text', shouldShow: true },
                {text: 'Tenant Name', align: 'start', sortable: false, value: 'full_name', class: 'black--text', shouldShow: true},
                {text: 'Address', align: 'start', sortable: false, value: 'to_address', class: 'black--text', shouldShow: true},
                {text: 'Connection Date', align: 'start', sortable: false, value: 'connection_date', class: 'black--text', shouldShow: true},
                {text: 'Created Date', align: 'start', sortable: false, value: 'created_date', class: 'black--text', shouldShow: true},
                {text: 'Services', align: 'start', sortable: false, value: 'services', class: 'black--text', shouldShow: true},
                {text: 'Status', align: 'start', sortable: false, value: 'caf_generation_status', class: 'black--text', shouldShow: true},
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

        onRowSelect(item, row) {
            let params = { ...this.$route.query, app_id: item.id}
            this.$router.replace({ query: {...params} });
            // row.select(true);
        },
        isSelectedClass(item) {
            if (item.color === true) {
                return 'red--text service-status';
            }
             return 'service-status';

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
            return false;
        },

        isServiceAllowed(services, type) {
            const pp =  services.find((dt) => dt.service_type === type);
            return !pp;

        },
        getServiceStatus(services, type) {
            let selectedService =  services.find((dt) => dt.service_type === type);

            if(selectedService && !isNull(selectedService.status)) {
                return this.mapServiceStatus(selectedService.status);
            }
            return '--';
        },

        mapServiceStatus(status) {
            switch (status) {
                case 'Manual_Processing' :
                    return 'Manual Processing';
                default :
                    return status;
            }
        },

        truncateLongText(address){
            return address.substring(0,20)+"...";
        },

        selectedRowToHighlight(item) {
           return item.id == this.$route.query.app_id ? 'highlight-selected' : '';
        }
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

.row-pointer >>> tbody tr td{
   font-size: 12px !important;
}
.row-pointer >>> thead tr th{
   white-space: nowrap;
}

.row-pointer >>> tbody tr td:nth-child(1) {
    padding: 0px 0px  0px 16px !important;
}
.row-pointer >>> tbody tr td:not(:nth-child(1)) {
    padding: 0px 0px  0px 0px !important;
}

.row-pointer >>> thead tr th:not(:nth-child(1)) {
    padding: 0px 8px  0px 0px !important;
}



</style>
