<template>
    <div>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="gilbertApplications"
            :server-items-length="totalItems"
            :options.sync="options"
            :single-expand=true
            :expanded.sync="expanded"
            :item-class="isSelectedClass"
            item-key="id"
            show-expand
            show-select
            class="row-pointer"
            @click:row="onRowSelect"
        >

            <!-- row expend start-->
            <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length" style="padding: 0">
                    <NbnApplicationCafFileDetails :application='item'></NbnApplicationCafFileDetails>
                </td>
            </template>
            <!-- row expend end-->
        </v-data-table>
    </div>
</template>

<script>
import {debounce} from "lodash-es";
import NbnApplicationCafFileDetails from "@scripts/pages/NbnApplicationCafFileDetails.vue";

export default {
    name: "NbnApplicationCafFileTable",
    components: {NbnApplicationCafFileDetails},
    props: ["value", "gilbertApplications", "totalItems", 'pages'],
    data() {
        return {
            selected: [],
            expanded: [],
            selectedRowId: 0,
            disabledCount: 0,
            headers: [
                {text: 'App ID', align: 'start', sortable: true, value: 'id', class: 'black--text'},
                {text: 'Name', align: 'start', sortable: true, value: 'full_name', class: 'black--text'},
                {text: 'Address', align: 'start', sortable: true, value: 'address_text', class: 'black--text'},
                {text: 'Conn Date', align: 'start', sortable: true, value: 'connection_date', class: 'black--text'},
                {text: 'Created Date', align: 'start', sortable: true, value: 'created_at', class: 'black--text'},
                {text: 'Supplier', align: 'start', sortable: true, value: 'supplier', class: 'black--text'},
                // {text: 'Plan', align: 'start', sortable: true, value: 'plan', class: 'black--text'},
                {text: 'Status', align: 'start', sortable: true, value: 'status', class: 'black--text'},
                {text: '', value: 'data-table-expand', sortable: false, align: 'start'},
                {text: '', value: 'data-table-select', sortable: false}
            ],
            options: {},
        }
    },

    watch: {
        selected(val) {
            this.$emit('input', val)
        },
        options: {
            handler: debounce(function () {
                this.loadGilbertCafFileList();
            }, 200),
            deep: true,
        },
        pages(val) {
            this.options.page = val;
        }
    },

    methods: {

        onchangeRow(item) {
            this.$emit('selectRowCafFiles', item);
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

        loadGilbertCafFileList() {
            const meta = {
                page: this.options.page,
                per_page: this.options.itemsPerPage === -1 ? this.totalItems : this.options.itemsPerPage,
            }
            this.$emit('reloadDataTable', meta);
        },
    },

}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    /*cursor: pointer;*/
}

</style>
