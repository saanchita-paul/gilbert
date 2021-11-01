<template>
    <v-row class="pa-2">
        <h4>Connection Details</h4>
        <v-col cols="11">
            <h4>Status</h4>
            <p>What is the status of this application?</p>

            <div>
                <v-btn>In Progress</v-btn>
                <v-btn>Needs more info</v-btn>
                <v-btn>Submitted</v-btn>
                <v-btn >Connected</v-btn>
                <v-btn >Can’t Connect</v-btn>
            </div>

        </v-col>
        <v-col cols="8">
            <div class="text-field">
                <ValidationProvider
                    name="Connection Date"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <v-menu
                        v-model="connection_date"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <ValidationProvider
                                name="Connection Date"
                                rules="required|valid-date|not-holiday:@h_state"
                                v-slot="{ errors }"
                            >
                                <v-text-field
                                    placeholder="DD/MM/YYYY"
                                    outlined
                                    dense
                                    append-icon="mdi-calendar"
                                    v-model="property_details.moving_date"
                                    v-bind="attrs"
                                    :error-messages="errors[0]"
                                    hide-details="auto"
                                    @input="updateLeads"
                                    @change="updateConDatePicker"
                                >
                                    <template slot="append">
                                        <v-icon v-on="on">mdi-calendar</v-icon>
                                    </template>
                                </v-text-field>
                            </ValidationProvider>
                        </template>
                        <v-date-picker
                            v-model="moving_date"
                            :min="minConnectionDate"
                            @input="connection_date = false"
                        ></v-date-picker>
                    </v-menu>
                </ValidationProvider>
            </div>
            <v-divider></v-divider>
        </v-col>
        <v-col cols="8">
            <h4>Service Providers</h4>
            <h5>Select a provider for 54 Haughton Road, Oakleigh</h5>
            <v-select :items="waterServiceDD" item-value="source" item-text="text">
            </v-select>
        </v-col>
    </v-row>
</template>

<script>
export default {
name: "WaterService",
    props:[],
    data(){
    return {
        waterServiceDD: [
            {
                text: 'Greater Western Water',
                source: 'greater_western_water'
            },
            {
                text: 'South East Water',
                source: 'south_east_water'
            },
            {
                text: 'Yarra Valley Water',
                source: 'yarra_valley_water'
            },
        ]
    }
    }
}
</script>

<style scoped>

</style>
