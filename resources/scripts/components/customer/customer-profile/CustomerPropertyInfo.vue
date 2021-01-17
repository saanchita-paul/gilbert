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
                <td>ID #{{ propertyInfo.id }}</td>
                <td>{{ propertyInfo.property_address_text }}</td>
                <td>{{ propertyInfo.occupation_type }}</td>
                <td>{{ propertyInfo.house_type }}</td>
                <td>{{ propertyInfo.house_size }}</td>
                <td><div class="d-flex flex-column py-1">
                    <span v-for="(activity, index) in propertyInfo.activities" :key="index">
                        {{activity}}
                    </span>
                </div></td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { capitalize } from 'lodash-es';
import CustomerService from "@scripts/services/CustomerService";
import CustomerProperty from "@scripts/models/CustomerProperty";
import { merge } from 'lodash-es';

export default {
    name: "CustomerPropertyInfo",
    props: ['customer'],
    data() {
        return {
            propertyInfo: new CustomerProperty()
        }
    },
    computed: {
        columnNames() {
            return [
                'Property ID',
                'Property Address',
                'Occupation Type',
                'House Type',
                'House Size',
                'Activities'
            ];
        },
        columnValues() {
            return !this.customer
                ? ['', '', '', '', '']
                : [
                    'TBC',
                    this.customer.from ? this.customer.from.formatted_address : '',
                    this.customer.rent ? 'Rent' : 'Owned',
                    capitalize(this.customer.house_type),
                    this.customer.bedrooms
                ];
        },
        activities() {
            let output = [];
            if (!this.customer) {
                return output;
            }
            if (this.customer.has_finished_utility_flow) {
                output = [...output, 'Connect Energy,'];
            }
            if (this.customer.has_finished_onboarding) {
                output = [...output, 'Moving Calculator,'];
            }
            if (this.customer.has_booked_movers) {
                output = [...output, 'Booked Movers,'];
            }
            if (this.customer.has_setup_reminders) {
                output = [...output, 'Setup Reminders'];
            }
            return output;
        }
    },
    async mounted() {
        merge(this.propertyInfo, await CustomerService.getPropertyInfo(this.customer.id));
    }
}
</script>

<style scoped>

</style>
