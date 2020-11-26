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
                <td>
                    <template v-for="(activity, activityIndex) in activities" :v-key="`ACTIVITY-${activityIndex}`">
                        {{
                            activityIndex === activities.length - 1
                                ? activity.slice(0, -1)
                                : activity
                        }}<br />
                    </template>
                </td>
            </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
import { capitalize } from 'lodash-es';

export default {
    name: "CustomerPropertyInfo",
    props: ['customer'],
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
    }
}
</script>

<style scoped>

</style>
