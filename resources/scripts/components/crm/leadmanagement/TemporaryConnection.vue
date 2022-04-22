<template>
    <div>
        <v-col cols="12" v-if="leadSummary.is_temporary_connection">
            <b>
                There is a request for temporary connection for this property.
            </b>
        </v-col>
        <ValidationObserver ref="endConnection">
            <v-col cols="12" v-if="leadSummary.is_temporary_connection">
                <v-row>
                    <v-col cols="3" class="pt-5">
                        <b>Temp Connection</b>
                    </v-col>
                    <v-col cols="4">
                        <v-menu
                            v-model="connection_date_menu"
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
                                        v-bind="attrs"
                                        append-icon="mdi-calendar"
                                        v-model="modified_moving_date"
                                        :error-messages="errors[0]"
                                        hide-details="auto"
                                    >
                                        <template slot="append">
                                            <v-icon v-on="on">mdi-calendar</v-icon>
                                        </template>
                                    </v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker
                                v-model="moving_date"
                                @input="updateMovingDate"
                            ></v-date-picker>
                        </v-menu>
                    </v-col>
                </v-row>
            </v-col>
        </ValidationObserver>
    </div>          
</template>

<script>

export default {
    name: "TemporaryConnection",
    props: {
        leadSummary: {
            require: true
        },
        modified_moving_date: {
            require: true
        },
        connection_date_menu: {
            require: true
        },
        moving_date: {
            require: true
        },

    },
    data() {
        return {
             
        };
    },
};
</script>
