<template>
    <div>
        <v-data-table
            v-model="selected"
            :headers="headers"
            :items="desserts"
            :single-expand=true
            :expanded.sync="expanded"
            :item-class="isSelectedClass"
            item-key="id"
            :items-per-page="50"
            show-select
            show-expand
            class="row-pointer"
            @click:row="onRowSelect"
        >
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

export default {
    name: "ApplicationCafFileTable",
    components: {ApplicationCafFileDetails},
    props: ["cafFiles", 'value'],
    data() {
        return {
            selected: [],
            expanded: [],
            selectedRowId: 0,
            headers: [
                {text: 'App ID', align: 'start', sortable: true, value: 'id', class: 'black--text'},
                {text: 'Name', align: 'start', sortable: true, value: 'name', class: 'black--text'},
                {text: 'Address', align: 'start', sortable: true, value: 'address', class: 'black--text'},
                {text: 'Conn Date', align: 'start', sortable: true, value: 'date', class: 'black--text'},
                {text: 'Service', align: 'start', sortable: true, value: 'service', class: 'black--text'},
                {text: 'Supplier', align: 'start', sortable: true, value: 'supplier', class: 'black--text'},
                {text: 'Plan', align: 'start', sortable: true, value: 'plan', class: 'black--text'},
                {text: 'Business Name', align: 'start', sortable: true, value: 'business_name', class: 'black--text'},
                {text: 'ABN', align: 'start', sortable: true, value: 'abn', class: 'black--text'},
                {text: 'Status', align: 'start', sortable: true, value: 'status', class: 'black--text'},
                {text: '', value: 'data-table-expand', align: 'start'},
                {text: '', value: 'data-table-select'}
            ],
            desserts: [
                {
                    id: 1,
                    name: 'Robin',
                    address: 'Dhaka',
                    date: '1985/05/05',
                    service: 'Energy Australia',
                    supplier: 'Total Plan',
                    plan: '251525252',
                    business_name: 'New',
                    abn: '2515425245',
                    status: 'Active',
                },
                {
                    id: 2,
                    name: 'Rakib',
                    address: 'Dhaka',
                    date: '2022/05/05',
                    service: 'Energy Australia',
                    supplier: 'Total Plan',
                    plan: '251525252',
                    business_name: 'New',
                    abn: '2515425245',
                    status: 'Inactive',
                },
            ],
        }
    },
    computed: {

    },
    watch: {
        selected(val) {
            this.$emit('input', val)
        }
    },

    mounted() {

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
    },

}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    /*cursor: pointer;*/
}

</style>
