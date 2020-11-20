<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
            <tr>
                <th class="text-left" v-for="(column, columnIndex) in columnNames" :v-key="`COLUMN-${columnIndex}`">
                    {{column}}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td v-for="(columnValue, columnValueIndex) in columnValues" :v-key="`VALUE-${columnValueIndex}`">{{ columnValue }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import {capitalize} from "lodash-es";

export default {
    name: "CustomerConnectionInfo",
    props: ['customer'],
    computed: {
        columnNames() {
            return [
                'Connection ID',
                'Connection Address',
                'Provider',
                'Usage',
                'Plan',
                'Solar'
            ];
        },
        columnValues() {
            return !this.customer
                ? ['', '', '', '', '', '']
                : [
                    'TBC',
                    this.customer.to ? this.customer.to.formatted_address : '',
                    'TBC',
                    capitalize(this.customer.energy_usage),
                    'TBC',
                    this.customer.solar_panel === 'solar'
                        ? 'Yes'
                        : this.customer.solar_panel === 'no_solar'
                        ? 'No'
                        : 'Considering'
                ];
        },
    }
}
</script>

<style scoped>

</style>
