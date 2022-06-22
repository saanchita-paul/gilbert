<template>
    <div>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="cafFiles"
            :page.sync="pagination.current_page"
            :items-per-page="pagination.per_page"
            :server-items-length="pagination.total"
            :single-expand=true
            :expanded.sync="expanded"
            :item-class="isSelectedClass"
            item-key="id"
            show-select
            show-expand
            class="row-pointer"
            @click:row="onRowSelect"
        >
            <!-- create date start-->
            <template v-slot:item.created_date="{ item }">
                <span>{{ created_date(item.created_date) }}</span>
            </template>
            <!-- create date end-->
            <!-- connection date start-->
            <template v-slot:item.service.connection_date="{ item }">
                <span>{{ connection_date(item.service.connection_date) }}</span>
            </template>
            <!-- connection date end-->
            <!-- remove select all checkbox from header start-->
            <template v-slot:[`header.data-table-select`]></template>
            <!-- remove select all checkbox from header end-->
            <!-- row expend start-->
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" style="padding: 0">
                    <ApplicationCafFileDetails :cafFileData='item'></ApplicationCafFileDetails>
                </td>
            </template>
            <!-- row expend end-->
        </v-data-table>
    </div>
</template>

<script>
import ApplicationCafFileDetails from "@scripts/pages/ApplicationCafFileDetails";
import Pagination from "@scripts/models/crm/Pagination";
import {merge} from "lodash-es";
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
import dayJs from "dayjs";

export default {
    name: "ApplicationCafFileTable",
    components: {ApplicationCafFileDetails},
    props: ["value"],
    data() {
        return {
            selected: [],
            expanded: [],
            selectedRowId: 0,
            headers: [
                {text: 'App ID', align: 'start', sortable: true, value: 'id', class: 'black--text'},
                {text: 'Name', align: 'start', sortable: true, value: 'full_name', class: 'black--text'},
                {text: 'Address', align: 'start', sortable: true, value: 'to_address', class: 'black--text'},
                {text: 'Conn Date', align: 'start', sortable: true, value: 'service.connection_date', class: 'black--text'},
                {text: 'Created Date', align: 'start', sortable: true, value: 'created_date', class: 'black--text'},
                {text: 'Supplier', align: 'start', sortable: true, value: 'supplier', class: 'black--text'},
                {text: 'Plan', align: 'start', sortable: true, value: 'plan', class: 'black--text'},
                {text: 'Business Name', align: 'start', sortable: true, value: 'business_name', class: 'black--text'},
                {text: 'ABN', align: 'start', sortable: true, value: 'abn', class: 'black--text'},
                {text: 'Status', align: 'start', sortable: true, value: 'status', class: 'black--text'},
                {text: '', value: 'data-table-expand', sortable: false, align: 'start'},
                {text: '', value: 'data-table-select', sortable: false}
            ],
            cafFiles: [],
            pagination: new Pagination(),
            current_page: 1,
            total: 0
        }
    },
    computed: {

    },
    watch: {
        selected(val) {
            this.$emit('input', val)
        },

        'pagination.current_page'(pageNew, pageOld) {
            if (pageNew !== pageOld) {
                this.load(pageNew)
            }
        }
    },

    async mounted() {
        await this.load(this.pagination.current_page);
    },
    methods: {
        onRowSelect(item, slot) {
            this.selectedRowId = item.id;
            slot.expand(!slot.isExpanded)
        },
        isSelectedClass(item) {
            if (item.id === this.selectedRowId) {
                return 'selectedRowForAgentTable';
            }
        },
        async load(page) {
            let response = await ApplicationCafFileService.getApplicationCafFileData(page);
            merge(this.pagination, response.pagination)
            this.cafFiles = response.data;
        },
        created_date(date) {
            return dayJs(date,'YYYY-MM-DD').format('MM/DD/YYYY');
        },
        connection_date(date) {
            return dayJs(date,'YYYY-MM-DD').format('MM/DD/YYYY');
        }
    },

}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    /*cursor: pointer;*/
}

</style>
