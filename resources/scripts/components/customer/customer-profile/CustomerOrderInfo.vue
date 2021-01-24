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
            <tr v-for="orderDetails in orders">
                <td>ID #{{ orderDetails.id }}</td>
                <td>{{ orderDetails.total_cost }}</td>
                <td>{{ orderDetails.service_type }}</td>
                <td><div class="d-flex flex-column py-1">
                    <span v-for="(item, index) in orderDetails.equipments" :key="index">
                        {{item}}
                    </span>
                </div></td>
                <td>{{ orderDetails.moving_time }}</td>
                <td><div class="d-flex flex-column py-1">
                    <span v-for="(item, index) in orderDetails.extra_services" :key="index">
                        {{item}}
                    </span>
                </div></td>
                <td>{{ orderDetails.payment_type }}</td>
                <td>{{ orderDetails.status }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import CustomerService from "@scripts/services/CustomerService";
import { merge } from 'lodash-es';
import CustomerConnection from "@scripts/models/CustomerOrder";
import CustomerOrder from "@scripts/models/CustomerOrder";

export default {
    name: "CustomerOrderInfo",
    props: ['customer'],
    data() {
        return {
            orders: []
        }
    },
    computed: {
        columnNames() {
            return [
                'Order ID',
                'Value',
                'Service Type',
                'Service Details',
                'Moving Time',
                'Extras',
                'Payment Type',
                'Status'
            ];
        },
    },
    async mounted() {
        this.orders = await CustomerService.getOrderDetails(this.customer.id);
    }
}
</script>

<style scoped>

</style>
