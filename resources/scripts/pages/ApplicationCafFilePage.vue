<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <v-card class="hood-card">
                    <h3 class="page-title">Chatbot Applications & CAF Files</h3>
                </v-card>
            </v-col>
        </v-row>


<!--        <v-row>-->
<!--            <v-col>-->
<!--                <h5>Filters</h5>-->
<!--                <ApplicationCafFileFilter></ApplicationCafFileFilter>-->
<!--            </v-col>-->
<!--        </v-row>-->

<!--        <template>-->
<!--            <v-data-table-->
<!--                :headers="headers"-->
<!--                :items="items"-->
<!--                :items-per-page="5"-->
<!--                class="elevation-1"-->
<!--            >-->
<!--                &lt;!&ndash;  Service start &ndash;&gt;-->
<!--                <template v-slot:item.service="{ item }">-->
<!--                    <v-select-->
<!--                        v-model="service"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        :items="item.service"-->
<!--                    ></v-select>-->
<!--                </template>-->
<!--                &lt;!&ndash;  Service end &ndash;&gt;-->

<!--                &lt;!&ndash;  Energy Supplier start &ndash;&gt;-->
<!--                <template v-slot:item.energy_supplier="{ item }">-->
<!--                    <v-select-->
<!--                        v-model="energy_supplier"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        :items="item.energy_supplier"-->
<!--                    ></v-select>-->
<!--                </template>-->
<!--                &lt;!&ndash;  Energy Supplier end &ndash;&gt;-->

<!--                &lt;!&ndash;  Plan start &ndash;&gt;-->
<!--                <template v-slot:item.plan="{ item }">-->
<!--                    <v-select-->
<!--                        v-model="plan"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        :items="item.plan"-->
<!--                    ></v-select>-->
<!--                </template>-->
<!--                &lt;!&ndash;  Plan end &ndash;&gt;-->

<!--                &lt;!&ndash;  ABN start &ndash;&gt;-->
<!--                <template v-slot:item.abn="{ item }">-->
<!--                    <v-text-field-->
<!--                        placeholder="ABN"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        v-model="abn">-->
<!--                    </v-text-field>-->
<!--                </template>-->
<!--                &lt;!&ndash;  ABN end &ndash;&gt;-->

<!--                &lt;!&ndash;  MNI start &ndash;&gt;-->
<!--                <template v-slot:item.nmi="{ item }">-->
<!--                    <v-text-field-->
<!--                        placeholder="NMI"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        v-model="nmi">-->
<!--                    </v-text-field>-->
<!--                </template>-->
<!--                &lt;!&ndash;  MNI end &ndash;&gt;-->

<!--                &lt;!&ndash;  MIRN start &ndash;&gt;-->
<!--                <template v-slot:item.mirn="{ item }">-->
<!--                    <v-text-field-->
<!--                        placeholder="MIRN"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        v-model="mirn">-->
<!--                    </v-text-field>-->
<!--                </template>-->
<!--                &lt;!&ndash;  MIRN end &ndash;&gt;-->

<!--                &lt;!&ndash;  Business Name start &ndash;&gt;-->
<!--                <template v-slot:item.business_name="{ item }">-->
<!--                    <v-text-field-->
<!--                        placeholder="Business Name"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        solo-->
<!--                        v-model="business_name">-->
<!--                    </v-text-field>-->
<!--                </template>-->
<!--                &lt;!&ndash;  Business Name end &ndash;&gt;-->

<!--                &lt;!&ndash;  status start &ndash;&gt;-->
<!--                <template v-slot:items.status="{ item }">-->
<!--                    <v-checkbox v-model="status" />-->
<!--                </template>-->
<!--                &lt;!&ndash;  status end &ndash;&gt;-->

<!--            </v-data-table>-->
<!--        </template>-->
    </v-container>
</template>

<script>
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";

export default {
    name: "ApplicationCafFilePage",
    components: {
        ApplicationCafFileFilter
    },

    data() {
        return {
            // service: 'Service',
            // energy_supplier: 'Energy Australia',
            // plan: 'Total Plan',
            // abn: '123456',
            // nmi: '12121212121',
            // mirn: '141525265251',
            // business_name: 'ABCNDDKDKKD',
            // status: '',
            // headers: [
            //     {text: 'App ID', value: 'app_id'},
            //     {text: 'First Name', value: 'first_name'},
            //     {text: 'Last Name', value: 'last_name'},
            //     {text: 'Service', value: 'service'},
            //     {text: 'Energy Supplier', value: 'energy_supplier'},
            //     {text: 'Plan', value: 'plan'},
            //     {text: 'ABN', value: 'abn'},
            //     {text: 'NMI', value: 'nmi'},
            //     {text: 'MIRN', value: 'mirn'},
            //     {text: 'Business Name', value: 'business_name'},
            //     {text: 'Status', value: 'status'},
            // ],
            // items: [
            //     {
            //         app_id: 1,
            //         first_name: 'Shakil',
            //         last_name: 'Hossain',
            //         service: ['Service', 'B', 'C', 'D'],
            //         energy_supplier: ['Energy Australia', 'B', 'C', 'D'],
            //         plan: ['Total Plan', 'B', 'C', 'D'],
            //         abn: '',
            //         nmi: '',
            //         mirn: '',
            //         business_name: '',
            //         status: '',
            //     },
            // ],
        }
    },

    methods: {
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
</style>

