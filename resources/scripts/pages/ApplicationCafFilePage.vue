<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <v-card class="hood-card">
                    <h3 class="page-title">Chatbot Applications & CAF Files</h3>
                </v-card>
            </v-col>
        </v-row>


        <v-row>
            <v-col>
                <h5>Filters</h5>
                <ApplicationCafFileFilter></ApplicationCafFileFilter>
            </v-col>
        </v-row>

        <v-row>
            <v-col>
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
                    <template v-slot:expanded-item="{ headers, item }">
                        <td :colspan="headers.length">
                            <ApplicationCafFileDetails :cafFileData='item'></ApplicationCafFileDetails>
                        </td>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import ApplicationCafFileDetails from '@scripts/pages/ApplicationCafFileDetails';
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";

export default {
    name: "ApplicationCafFilePage",
    components: {
        ApplicationCafFileFilter,
        ApplicationCafFileDetails
    },

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
                {text: '', value: 'data-table-expand', align: 'start', sortable: true},
                { text: '', value: 'data-table-select' }
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
        async getCafFiles () {
            let data = await ApplicationCafFileService.getApplicationCafFileData();
            console.log(data);

            data.forEach(dt=> {
                console.log(dt);
            })
        },
    },

    mounted() {
        this.getCafFiles();
    },
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    cursor: pointer;
}
</style>

