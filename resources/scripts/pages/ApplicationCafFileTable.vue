<template>
    <div>
        <v-card class="mt-2 hood-card-for-agency">
            <v-row class="crmTableRowDesign">
                <v-col cols="12" class="crm-table">
                    <v-data-table
                        :headers="headers"
                        :items="items"
                        :items-per-page="5"
                        :loading="loading"
                        :item-class="isSelectedClass"
                        :single-expand="singleExpand"
                        :expanded.sync="expanded"
                        item-key="app_id"
                        show-expand
                        class="row-pointer"
                        @click:row="onRowSelect"
                    >
                        <!--  Service start -->
                        <template v-slot:item.service="{ item }">
                            <v-select
                                v-model="service"
                                outlined
                                dense
                                solo
                                :items="item.service"
                            ></v-select>
                        </template>
                        <!--  Service end -->

                        <!--  Energy Supplier start -->
                        <template v-slot:item.energy_supplier="{ item }">
                            <v-select
                                v-model="energy_supplier"
                                outlined
                                dense
                                solo
                                :items="item.energy_supplier"
                            ></v-select>
                        </template>
                        <!--  Energy Supplier end -->

                        <!--  Plan start -->
                        <template v-slot:item.plan="{ item }">
                            <v-select
                                v-model="plan"
                                outlined
                                dense
                                solo
                                :items="item.plan"
                            ></v-select>
                        </template>
                        <!--  Plan end -->

                        <!--  ABN start -->
                        <template v-slot:item.abn="{ item }">
                            <v-text-field
                                placeholder="ABN"
                                outlined
                                dense
                                solo
                                v-model="abn">
                            </v-text-field>
                        </template>
                        <!--  ABN end -->

                        <!--  MNI start -->
                        <template v-slot:item.nmi="{ item }">
                            <v-text-field
                                placeholder="NMI"
                                outlined
                                dense
                                solo
                                v-model="nmi">
                            </v-text-field>
                        </template>
                        <!--  MNI end -->

                        <!--  MIRN start -->
                        <template v-slot:item.mirn="{ item }">
                            <v-text-field
                                placeholder="MIRN"
                                outlined
                                dense
                                solo
                                v-model="mirn">
                            </v-text-field>
                        </template>
                        <!--  MIRN end -->

                        <!--  Business Name start -->
                        <template v-slot:item.business_name="{ item }">
                            <v-text-field
                                placeholder="Business Name"
                                outlined
                                dense
                                solo
                                v-model="business_name">
                            </v-text-field>
                        </template>
                        <!--  Business Name end -->

                        <!--  status start -->
                        <template v-slot:items.status="{ item }">
                            <v-checkbox v-model="status"/>
                        </template>
                        <!--  status end -->

                        <template v-slot:expanded-item="{ headers, item }">
                            <td :colspan="headers.length">
                                 <ApplicationCafFileDetails :application='item'/>
                            </td>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
        </v-card>

    </div>
</template>

<script>
import AdvanceSearchModal from '@scripts/components/crm/modals/AdvanceSearchModal.vue';
import {LeadSearchFilterModel} from '@scripts/models/LeadSearchFilterModel';
import AgentFilterChip from '@scripts/components/crm/agent/AgentFilterChip';
import {sourcesNumberToName} from '@scripts/data/LeadSourceMap';
import Search from "@scripts/components/crm/Search";
import AuthService from "@scripts/services/AuthService";
import AgentApplicationDetails from "@scripts/components/crm/agent/AgentApplicationDetails";

export default {
    name: "ApplicationCafFileTable",
    props: [],
    components: {},
    data() {
        return {
            expanded: [],
            singleExpand: true,
            user: null,
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            loading: false,
            options: {
                sortDesc: [],
                sortBy: [],
            },
            headers: [
                {text: 'App ID', align: 'start', sortable: true, value: 'app_id'},
                {text: 'First Name', align: 'start', sortable: true, value: 'first_name'},
                {text: 'Last Name', align: 'start', sortable: true, value: 'last_name'},
                {text: 'Service', align: 'start', sortable: true, value: 'service'},
                {text: 'Energy Supplier', align: 'start', sortable: true, value: 'energy_supplier'},
                {text: 'Plan', align: 'start', sortable: true, value: 'plan'},
                {text: 'ABN', align: 'start', sortable: true, value: 'abn'},
                {text: 'NMI', align: 'start', sortable: true, value: 'nmi'},
                {text: 'MIRN', align: 'start', sortable: true, value: 'mirn'},
                {text: 'Business Name', align: 'start', sortable: true, value: 'business_name'},
                {text: 'Status', align: 'start', sortable: true, value: 'status'},
                {text: '', value: 'data-table-expand', align: 'start', sortable: true},
            ],
            items: [
                {
                    app_id: 1,
                    first_name: 'Shakil',
                    last_name: 'Hossain',
                    service: ['Service', 'B', 'C', 'D'],
                    energy_supplier: ['Energy Australia', 'B', 'C', 'D'],
                    plan: ['Total Plan', 'B', 'C', 'D'],
                    abn: '',
                    nmi: '',
                    mirn: '',
                    business_name: '',
                    status: '',
                },
                {
                    app_id: 2,
                    first_name: 'Rakib Hasan',
                    last_name: 'Hossain',
                    service: ['Service', 'B', 'C', 'D'],
                    energy_supplier: ['Energy Australia', 'B', 'C', 'D'],
                    plan: ['Total Plan', 'B', 'C', 'D'],
                    abn: '',
                    nmi: '',
                    mirn: '',
                    business_name: '',
                    status: '',
                },
            ],
            selectedRowId: 0,
        }
    },
    computed: {},
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
    watch: {},
    mounted() {

    }
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    cursor: pointer;
}

</style>
