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
            <tr v-for="service in services">
                <td>ID #{{ service.id }}</td>
                <td>{{ service.category_name }}</td>
                <td>{{ service.postcode }}</td>
                <td>{{ service.business_name }}</td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import CustomerService from "@scripts/services/CustomerService";

export default {
    name: "CustomerOtherService",
    props: ['customer'],
    data() {
        return {
            services: []
        }
    },
    computed: {
        columnNames() {
            return [
                'Other ID',
                'Category',
                'Location',
                'Business Name'
            ];
        },
    },
    async mounted() {
        this.services = await CustomerService.getLocalSearch(this.customer.id);
    }
}
</script>

<style scoped>

</style>
