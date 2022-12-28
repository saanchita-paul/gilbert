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

            <!-- row expend start-->
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" style="padding: 0">
                    <ApplicationCafFileDetails @refreshTable="updateTableData" :cafFileData='item' @updateServiceType="updateServiceType"></ApplicationCafFileDetails>
                </td>
            </template>
            <!-- row expend end-->
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
                {text: 'Name', align: 'start', sortable: true, value: 'full_name', class: 'black--text', shouldShow: true},
                {text: 'Address', align: 'start', sortable: true, value: 'to_address', class: 'black--text', shouldShow: true},
                {text: 'Supplier', align: 'start', sortable: true, value: 'supplier', class: 'black--text', shouldShow: true},
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
        }
    },
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    /*cursor: pointer;*/
}

</style>
