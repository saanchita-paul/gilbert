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
                <td>ID #{{ movingInfo.id }}</td>
                <td>{{ movingInfo.origin_address_text }}</td>
                <td>{{ movingInfo.distance_type }}</td>
                <td>{{ movingInfo.service_type }}</td>
                <td>{{ movingInfo.house_type }}</td>
                <td>{{ movingInfo.house_size }}</td>
                <td>{{ movingInfo.has_order }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import CustomerService from "@scripts/services/CustomerService";
import { merge } from 'lodash-es';
import CustomerMovingInfo from "@scripts/models/customer-profile/CustomerMovingInfo";

export default {
    name: "CustomerMovingInfo",
    props: ['customer'],
    data() {
        return {
            movingInfo: new CustomerMovingInfo()
        }
    },
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
    },
    async mounted() {
        merge(this.movingInfo, await CustomerService.getMovingInfo(this.customer.id));
    }
}
</script>

<style scoped>

</style>
