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
    name: "CustomerMovingInfo",
    props: ['customer'],
    computed: {
        columnNames() {
            return [
                'Moving ID',
                'Origin',
                'Distance Type',
                'Moving Type',
                'House Type',
                'House Size',
                'Order'
            ];
        },
        columnValues() {
            return !this.customer
                ? ['', '', '', '', '', '', '']
                : [
                    'TBC',
                    this.customer.from ? this.customer.from.formatted_address : '',
                    'TBC',
                    this.customer.moving_service_type,
                    capitalize(this.customer.house_type),
                    this.customer.bedrooms,
                    this.customer.has_booked_movers ? 'Yes' : 'No'
                ];
        },
    }
}
</script>

<style scoped>

</style>
