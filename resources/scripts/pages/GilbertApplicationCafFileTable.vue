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
            show-expand
            class="row-pointer"
            @click:row="onRowSelect"
        >
            <!-- create date start-->
            <template v-slot:item.created_date="{ item }">
                <span>{{ created_date(item.created_date) }}</span>
            </template>
            <!-- create date end-->

            <!-- remove select all checkbox from header start-->
<!--            <template v-slot:[`header.data-table-select`]></template>-->
            <!-- remove select all checkbox from header end-->
            <template v-slot:item.data-table-select="{ item, isSelected, select }">
                <v-simple-checkbox
                    :disabled="getStatus(item)"
                    v-model="item.is_selected"
                    @input="onchangeRow(item)"

                ></v-simple-checkbox>
            </template>

            <!-- row expend start-->
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" style="padding: 0">
                    <GilbertApplicationCafFileDetails @refreshTable="updateTableData" :cafFileData='item' @updateServiceType="updateServiceType"></GilbertApplicationCafFileDetails>
                </td>
            </template>
            <!-- row expend end-->
        </v-data-table>
    </div>
</template>

<script>
import GilbertApplicationCafFileDetails from "@scripts/pages/GilbertApplicationCafFileDetails";
import Pagination from "@scripts/models/crm/Pagination";
import dayJs from "dayjs";
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";

export default {
    name: "GilbertApplicationCafFileTable",
    components: {GilbertApplicationCafFileDetails},
    props: ["value", "cafFiles", "totalItem"],
    data() {
        return {
            selected: [],
            expanded: [],
            selectedRowId: 0,
            disabledCount: 0,
            headers: [
                {text: 'App ID', align: 'start', sortable: true, value: 'id', class: 'black--text'},
                {text: 'Name', align: 'start', sortable: true, value: 'full_name', class: 'black--text'},
                {text: 'Address', align: 'start', sortable: true, value: 'to_address', class: 'black--text'},
                {text: 'Conn Date', align: 'start', sortable: true, value: 'connection_date', class: 'black--text'},
                {text: 'Created Date', align: 'start', sortable: true, value: 'created_date', class: 'black--text'},
                {text: 'Supplier', align: 'start', sortable: true, value: 'supplier', class: 'black--text'},
                {text: 'Plan', align: 'start', sortable: true, value: 'plan', class: 'black--text'},
                {text: 'Status', align: 'start', sortable: true, value: 'status', class: 'black--text'},
                {text: '', value: 'data-table-expand', sortable: false, align: 'start'},
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
            console.log('data', data);
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
        onRowSelect(item, slot) {
            this.selectedRowId = item.id;
            slot.expand(!slot.isExpanded)
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
                // search: this.cafFileSearch,
                page: this.options.page,
                per_page: this.options.itemsPerPage === -1 ? this.totalItem : this.options.itemsPerPage,
                // is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                // sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
            }
            this.$emit('refreshDataTable', meta);
        },


        isDisabled(item) {

            console.log('updated item', item);
            return false;
        },

        // checkedCafKey(service_type, services){
        //     let key = ApplicationCafFileService.isPossibleToCreateCaf(service_type, services);
        //     return key;
        // }
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
