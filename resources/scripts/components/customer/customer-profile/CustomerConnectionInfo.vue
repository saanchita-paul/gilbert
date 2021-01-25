<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
            <tr>
                <th class="text-left customer-data-title"
                    v-for="(column, columnIndex) in columnNames"
                    :key="`COLUMN-${columnIndex}`"
                >
                    {{column}}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>ID #{{ connectionInfo.id }}</td>
                <td>{{ connectionInfo.connection_address_text }}</td>
                <td>{{ connectionInfo.provider }}</td>
                <td>{{ connectionInfo.energy_type }}</td>
                <td>{{ connectionInfo.selected_plan }}</td>
                <td>{{ connectionInfo.solar }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { capitalize } from 'lodash-es';
import CustomerService from "@scripts/services/CustomerService";
import CustomerProperty from "@scripts/models/customer-profile/CustomerProperty";
import { merge } from 'lodash-es';
import CustomerConnection from "@scripts/models/customer-profile/CustomerConnection";

export default {
    name: "CustomerConnectionInfo",
    props: ['customer'],
    data() {
        return {
            connectionInfo: new CustomerConnection()
        }
    },
    computed: {
        columnNames() {
            return [
                'Connection ID',
                'Connection Address',
                'Provider',
                'Energy Type',
                'Selected Plan',
                'Solar'
            ];
        },
    },
    async mounted() {
        merge(this.connectionInfo, await CustomerService.getConnectionInfo(this.customer.id));
    }
}
</script>

<style scoped>

</style>
